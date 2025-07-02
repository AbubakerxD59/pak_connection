<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route("frontend.member.home");
        } else {
            return view('frontend.auth.login');
        }
    }

    public function login(Request $request)
    {
        // return $request;
        $validated = $request->validate([
            'membership_id' => 'required',
            'password' => 'required',
            'remember_me' => 'sometimes'
        ]);
        $user = $this->user->membership($request->membership_id)->first();
        if ($user) {
            $attempt = [
                "password" => $request->password
            ];
            if ($user->email == $request->membership_id) {
                $attempt["email"] = $request->membership_id;
            } else {
                $attempt["membership_id"] = $request->membership_id;
            }

            // return [$attempt, $request->remember_me];


            if (Auth::attempt($attempt, $request->remember_me)) {
                $response = [
                    'success' => true,
                    'message' => 'Login successful!'
                ];
            } else {
                // return $user;
                $response = [
                    'success' => false,
                    'message' => 'Invalid Credentials! 1',
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid Credentials! 2',
            ];
        }

        if ($response['success']) {
            return redirect()->route('frontend.member.home')->with('success', $response['message']);
        } else {
            return redirect()->intended(RouteServiceProvider::MEMBER_INV_CRED)->with('error', $response['message']);
        }
    }

    public function login_02(Request $request)
    {
        $validated = $request->validate([
            'membership_id' => 'required',
            'password' => 'required',
            'remember_me' => 'sometimes'
        ]);

        // Try finding the user by email or membership_id using a custom scope
        $user = $this->user->membership($request->membership_id)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->remember_me);

            return redirect()->route('frontend.member.home')->with('success', 'Login successful!');
        }

        return redirect()->intended(RouteServiceProvider::MEMBER_INV_CRED)->with('error', 'Invalid Credentials!');
    }

    public function login_03(Request $request)
    {
        // return $request;
        $validated = $request->validate([
            'membership_id' => 'required',
            'password' => 'required',
            'remember_me' => 'sometimes'
        ]);
        $user = $this->user->membership($request->membership_id)->first();
        if ($user) {
            $attempt = [
                "password" => $request->password
            ];
            if ($user->email == $request->membership_id) {
                $attempt["email"] = $request->membership_id;
            } else {
                $attempt["membership_id"] = $request->membership_id;
            }

            // return [$attempt, $request->remember_me];


            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->remember_me);
                $response = [
                    'success' => true,
                    'message' => 'Login successful!'
                ];
            } else {
                // return $user;
                $response = [
                    'success' => false,
                    'message' => 'Invalid Credentials! 1',
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid Credentials! 2',
            ];
        }

        if ($response['success']) {
            return redirect()->route('frontend.member.home')->with('success', $response['message']);
        } else {
            return redirect()->intended(RouteServiceProvider::MEMBER_INV_CRED)->with('error', $response['message']);
        }
    }


    public function logout()
    {
        Auth::logout();
        session()->flash('success', 'auth.logout_success');
        return redirect()->route('frontend.login');
    }
}
