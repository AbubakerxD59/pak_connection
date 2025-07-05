<?php

namespace App\Http\Controllers\Admin;

use App\Events\BookedServiceStatusUpdated;
use App\Models\Role;
use App\Models\User;
use App\Models\BookService;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;
    private $role;
    private $bookService;
    private $promo_code;
    private $stripe;
    private $transaction;
    public function __construct(User $user, Role $role, BookService $bookService, PromoCode $promo_code, Transaction $transaction)
    {
        // permissions
        $this->middleware('permission:view_user', ['only' => ['index']]);
        $this->middleware('permission:add_user', ['only' => ['create']]);
        $this->middleware('permission:edit_user', ['only' => ['edit', 'showInfo']]);
        $this->middleware('permission:delete_user', ['only' => ['destroy']]);
        $this->middleware('permission:edit_booked_services', ['only' => ['editBookedService']]);
        // permissions

        $this->user = $user;
        $this->role = $role;
        $this->bookService = $bookService;
        $this->promo_code = $promo_code;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->role->get();
        return view('admin.users.add', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:4|confirmed',
            'role' => 'required',
        ]);
        $user = $this->user->create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if (!empty($user)) {
            $role = $this->role->find($request->role);
            if (!empty($role)) {
                $user->assignRole($role->name);
            }
            $response = [
                'success' => true,
                'message' => __('users.add_user_success'),
            ];
        } else {
            $response = [
                'success' => false,
                'message' => __('users.add_user_error'),
            ];
        }

        if ($response['success']) {
            return redirect(route('users.index'))->with('success', $response['message']);
        } else {
            return back()->with('error', $response['message']);
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
        $user = $this->user->find($id);
        // $user = $this->user->with([
        //     'bookServices.service',
        //     'bookServices.transactions'
        // ])->find($id);


        $roles = $this->role->get();
        $promo_codes = $this->promo_code->get();

        // return $user;

        return view('admin.users.edit', compact('roles', 'user', 'promo_codes'));
    }

    public function createInvoice(Request $request)
    {

        // return $request;

        try {
            $data = $request->validate([
                'book_service_id' => 'required|exists:book_services,id',
                'amount'            => 'required|numeric|min:0',
                'final_price'       => 'required|numeric|min:0',
                'promo_code_id'         => 'nullable|exists:promo_codes,id',
            ]);


            // Load BookService with its Service
            $bookedService = $this->bookService->with('service')->find($request->book_service_id);
            $serviceName = $bookedService->service->name ?? '-';



            // Load Coupon name if provided
            $promoCode = $this->promo_code->find($request->promo_code_id);
            $promoName = $promoCode->name ?? '-';

            // dd($promoCode);


            // Set Stripe secret key
            // Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // Create a product (if not already created)
            $product = $this->stripe->products->create([
                'name' => $serviceName,
            ]);

            // Create a price
            $price = $this->stripe->prices->create([
                'unit_amount' => $request->final_price * 100, // Amount in cents (i.e. $50.00)
                'currency' => 'gbp',
                'product' => $product->id,
            ]);

            // Create a payment link
            // $paymentLink = PaymentLink::create([
            $paymentLink = $this->stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ],
                ],
            ]);

            //  Save link to invoice url column

            $bookedService->invoice_url = $paymentLink->url;
            $bookedService->invoice_status = 1;
            $bookedService->status = 5;

            // event(new BookedServiceStatusUpdated($bookedService));

            // $bookedService->save();


            // ============


            $bookedService->save();


            $bookedService->total_amount = $request->amount;
            $bookedService->discount_amount = $request->amount - $request->final_price;
            $bookedService->payable_amount = $request->final_price;
            $bookedService->service_name = $bookedService->getService();


            event(new BookedServiceStatusUpdated($bookedService));

            // $bookedService->save();


            // ============


            $this->transaction->create([
                "user_id" => $bookedService->user_id,
                // "order_id" => $order->id,
                "book_service_id" => $request->book_service_id,
                // "session_id" => $session->id,
                // "package_id" => $package->id,
                "promo_id" => $request->promo_code_id ? $request->promo_code_id : "",


                "total_amount" => $request->amount,
                "discount_amount" => $request->amount -  $request->final_price,
                "payable_amount" => $request->final_price,


                "invoice_url" => $paymentLink->url,
                "status" => "0",
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully.',
                'url'     => $paymentLink->url,
            ]);
        } catch (\Exception $e) {

            dd($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function depositPayment(Request $request)
    {

        try {

            //  return response()->json([
            //     'success' => true,
            //     'message' => 'return back temporary.',
            //     'url'     => '$paymentLink->url',
            // ]);

            $deposit_amount = 100; // fixed in pound


            $data = $request->validate([
                'book_service_id' => 'required|exists:book_services,id',
            ]);


            // Load BookService with its Service
            $bookedService = $this->bookService->with('service')->find($request->book_service_id);

            // Create a product (if not already created)
            $product = $this->stripe->products->create([
                'name' => 'Deposit Payment',
            ]);

            // Create a price
            $price = $this->stripe->prices->create([
                'unit_amount' => $deposit_amount * 100, // Amount in cents (i.e. $50.00)
                'currency' => 'gbp',
                'product' => $product->id,
            ]);

            // Create a payment link
            // $paymentLink = PaymentLink::create([
            $paymentLink = $this->stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ],
                ],
            ]);

            $bookedService->deposit_url = $paymentLink->url;
            $bookedService->deposit_status = 1;
            $bookedService->status = 2;

            $bookedService->save();


            $this->transaction->create([
                "user_id" => $bookedService->user_id,
                // "order_id" => $order->id,
                "book_service_id" => $request->book_service_id,
                // "session_id" => $session->id,
                // "package_id" => $package->id,
                "promo_id" => "",

                "total_amount" => $deposit_amount,
                "discount_amount" => 0,
                "payable_amount" => $deposit_amount,

                "invoice_url" => $paymentLink->url,
                "status" => "0",
            ]);

            $bookedService->total_amount = $deposit_amount;
            $bookedService->discount_amount = 0;
            $bookedService->payable_amount = $deposit_amount;
            $bookedService->service_name = $bookedService->getService();


            event(new BookedServiceStatusUpdated($bookedService));


            return response()->json([
                'success' => true,
                'message' => 'Deposit requested successfully.',
                'url'     => $paymentLink->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function crmStatusTransaction(Request $request)
    {

        try {

            //  return response()->json([
            //     'success' => true,
            //     'message' => 'return back temporary.',
            //     'url'     => '$paymentLink->url',
            // ]);


            $data = $request->validate([
                'book_service_id' => 'required|exists:book_services,id',
            ]);


            // Load BookService with its Service
            $bookedService = $this->bookService->with('service')->find($request->book_service_id);


            $bookedService->status = $request->status;

            event(new BookedServiceStatusUpdated($bookedService));

            $bookedService->save();


            // NOT IN USE - START
            // NOT IN USE 
            // NOT IN USE 

            // // Create a product (if not already created)
            // $product = $this->stripe->products->create([
            //     'name' => $request->status_text,
            // ]);

            // // Create a price
            // $price = $this->stripe->prices->create([
            //     'unit_amount' => 100 * 100, // Amount in cents (i.e. $50.00)
            //     'currency' => 'gbp',
            //     'product' => $product->id,
            // ]);

            // // Create a payment link
            // // $paymentLink = PaymentLink::create([
            // $paymentLink = $this->stripe->paymentLinks->create([
            //     'line_items' => [
            //         [
            //             'price' => $price->id,
            //             'quantity' => 1,
            //         ],
            //     ],
            // ]);

            // $bookedService->deposit_url = $paymentLink->url;
            // // $bookedService->deposit_status = 1;
            // $bookedService->status = $request->status;

            // $this->transaction->create([
            //     "user_id" => $bookedService->user_id,
            //     // "order_id" => $order->id,
            //     "book_service_id" => $request->book_service_id,
            //     // "session_id" => $session->id,
            //     // "package_id" => $package->id,
            //     "promo_id" => "",
            //     "total_amount" => 100 * 100,
            //     "invoice_url" => $paymentLink->url,
            //     "status" => "0",
            // ]);

            // event(new BookedServiceStatusUpdated($bookedService));

            // $bookedService->save();


            // NOT IN USE 
            // NOT IN USE 
            // NOT IN USE - END

            return response()->json([
                'success' => true,
                'message' => $request->status_text . ' action done successfully.',
                // 'url'     => $paymentLink->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|max:250|',
            'password' => 'sometimes',
            'role' => 'required',
            'active' => 'required'
        ]);
        $user = $this->user->find($id);
        if (!empty($user)) {
            $data = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'status' => $request->active ? 1 : 0
            ];
            if (!empty($request->password)) {
                $data['password'] = $request->password;
            }
            $user->update($data);
            $role = $this->role->find($request->role);
            if (!empty($role)) {
                $user->syncRoles($role->name);
            }
            $response = [
                'success' => true,
                'message' => 'Customer updated successfully!'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Unable to update Customer!'
            ];
        }
        if ($response['success']) {
            return redirect(route('users.index'))->with('success', $response['message']);
        } else {
            return back()->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->user->find($id);
        if ($user) {
            if ($user->delete()) {
                return back()->with('success', 'Customer deleted successfully!');
            } else {
                return back()->with('error', 'Unable to delete Customer!');
            }
        }
    }

    public function dataTable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        // $order = end($data['order']);
        // $orderby = $data['columns'][$order['column']]['data'];
        $iTotalRecords = new User;
        $users = new User;

        if (!empty($search)) {
            $users = $users->search($search);
        }
        $totalRecordswithFilter = clone $users;
        // $users->orderBy($orderby, $order['dir']);
        $users->orderBy('id', 'ASC');

        /*Set limit offset */
        $users = $users->offset(intval($data['start']));
        $users = $users->limit(intval($data['length']));

        $users = $users->get();
        foreach ($users as $k => $val) {
            $role = (count($val->roles->pluck('name')) > 0) ? $val->roles->pluck('name')[0] : '-';
            $rand = rand(1, 5);
            $path = $rand > 0 ? 'avatar' . $rand : 'avatar';
            $path .= '.png';
            $profile_pic = !empty($val->profile_pic) ? $val->profile_pic : url(getImage('img', $path, 'assets'));
            $email = !empty($val->email) ? $val->email : '-';
            $email_username = '<div><span>' . $email . '</span><br><span>' . $val->username . '</span></div>';
            $users[$k]['profile'] = "<img src='" . asset($profile_pic) . "' alt='Logo' width='50px'>";
            $users[$k]['name_link'] = !empty($val->full_name) ? '<a href=' . route('users.edit', $val->id) . '>' . $val->full_name . '</a>' : '-';
            $users[$k]['email_username'] = $email_username;
            $users[$k]['created'] = date('Y-m-d', strtotime($val->created_at));
            $users[$k]['role'] = $role;
            $users[$k]['status_span'] = $val->status ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>";
            $users[$k]['action'] = view('admin.users.action')->with('user', $val)->with('role', $role)->render();
            $users[$k] = $val;
        }

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords->count(),
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $users,
        ]);
    }

    public function editBookedService($id)
    {
        $bookedService = $this->bookService->with(['service', 'transactions'])->find($id);

        // return $bookedService;


        // $user = $this->user->with([
        //     'bookServices.service',
        //     'bookServices.transactions'
        // ])->find($id);

        if ($bookedService) {
            return view("admin.users.edit_booked_service", compact("bookedService"));
        } else {
            return back();
        }
    }

    public function updateBookedService(Request $request, $id)
    {
        $status = $request->status;
        // $bookedService = $this->bookService->find($id);
        $bookedService = $this->bookService->with('user')->find($id);

        // return $bookedService;
        if ($bookedService) {
            $user_id = $bookedService->user_id;
            $fields = $request->fields;
            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    $bookField = $bookedService->bookFields()->where("field_id", $key)->first();
                    if ($bookField) {
                        $bookField->update(["value" => $value]);
                    }
                }
            }
            $bookedService->update(["status" => $status]);

            event(new BookedServiceStatusUpdated($bookedService));

            return redirect(route("users.edit", $user_id))->with("success", "Service updated Successfully!");
        } else {
            return back()->with("error", "Something went Wrong!");
        }
    }

    public function deleteBookedService($id)
    {
        $bookedService = $this->bookService->find($id);
        if ($bookedService) {
            $bookedService->bookFields()->delete();
            $bookedService->delete();
            return back()->with("success", "Service deleted Successfully!");
        } else {
            return back()->with("error", "Something went Wrong!");
        }
    }
}
