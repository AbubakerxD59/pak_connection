<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\ResetLink;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// use Mail;
use Illuminate\Support\Facades\Mail;


class PasswordController extends Controller
{

    public function showLinkRequestForm()
    {
        return view('frontend.password.forget-email');
        // return view('frontend.password.update-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // return $request;
        $request->validate(['email' => 'required|email']);

        // Find the user by the provided email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            try {
                // Generate the reset token
                $small_alphabets = implode('', array_rand(array_flip(range('a', 'z')), 5));
                $token = $small_alphabets . '-' . time() . '-' . rand(10000, 99999);



                // Create or update the reset link for the user
                ResetLink::updateOrCreate(
                    ['user_id' => $user->id],
                    ['token' => $token],
                );

                // Create the reset link URL
                $user->token = route('password.visit_link', ['token' => $token]);

                // return $user;

                // Send the reset password email
                Mail::to($user->email)->send(new ForgetPasswordMail($user));

                return redirect()->back()->with('success', 'A reset link has been sent to your email address.');
            } catch (\Exception $e) {
                // dd($e);
                // dd($e->getMessage());

                // Catch any errors and handle them
                return redirect()->back()->with('error', 'An error occurred while processing your request. Please try again.');
            }
        } else {
            return redirect()->back()->with('error', 'No record found with the provided email address.');
        }

        // Mail::to($user->email)->send(new ForgetPasswordMail($user));


    }

    public function visitPasswordLink(Request $request)
    {
        // Retrieve the 'token' from the query parameters
        $token = $request->query('token'); // or $request->get('token');

        //  return $token;

        $record = ResetLink::where('token', $token)->first();
        //  return $record;

        if (!$record) {
            $record = '404';
            return view('frontend.password.update-password', compact('record'));
        }

        // You can now use this token, e.g., verify it, show a form, etc.
        return view('frontend.password.update-password', compact('record'));
    }

    public function resetPassword(Request $request)
    {
        try {
            // return $request;
            $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->first();
            // return $user;
            if (!$user) {
                return redirect()->back()->with('error', 'No record found with the provided email address.');
            }

            // Validate request
            $request->validate([
                'password' => 'required|min:8|confirmed',
            ]);

            $data = []; // Initialize the $data array

            // Only update the password if it is present in the request
            if ($request->filled('password')) {
                // $data['password'] = bcrypt($request->password);
                // $data['password'] = Hash::make($request->password);
                $data['password'] = $request->password;
            }


            // return $data;

            $user->update($data);

            ResetLink::where('user_id', $user->id)->delete();

            return redirect()->route('frontend.showLogin')->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());

            // return redirect()->back()->with('error', 'An error occurred while processing your request. Please try again.');
            return redirect()->back()->with('error', $e->getMessage());
        }
    }






    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
