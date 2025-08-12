<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request, User $user)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms_and_conditions' => 'required'
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors()->first(), 400);
        } else {
            $user = $user::create([
                'full_name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if ($user) {
                $user = User::where("email", $request->email)->first();
                return $this->successResponse($user, "Registration Completed!");
            } else {
                return $this->errorResponse('Something went Wrong!', 403);
            }
        }
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $data = Validator::make($request->all(), [
            'email_phone' => 'required|string',
            'password' => 'required|string',
            'remember' => 'required'
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors()->first(), 400);
        } else {
            $user = User::where('email', $request->email_phone)->orWhere("phone_number", $request->email_phone)->first();
            if (!$user || !Auth::attempt(["email" => $user->email, "password" => $request->password], $request->remember)) {
                return $this->errorResponse('Invalid Credentials!', 403);
            }
            $token = $user->createToken('auth-token')->plainTextToken;
            $user->setRememberToken($token);
            $response = ["user" => $user, "token" => $token];
            return $this->successResponse($response, "Login Successful!");
        }
    }

    /**
     * Logout user (Revoke the token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse([], "Logout Successful!");
    }
}
