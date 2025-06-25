<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class AuthController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function showLogin()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
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
            if (Auth::attempt($attempt, $request->remember_me)) {
                $response = [
                    'success' => true,
                    'message' => 'Login successful!'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid Credentials!',
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid Credentials!',
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
