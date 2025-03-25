<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $package;
    private $user;
    public function __construct(Package $package, User $user)
    {
        $this->package = $package;
        $this->user = $user;
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
            'email' => 'required|unique:users',
            'whatsapp_number' => 'required',
            'city' => 'required',
            'country' => 'required',
        ]);
        $package = $this->package->find($request->package_id);
        if ($package) {
            $this->user->create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'whatsapp_number' => $request->whatsapp_number,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'country' => $request->country,
                'address' => $request->address,
            ]);
            $user = $this->user->where('email', $request->email)->first();
            return auth()->user()
                ->newSubscription($package->stripe_product_id, $package->stripe_price_id)
                ->checkout([
                    'success_url' => route('frontend.checkout_success'),
                    'cancel_url' => route('frontend.home'),
                ]);
        } else {
            return redirect()->back()->with('error', 'Package not Found!');
        }
    }

    public function success()
    {
        $user = Auth::user();
        return view('frontend.success', compact('user'));
    }
}
