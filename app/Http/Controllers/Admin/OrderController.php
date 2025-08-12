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
        $order = $this->order->find($id);
        if ($order) {
            return view("admin.orders.edit", compact("order"));
        } else {
            return back()->with("error", "Something went Wrong!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = $this->order->find($id);
        if ($order) {
            $status = $request->status;
            if (isset(Order::$status_array[$status])) {
                $order->update([
                    "status" => $status
                ]);
                return back()->with("success", "Order updated Successfully!");
            } else {
                return back()->with("error", "Something went Wrong!");
            }
        } else {
            return back()->with("error", "Something went Wrong!");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = $this->order->find($id);
        if ($order) {
            $order->delete();
            return back()->with("success", "Order deleted Successfully!");
        } else {
            return back()->with("error", "Something went Wrong!");
        }
    }

    public function datatable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        $iTotalRecords = $this->order;
        $orders = $this->order->with('user', 'package', 'promo')->latest();

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
            $orders[$k]['order_num'] = $val->order_num ?? '-/-';
            $orders[$k]['member_id'] = $val->user ? $val->user->membership_id : '-/-';
            $orders[$k]['customer_name'] = $val->user ? '<a href="' . route("users.edit", $val->user->id) . '">' . $val->user->full_name . ' (' . $val->user->email . ')</a>' : '-';
            $orders[$k]['package_name'] = $val->package ? '<a href="' . route("packages.edit", $val->package->id) . '">' . $val->package->name . '</a>' : '-';
            $orders[$k]['coupon_name'] = $val->promo ? '<a href="' . route("promo-code.edit", $val->promo->id) . '">' . $val->promo->name . '</a>' : '-';
            $orders[$k]['package_amount'] = $val->package ? '£' . $val->package->price : '-';
            $orders[$k]['discount_amount'] = $val->getDiscount();
            $orders[$k]['total_amount'] = $val->getTotal();
            $orders[$k]['date'] = date("Y-m-d", strtotime($val->created_at));
            $orders[$k]['action'] = view('admin.orders.action')->with('order', $val)->render();
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
