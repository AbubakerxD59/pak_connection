<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Feature;
use App\Models\Package;
use App\Mail\WelcomeEmail;
use App\Models\BookService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $user = Auth::user()->load('bookServices');
        $package = $user->getPackage();

        // Check if user has package and expiry time
        if ($user->package_id && $user->pkg_end_time) {
            $currentTime = Carbon::now();
            $pkgEndTime = Carbon::parse($user->pkg_end_time);

            if ($currentTime->greaterThan($pkgEndTime) && $user->package_status != 2) {
                $user->update([
                    "package_status" => 2,
                ]);
            }
        }

        $features = [];

        if ($package) {
            $featuresQuery = $package->features()->orderBy("order", "ASC");

            if ($user->package_status == 2) {

                $serviceIds = $user->bookServices
                    ->where('status', '!=', 10)
                    ->pluck('service_id');

                $features = $featuresQuery
                    ->whereIn('features.id', $serviceIds)
                    ->get();
            } else {

                $features = $featuresQuery->get();
            }
        }

        $isPackageExpired = $user->package_status == 2;




        return view('frontend.member.home', compact('package', 'features', 'isPackageExpired'));
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
                $bookService = $user->bookServices()->where("package_id", $package->id)->where("service_id", $feature->id)->latest()->first();
                $bookFields = $user->bookFields()->where("book_service_id", $bookService->id)->get();
                $view = view("frontend.member.modals.edit_fields", compact("bookFields"))->render();
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
                $bookService = $user->bookServices()->updateOrCreate([
                    "user_id" => $user->id,
                    "package_id" => $package->id,
                    "service_id" => $service->id
                ], [
                    "package_id" => $package->id,
                    "service_id" => $service->id,
                    "status" => 1
                ]);
                $data = $request->fields;
                $fields = $service->fields()->orderBy("order", "ASC")->get();
                foreach ($fields as $field) {
                    if (isset($data[$field->name])) {
                        $user->bookFields()->create([
                            "book_service_id" => $bookService->id,
                            "field_id" => $field->id,
                            "value" => $data[$field->name],
                            "status" => 1
                        ]);
                    }
                }

                Mail::to($user->email)->send(new WelcomeEmail($user));
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

    public function profile()
    {
        $user = Auth::user();
        return view('frontend.member.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $user->update([
            "full_name" => $request->full_name,
            "whatsapp_number" => $request->whatsapp_number,
            "phone_number" => $request->phone_number,
            "city" => $request->city,
            "country" => $request->country,
            "address" => $request->address,
        ]);
        return back()->with("success", "Profile updated Successfully!");
    }
}
