<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function tracking(Request $request)
    {
        $user = Auth::user();
        $services = $user->bookServices()->with("service")->get();
        foreach ($services as $key => $service) {
            foreach (getbookedServicestatus() as $innerKey => $status) {
                $tracking_status = "Pending";
                if ($service->status == $innerKey) {
                    $tracking_status = 'In Progress';
                }
                if ($service->status > $innerKey) {
                    $tracking_status = 'Completed';
                }
                $tracking[] = ["name" => $status, "status" => $tracking_status];
            }
            $services[$key]["tracking"] = $tracking;
        }
        return $this->successResponse($services);
    }
}
