<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    private $transaction;
    public function __construct(Transaction $transaction)
    {
        // permissions
        $this->middleware('permission:view_transactions', ['only' => ['index']]);
        $this->middleware('permission:add_transactions', ['only' => ['create']]);
        $this->middleware('permission:edit_transactions', ['only' => ['edit']]);
        $this->middleware('permission:delete_transactions', ['only' => ['destroy']]);
        // permissions

        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view("admin.transactions.index");
        $transactions = Transaction::with(['user', 'order', 'promo', 'package'])->latest()->get();
        // return $transactions;
        return view('admin.transactions.index', compact('transactions'));
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
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function datatable(Request $request)
    {
        try {
            $data = $request->all();
            $search = @$data['search']['value'];
            $iTotalRecords = $this->transaction;
            $transactions = $this->transaction->with('user', 'package', 'promo')->whereNotNull("order_id");

            if (!empty($search)) {
                $transactions = $transactions->search($search);
            }
            $totalRecordswithFilter = clone $transactions;
            $transactions->orderBy('id', 'ASC');

            /*Set limit offset */
            $transactions = $transactions->offset(intval($data['start']));
            $transactions = $transactions->limit(intval($data['length']));

            $transactions = $transactions->get();
            foreach ($transactions as $k => $val) {
                $transactions[$k]['customer_name'] = $val->user ? '<a href="' . route("users.edit", $val->user->id) . '">' . $val->user->full_name . ' (' . $val->user->email . ')</a>' : '-';
                $transactions[$k]['order_id'] = $val->order ? $val->order->id : '-';
                $transactions[$k]['package_name'] = $val->package ? '<a href="' . route("packages.edit", $val->package->id) . '">' . $val->package->name . '</a>' : '-';
                $transactions[$k]['coupon_name'] = $val->promo ? '<a href="' . route("promo-code.edit", $val->promo->id) . '">' . $val->promo->name . '</a>' : '-';
                $transactions[$k]['package_amount'] = $val->package ? '£' . $val->package->price : '-';
                $transactions[$k]['discount_amount'] = $val->getDiscount();
                $transactions[$k]['total_amount'] = $val->getTotal();
                $transactions[$k]['date'] = date("Y-m-d", strtotime($val->created_at));
                $transactions[$k]['status_view'] = $val->status_view;
                $transactions[$k]['action'] = view('admin.transactions.action')->with('transaction', $val)->render();
                $transactions[$k] = $val;
            }

            return response()->json([
                'draw' => intval($data['draw']),
                'iTotalRecords' => $iTotalRecords->count(),
                'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
                'aaData' => $transactions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
