<?php

namespace App\Http\Controllers\Frontend;

use App\Events\BookedServiceStatusUpdated;
use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use Stripe\StripeClient;
use App\Models\PromoCode;
use App\Models\WebhookCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\BookService;
use App\Models\Transaction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Carbon\Carbon;


class HomeController extends Controller
{
    private $package;
    private $user;
    private $role;
    private $promoCode;
    private $stripe;
    private $order;
    private $webhook;
    private $transaction;
    private $bookService;
    public function __construct(Package $package, User $user, Role $role, PromoCode $promoCode, Order $order, WebhookCall $webhook, Transaction $transaction, BookService $bookService)
    {
        $this->package = $package;
        $this->user = $user;
        $this->role = $role;
        $this->promoCode = $promoCode;
        $this->order = $order;
        $this->webhook = $webhook;
        $this->transaction = $transaction;
        $this->bookService = $bookService;
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }


    public function index()
    {
        if (auth()->check() && auth()->user()->getPackage()) {
            return view("frontend.auth_home");
        } else {
            $packages = $this->package->get();
            return view('frontend.home', compact('packages'));
        }
    }

    public function update_packages()
    {
        $packages = $this->package->get();
        return view('frontend.member.update-package', compact('packages'));
    }


    public function buyMembership($id = null)
    {
        $package = $this->package->find($id);
        if ($package) {
            return view('frontend.become-a-member', compact('package'));
        } else {
            return redirect()->back()->with('error', 'Package not Found!');
        }
    }

    public function checkout(Request $request)
    {
        $rules = [
            'full_name' => 'required',
            'email' => 'required',
            'whatsapp_number' => 'required',
            'city' => 'required',
            'country' => 'required',
            'promo' => 'sometimes',
            'emergency_full_name' => 'required',
            'emergency_phone_number' => 'required',
        ];
        if (!auth()->check()) {
            $rules['password'] = 'required|confirmed|min:6';
        }
        $data = $request->validate($rules);
        $package = $this->package->find($request->package_id);
        if ($package) {
            $session = array();
            $promo = '';
            if (!empty($request->promo)) {
                $promo = $this->promoCode->search($request->promo)->active()->first();
                if ($promo) {
                    if (!$promo->valid()) {
                        return redirect()->back()->with('error', 'Invalid Promo Code!');
                    }
                } else {
                    return redirect()->back()->with('error', 'Invalid Promo Code!');
                }
            }

            $user = $this->user->search($request->email)->first();
            if (auth()->check()) {
                $user = auth()->user();
            }
            // Checkout session
            $session["line_items"] = [["price" => $package->stripe_price_id, "quantity" => "1"]];
            $session["shipping_address_collection"] = ["allowed_countries" => ["GB", "PK"]];
            if ($user && $user->customer_id) {
                $session["customer"] = $user->customer_id;
            } else {
                $session["customer_email"] = $request->email;
            }
            if ($promo) {
                $session["discounts"] = [["coupon" => $promo->stripe_coupon_id]];
            }
            $session["mode"] = "subscription";
            $session["success_url"] = route("frontend.checkout_success", [], true) . "?session_id={CHECKOUT_SESSION_ID}";
            $session["cancel_url"] = route('frontend.home');
            $session = $this->stripe->checkout->sessions->create($session);
            // Checkout session
            if ($user) {
                $update = [
                    'full_name' => $request->full_name,
                    'whatsapp_number' => $request->whatsapp_number,
                    'phone_number' => $request->phone_number,
                    'city' => $request->city,
                    'country' => $request->country,
                    'address' => $request->address,
                    'stripe_id' => $session->id,
                    'status' => 1,
                    'emergency_full_name' => $request->has("emergency_full_name") && $request->emergency_full_name ? $request->emergency_full_name : null,
                    'emergency_phone_number' => $request->has("emergency_phone_number") && $request->emergency_phone_number ? $request->emergency_phone_number : null,
                ];
                if (empty($user->membership_id)) {
                    $update["membership_id"] =  rand(100000, 999999);
                }
                $user->update($update);
            } else {
                $user = $this->user->create([
                    'full_name' => $request->full_name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'whatsapp_number' => $request->whatsapp_number,
                    'phone_number' => $request->phone_number,
                    'city' => $request->city,
                    'country' => $request->country,
                    'address' => $request->address,
                    'membership_id' => rand(100000, 999999),
                    'stripe_id' => $session->id,
                    'status' => 1,
                    'emergency_full_name' => $request->has("emergency_full_name") && $request->emergency_full_name ? $request->emergency_full_name : null,
                    'emergency_phone_number' => $request->has("emergency_phone_number") && $request->emergency_phone_number ? $request->emergency_phone_number : null,
                ]);
            }
            $role = $this->role->where('name', 'Customer')->first();
            if (!empty($role)) {
                $user->assignRole($role->name);
            }

            $order = $this->order->create([
                "user_id" => $user->id,
                "session_id" => $session->id,
                "package_id" => $package->id,
                "promo_id" => $promo ? $promo->id : "",
                "total_amount" => $package->price,
                "discount_amount" => $promo ? calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 1) : $package->price,
                "payable_amount" => $promo ? $package->price -  calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 1) : $package->price,
                "order_num" => Order::generateAvailableOrderNum(),
                "status" => "0",
            ]);


            $this->transaction->create([
                "user_id" => $user->id,
                "order_id" => $order->id,
                "session_id" => $session->id,
                "package_id" => $package->id,
                "promo_id" => $promo ? $promo->id : "",
                "total_amount" => $package->price,
                "discount_amount" => $promo ? calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 1) : $package->price,
                "payable_amount" => $promo ? $package->price -  calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 1) : $package->price,
                "transaction_type" => "order",
                "status" => "0",
            ]);

            return redirect($session->url);
        } else {
            return redirect()->back()->with('error', 'Package not Found!');
        }
    }


    public function success(Request $request)
    {
        $customer = null;
        try {
            $session = $this->stripe->checkout->sessions->retrieve($request->session_id);
            $user = $this->user->where('stripe_id', $session->id)->first();

            $transaction = $this->transaction->where('session_id', $request->session_id)->first();

            $package = $this->package->find($transaction->package_id);

            $pkg_str_time = Carbon::now();
            $pkg_end_time = getPackageEndTime($pkg_str_time, $package);


            $user->update([
                "package_id" => $package->id,
                "pkg_start_time" => $pkg_str_time,
                "pkg_end_time" => $pkg_end_time,
                "package_status" => 1,
            ]);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
        return view('frontend.success', compact('user'));
    }

    public function webhook(Request $request)
    {
        $endpoint_secret = env("STRIPE_WEBHOOK_SECRET");
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response($e, 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response("signature verification", 400);
        }
        // Handle the event
        switch ($event->type) {
            case 'payment_intent.payment_failed':

                // session is completed
            case 'checkout.session.completed':
                $session = $event->data->object;
                $status = $session->payment_status == "paid" ? "1" : "2";
                $this->webhook->create([
                    "name" => $session->object,
                    "url" => $session->url,
                    "headers" => $session->id,
                    "payload" => $session,
                    "exception" => "",
                ]);

                // <<<<<<<<<<<<<<<<<<<<<

                $session_id = $session->id;
                $paymentLink = $session->payment_link;

                if ($paymentLink) {
                    // payment link apis (deposit and invoice)
                    $transaction = $this->transaction->paymentLink($paymentLink)->unpaid()->first();
                } else {
                    // checkout session (order)
                    $transaction = $this->transaction->session($session_id)->unpaid()->first();
                }

                if ($transaction) {
                    $type = $transaction->transaction_type;

                    if ($type === 'order') {
                        $order = $this->order->where('session_id', $session->id)->unpaid()->first();
                        if ($order) {
                            $order->update([
                                "customer_id" => $session->customer,
                                "status" => $status
                            ]);
                            $user = $this->user->find($order->user_id);
                            $user->update([
                                "customer_id" => $session->customer
                            ]);
                        }
                    }

                    if (in_array($type, ['deposit', 'invoice'])) {
                        $bookServiceId = $transaction->book_service_id ?? null;
                        if ($bookServiceId) {
                            $bookedService = $this->bookService->find($bookServiceId);
                            if ($bookedService) {
                                if ($type == "deposit") {
                                    $status = 3;
                                }

                                if ($type == "invoice") {
                                    $status = 6;
                                }

                                $bookedService->status = $status;
                                $bookedService->save();
                                // Send email notification
                                event(new BookedServiceStatusUpdated($bookedService));
                            }
                        }
                    }

                    $transaction->update([
                        'customer_id' => $session->customer,
                        'status' => 1
                    ]);
                }

                // >>>>>>>>>>>>>>>>>>>>>

            default:
                info("Received unknown event type" . $event->type);
        }
        return response("Here", 200);
    }
}
