<?php

namespace App\Http\Controllers\Frontend;

use App\Models\BookService;
use App\Models\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    private $feature;
    private $package;
    private $bookService;
    public function __construct(Feature $feature, Package $package, BookService $bookService)
    {
        $this->feature = $feature;
        $this->package = $package;
        $this->bookService = $bookService;
    }
    public function home()
    {
        $user = Auth::user();
        $package = $user->getPackage();
        if ($package) {
            $features = $package->features()->orderBy("order", "ASC")->get();
        } else {
            $features = [];
        }
        return view('frontend.member.home', compact('package', 'features'));
    }

    public function getFields(Request $request)
    {
        $data = $request->validate([
            "feature_id" => "required"
        ]);
        $user = Auth::user();
        $id = $request->feature_id;
        $feature = $this->feature->find($id);
        if ($feature) {
            $package = $user->getPackage();
            if ($feature->book) {
                $fields = $feature->bookServices()->search(["user_id" => $user->id, "package_id" => $package->id, "service_id" => $feature->id])->get();
                $view = view("frontend.member.modals.edit_fields", compact("fields"))->render();
            } else {
                $fields = $feature->fields()->orderBy("order", "ASC")->get();
                $view = view("frontend.member.modals.fields", compact("fields"))->render();
            }
            $response = [
                "success" => true,
                "book" => $feature->book,
                "data" => $view
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Something went wrong!"
            ];
        }
        return $response;
    }

    public function bookService(Request $request)
    {
        $user = Auth::user();
        $package = $this->package->find($request->package_id);
        if ($package) {
            $service = $this->feature->find($request->service_id);
            if ($service) {
                $data = $request->fields;
                $fields = $service->fields()->orderBy("order", "ASC")->get();
                foreach ($fields as $field) {
                    if (isset($data[$field->name])) {
                        $user->bookServices()->create([
                            "package_id" => $package->id,
                            "service_id" => $service->id,
                            "field_id" => $field->id,
                            "value" => $data[$field->name],
                            "status" => 1
                        ]);
                    }
                }
                $response = [
                    "success" => true,
                    "message" => "Service initiated Successfully!"
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "Something went Wrong!"
                ];
            }
        } else {
            $response = [
                "success" => false,
                "message" => "Something went Wrong!"
            ];
        }
        return response()->json($response);
    }
}
