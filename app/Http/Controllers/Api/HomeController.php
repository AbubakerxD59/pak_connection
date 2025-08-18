<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
                "current_package" => $user->getPackage(),
                "expiry_date" => !empty($user->pkg_end_time) ? date('Y-m-d', strtotime($user->pkg_end_time)) : ''
            ],
            "last_booking" => $last_booking,
            "upcoming_booking" => $upcoming_booking,
        ];
        return $this->successResponse($response);
    }

    public function profile()
    {
        $user = Auth::user();
        return $this->successResponse($user);
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();
        $data = $request->all();
        if(count($data) > 0){
            $user->update($data);
        }
        return $this->successResponse("Profile updated Successfully!", $user);
    }
}
