<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PhoneVerification;
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
                'password' => $request->password,
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
            if ($user->verified()) {
                $token = $user->createToken('auth-token')->plainTextToken;
                $user->setRememberToken($token);
            } else {
                $token = null;
            }
            $response = ["user" => $user, "token" => $token];
            return $this->successResponse($response, "Login Successful!");
        }
    }

    /**
     * Generate and send a 6-digit numeric verification code.
     */
    public function generateCode(Request $request)
    {
        $data = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $request->user_id,
        ]);

        if ($data->fails()) {
            return $this->errorResponse($data->errors()->first(), 400);
        } else {
            $user_id = $request->user_id;
            $user = User::find($user_id);
            if ($user->verified()) {
                return $this->errorResponse("Phone Number already verified!", 400);
            }
            $user->update([
                "phone_number" => $request->phone_number
            ]);

            // $code = random_int(100000, 999999);
            $code = "123456";
            $expiresAt = Carbon::now()->addMinutes(2);
            try {
                PhoneVerification::updateOrCreate(
                    ['user_id' => $user_id],
                    [
                        'code' => $code,
                        'expires_at' => $expiresAt,
                    ]
                );
                // SmsService::send($phoneNumber, "Your verification code is: " . $code);
                return $this->successResponse([], "Verification code generated and sent!");
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Verify the 6-digit numeric code against the phone number.
     */
    public function verifyCode(Request $request)
    {
        $data = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:20',
            'code' => 'required|string|size:6',
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors()->first(), 400);
        }
        $phoneNumber = $request->phone_number;
        $code = $request->code;
        $user = User::where("phone_number", $phoneNumber)->first();
        if ($user) {
            // 2. Find the verification record for the given phone number
            $verification = PhoneVerification::where('user_id', $user->id)->first();
            // 3. Check if a record exists and if the code matches and is not expired
            if (!$verification || $verification->code !== $code || $verification->expires_at->isPast()) {
                return $this->errorResponse("Invalid or expired verification code!", 400);
            }
            // 4. If verification is successful, delete the code (optional, but recommended for security)
            $user->update(["phone_verification" => 1]);
            $verification->delete();
            return $this->successResponse([], "Phone number verified successfully!");
        } else {
            return $this->errorResponse("Not Found!", 404);
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
