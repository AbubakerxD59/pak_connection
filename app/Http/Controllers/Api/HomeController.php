<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use Stripe\StripeClient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        $last_booking = $user->bookServices()->latest()->first();
        $upcoming_booking = $user->bookServices()->notCompleted();
        if ($last_booking) {
            $upcoming_booking = $upcoming_booking->where("id", "!=", $last_booking->id);
        }
        $upcoming_booking = $upcoming_booking->get();

        $response = [
            "user" => [
                "name" => $user->full_name,
                "current_package" => $user->getPackage() ? $user->getPackage() : null,
                "expiry_date" => !empty($user->pkg_end_time) ? date('Y-m-d', strtotime($user->pkg_end_time)) : null
            ],
            "last_booking" => $last_booking,
            "upcoming_booking" => count($upcoming_booking) > 0 ? $upcoming_booking : null,
        ];
        return $this->successResponse($response);
    }

    public function profile()
    {
        $user = Auth::user();
        return $this->successResponse($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        if (count($data) > 0) {
            $user->update($data);
        }
        return $this->successResponse("Profile updated Successfully!", $user);
    }

    public function checkout(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $data = Validator::make($request->all(), [
            "package_id" => "required|exists:packages,id",
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors()->first(), 400);
        } else {
            try {
                DB::beginTransaction();
                $package = Package::findOrFail($request->package_id);
                // --- 3. Prepare Session Parameters ---
                $sessionParams = [];
                $sessionParams['line_items'] = [
                    [
                        'price' => $package->stripe_price_id,
                        'quantity' => 1,
                    ]
                ];
                $sessionParams['shipping_address_collection'] = [
                    'allowed_countries' => ['GB', 'PK']
                ];
                $user = Auth::user();
                if ($user && $user->customer_id) {
                    $sessionParams['customer'] = $user->customer_id;
                }
                $sessionParams['mode'] = 'subscription';
                $sessionParams['success_url'] = route("frontend.checkout_success", [], true) . "?session_id={CHECKOUT_SESSION_ID}";
                $sessionParams['cancel_url'] = route('frontend.home');

                // --- 4. Create Stripe Checkout Session ---
                $session = $stripe->checkout->sessions->create($sessionParams);

                // --- 5. Create Order ---
                $order = Order::create([
                    "user_id" => $user->id,
                    "session_id" => $session->id,
                    "package_id" => $package->id,
                    "total_amount" => $package->price,
                    "payable_amount" =>  $package->price,
                    "order_num" => Order::generateAvailableOrderNum(),
                    "status" => "0",
                ]);

                // --- 6. Create Transaction ---
                Transaction::create([
                    "user_id" => $user->id,
                    "order_id" => $order->id,
                    "session_id" => $session->id,
                    "package_id" => $package->id,
                    "total_amount" => $package->price,
                    "payable_amount" => $package->price,
                    "transaction_type" => "order",
                    "status" => "0",
                ]);

                // --- 7. Update User ---
                $user->update([
                    "stripe_id" => $session->id
                ]);

                DB::commit();
                $response = [
                    'checkout_url' => $session->url,
                    'session_id' => $session->id,
                ];
                return $this->successResponse($response);
            } catch (Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage());
            }
        }
    }
}
