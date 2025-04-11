<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $order;
    public function __construct(Order $order)
    {
        // permissions
        $this->middleware('permission:view_orders', ['only' => ['index']]);
        $this->middleware('permission:add_orders', ['only' => ['create']]);
        $this->middleware('permission:edit_orders', ['only' => ['edit']]);
        $this->middleware('permission:delete_orders', ['only' => ['destroy']]);
        // permissions

        $this->order = $order;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.orders.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function datatable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        $iTotalRecords = $this->order;
        $orders = $this->order->with('user', 'package', 'promo');

        if (!empty($search)) {
            $orders = $orders->search($search);
        }
        $totalRecordswithFilter = clone $orders;
        $orders->orderBy('id', 'ASC');

        /*Set limit offset */
        $orders = $orders->offset(intval($data['start']));
        $orders = $orders->limit(intval($data['length']));

        $orders = $orders->get();
        foreach ($orders as $k => $val) {
            // dd($val);
            $orders[$k]['customer_name'] = $val->user ? '<a href="' . route("users.edit", $val->user->id) . '">' . $val->user->full_name . ' (' . $val->user->email . ')</a>' : '-';
            $orders[$k]['package_name'] = $val->package ? '<a href="' . route("packages.edit", $val->package->id) . '">' . $val->package->name . '</a>' : '-';
            $orders[$k]['coupon_name'] = $val->promo ? '<a href="' . route("promo-code.edit", $val->promo->id) . '">' . $val->promo->name . '</a>' : '-';
            $orders[$k]['package_amount'] = $val->package ? '£' . $val->package->price : '-';
            $orders[$k]['discount_amount'] = $val->getDiscount();
            $orders[$k]['total_amount'] = $val->getTotal();
            $orders[$k]['date'] = date("Y-m-d", strtotime($val->created_at));
            $orders[$k]['status_view'] = $val->status_view;
            $orders[$k]['action'] = view('admin.promo-code.action')->with('code', $val)->render();
            $orders[$k] = $val;
        }

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords->count(),
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $orders,
        ]);
    }
}
