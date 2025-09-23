<?php

namespace App\Http\Controllers;

use App\Models\BookService;
use App\Models\Feature;
use App\Models\Package;
use App\Models\Price;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use Stripe\Stripe;

class PackageController extends Controller
{
    private $package;
    private $feature;
    private $bookService;
    private $stripe;
    public function __construct(Package $package, Feature $feature, BookService $bookService)
    {
        // permissions
        $this->middleware('permission:view_package', ['only' => ['index']]);
        $this->middleware('permission:add_package', ['only' => ['create']]);
        $this->middleware('permission:edit_package', ['only' => ['edit']]);
        $this->middleware('permission:delete_package', ['only' => ['destroy']]);
        // permissions

        $this->package = $package;
        $this->feature = $feature;
        $this->bookService = $bookService;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.packages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'icon' => 'sometimes|file',
            'price' => 'required|array',
            'personal_assistance' => 'required',
            'status' => 'required'
        ]);
        try {
            // create stripe product
            $stripe_product = $this->stripe->products->create([
                'name' => $request->name,
                'active' => true,
            ]);
            if ($stripe_product->id) {
                $package = $this->package->create([
                    'name' => $request->name,
                    'icon' => $request->has('icon') ? saveImage($request->File('icon')) : '',
                    'personal_assistance' => $request->personal_assistance == "on" ? 1 : 0,
                    'stripe_product_id' => $stripe_product->id,
                    "status" => $request->status
                ]);
                // pricing
                $pricing = $request->price;
                foreach ($pricing as $interval => $price) {
                    if (!empty($price)) {
                        $stripe_amount = $this->stripe->prices->create([
                            'currency' => 'gbp',
                            'active' => true,
                            'product' => $stripe_product->id,
                            'unit_amount_decimal' => $price * 100,
                            'recurring' => [
                                'interval' => "month",
                                'interval_count' => $interval
                            ]
                        ]);
                        if ($stripe_amount->id) {
                            $package->prices()->create([
                                "type" => $interval,
                                "price" => $price,
                                "stripe_id" => $stripe_amount->id,
                            ]);
                        }
                    }
                }
                return redirect(route('packages.index'))->with('success', 'Package added Successfully!');
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $package = $this->package->with("prices", "features")->find($id);
        $prices = Price::where("package_id", $package->id)->get();
        $features = $this->feature->get();
        return view('admin.packages.edit', compact('package', 'prices', 'features'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'icon' => 'sometimes|file',
            'price' => 'required|array',
            'personal_assistance' => 'required',
        ]);
        $package = $this->package->find($id);
        if ($package) {
            // update stripe product
            $this->stripe->products->update($package->stripe_product_id, ['name' => $request->name]);
            // update stripe price
            $data = [
                'name' => $request->name,
                'personal_assistance' => $request->personal_assistance == "on" ? 1 : 0,
                "status" => $request->status
            ];
            if ($request->has('icon')) {
                $data['icon'] = saveImage($request->File('icon'));
            }
            $package->update($data);
            // pricing
            $pricing = $request->price;
            foreach ($pricing as $type => $price) {
                if (!empty($price) && $price > 0) {
                    $packagePrice = Price::where("type", $type)->where("package_id", $id)->first();
                    if ($packagePrice) {
                        if ($packagePrice->price != $price) {
                            $this->stripe->prices->update($packagePrice->stripe_id, [
                                "active" => false
                            ]);
                            $stripe_amount = $this->stripe->prices->create([
                                'currency' => 'gbp',
                                'active' => true,
                                'product' => $package->stripe_product_id,
                                'unit_amount_decimal' => $price * 100,
                                'recurring' => [
                                    'interval' => "month",
                                    'interval_count' => $packagePrice->type
                                ]
                            ]);
                            if ($stripe_amount->id) {
                                $packagePrice->update([
                                    "price" => $price,
                                    "stripe_id" => $stripe_amount->id,
                                ]);
                            }
                        }
                    } else {
                        $stripe_amount = $this->stripe->prices->create([
                            'currency' => 'gbp',
                            'active' => true,
                            'product' => $package->stripe_product_id,
                            'unit_amount_decimal' => $price * 100,
                            'recurring' => [
                                'interval' => "month",
                                'interval_count' => $type
                            ]
                        ]);
                        if ($stripe_amount->id) {
                            Price::create([
                                "package_id" => $id,
                                "type" => $type,
                                "price" => $price,
                                "stripe_id" => $stripe_amount->id,
                            ]);
                        }
                    }
                }
            }
            return redirect(route('packages.index'))->with('success', 'Package update successfully!');
        } else {
            return back()->with('error', 'Unable to update Package!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = $this->package->find($id);
        if ($package) {
            $package->prices()->delete();
            if ($package->delete()) {
                return back()->with('success', 'Package deleted successfully!');
            } else {
                return back()->with('error', value: 'Unable to delete Package!');
            }
        }
    }

    public function datatable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        // $order = end($data['order']);
        // $orderby = $data['columns'][$order['column']]['data'];
        $iTotalRecords = $this->package;
        $packages = new $this->package;

        if (!empty($search)) {
            $packages = $packages->search($search);
        }
        $totalRecordswithFilter = clone $packages;
        $packages->orderBy('id', 'ASC');

        /*Set limit offset */
        $packages = $packages->offset(intval($data['start']));
        $packages = $packages->limit(intval($data['length']));

        $packages = $packages->get();
        $packages->append(["icon_view", "pricing", "personal", "status_view", "action"]);

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords->count(),
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $packages,
        ]);
    }

    public function addFacility(Request $request)
    {
        $data = $request->validate([
            'package_id' => 'required'
        ]);
        $id = $request->package_id;
        $package = $this->package->find($id);
        if ($package) {
            $feature_ids = explode(',', $request->feature_ids);
            $package->features()->sync($feature_ids);
            $response = [
                'status' => true,
                'message' => 'Features added Successfully!'
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Unable to add Features!'
            ];
        }
        return response()->json($response);
    }
}
