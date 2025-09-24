<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Package;
use App\Models\Price;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function get(Package $package)
    {
        $packages = $package->with("prices", "features")->active()->get();
        return $this->successResponse($packages);
    }

    public function getServices(Request $request, Package $package)
    {
        $data = $request->only("id");
        if ($data) {
            $services = $package->with("features")->where("id", $data["id"])->active()->first();
            return $this->successResponse($services);
        } else {
            return $this->errorResponse("Not Found", 404);
        }
    }

    public function getFields(Request $request, Feature $feature)
    {
        $data = $request->only("id");
        if ($data) {
            $fields = $feature->with("fields")->where("id", $data["id"])->first();
            return $this->successResponse($fields);
        } else {
            return $this->errorResponse("Not Found", 404);
        }
    }
}
