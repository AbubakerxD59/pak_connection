<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Feature;
use App\Mail\BookServiceEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function book(Request $request)
    {
        $data = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'fields' => 'required|array|min:1',
        ]);
        if ($data->fails()) {
            return $this->errorResponse($data->errors()->first(), 400);
        } else {
            $user = User::with("orders", "bookServices")->where("id", Auth::id())->first();
            $package = $user->getPackage();
            if ($package) {
                try {
                    // start transaction
                    DB::beginTransaction();
                    $service = Feature::with("fields")->findOrFail($request->service_id);
                    // book service
                    $bookedService = $user->bookServices()->updateOrCreate([
                        "package_id" => $package->id,
                        "service_id" => $service->id,
                    ], [
                        "status" => 3,
                    ]);
                    // book fields
                    $fields = $request->fields;
                    $serviceFields = $service->fields()->get();
                    $bookedService->bookFields()->delete();
                    foreach ($fields as $field) {
                        foreach ($field as $id => $value) {
                            if ($serviceFields->where("id")) {
                                $bookedService->bookFields()->updateOrCreate([
                                    "user_id" => $user->id,
                                    "field_id" => $id,
                                ], [
                                    "value" => $value,
                                    "status" => 1
                                ]);
                            }
                        }
                    }
                    DB::commit();
                    // send mail to user
                    Mail::to($user->email)->send(new BookServiceEmail($user));
                    return $this->successResponse("Service booked Successfully!");
                } catch (Exception $e) {
                    DB::rollBack();
                    return $this->errorResponse($e->getMessage());
                }
            } else {
                return $this->errorResponse("Something went Wrong!");
            }
        }
    }
    public function tracking(Request $request)
    {
        $user = Auth::user();
        $services = $user->bookServices()->with("service")->get();
        foreach ($services as $key => $service) {
            $tracking = [];
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
