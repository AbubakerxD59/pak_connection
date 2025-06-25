<?php

namespace App\Http\Controllers\Frontend;

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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
    private $package;
    private $user;
    private $role;
    private $promoCode;
    private $stripe;
    private $order;
    private $webhook;
    public function __construct(Package $package, User $user, Role $role, PromoCode $promoCode, Order $order, WebhookCall $webhook)
    {
        $this->package = $package;
        $this->user = $user;
        $this->role = $role;
        $this->promoCode = $promoCode;
        $this->order = $order;
        $this->webhook = $webhook;
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }
    public function index()
    {
        $packages = $this->package->get();
        return view('frontend.home', compact('packages'));
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
        $data = $request->validate([
            'full_name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed|min:6',
            'whatsapp_number' => 'required',
            'city' => 'required',
            'country' => 'required',
            'promo' => 'sometimes',
            'emergency_full_name' => 'sometimes',
            'emergency_phone_number' => 'sometimes',
        ]);
        $package = $this->package->find($request->package_id);
        if ($package) {
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

            if (empty($promo)) {
                $session = $this->stripe->checkout->sessions->create([
                    "line_items" => array(
                        ["price" => $package->stripe_price_id, "quantity" => "1"]
                    ),
                    "shipping_address_collection" => [
                        "allowed_countries" => ["GB", "PK"]
                    ],
                    "customer_email" => $request->email,
                    "mode" => "subscription",
                    "success_url" => route("frontend.checkout_success", [], true) . "?session_id={CHECKOUT_SESSION_ID}",
                    "cancel_url" => route('frontend.home'),
                ]);
            } else {
                $session = $this->stripe->checkout->sessions->create([
                    "line_items" => array(
                        ["price" => $package->stripe_price_id, "quantity" => "1"]
                    ),
                    "shipping_address_collection" => [
                        "allowed_countries" => ["GB", "PK"]
                    ],
                    "discounts" => array(
                        ["coupon" => $promo->stripe_coupon_id]
                    ),
                    "customer_email" => $request->email,
                    "mode" => "subscription",
                    "success_url" => route("frontend.checkout_success", [], true) . "?session_id={CHECKOUT_SESSION_ID}",
                    "cancel_url" => route('frontend.home'),
                ]);
            }
            $user = $this->user->search($request->email)->first();
            if ($user) {
                $user->update([
                    'full_name' => $request->full_name,
                    'password' => $request->password,
                    'whatsapp_number' => $request->whatsapp_number,
                    'phone_number' => $request->phone_number,
                    'city' => $request->city,
                    'country' => $request->country,
                    'address' => $request->address,
                    'stripe_id' => $session->id,
                    'status' => 1,
                    'emergency_full_name' => $request->has("emergency_full_name") && $request->emergency_full_name ? $request->emergency_full_name : null,
                    'emergency_phone_number' => $request->has("emergency_phone_number") && $request->emergency_phone_number ? $request->emergency_phone_number : null,
                ]);
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

            $this->order->create([
                "user_id" => $user->id,
                "session_id" => $session->id,
                "package_id" => $package->id,
                "promo_id" => $promo ? $promo->id : "",
                "total_amount" => $promo ? calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 1) : $package->price,
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
            Log::info($e);
            // Invalid payload
            return response($e, 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::info($e);
            // Invalid signature
            return response("signature verification", 400);
        }
        Log::info($event);
        // Handle the event
        switch ($event->type) {
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
                $order = $this->order->where('session_id', $session->id)->unpaid()->first();
                if ($order) {
                    $order->update([
                        "customer_id" => $session->customer,
                        "status" => $status
                    ]);
                }
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        return response("Here", 200);
    }
}
