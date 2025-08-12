<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\BookService;
use App\Models\Feature;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    private $transaction;
    private $bookService;
    public function __construct(Transaction $transaction, BookService $bookService)
    {
        // permissions
        $this->middleware('permission:view_transactions', ['only' => ['index']]);
        $this->middleware('permission:add_transactions', ['only' => ['create']]);
        $this->middleware('permission:edit_transactions', ['only' => ['edit']]);
        $this->middleware('permission:delete_transactions', ['only' => ['destroy']]);
        // permissions

        $this->transaction = $transaction;
        $this->bookService = $bookService;
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
            $transactions = $this->transaction->with('user', 'package', 'promo')->whereNotNull("order_id")->latest();

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
                $transactions[$k]['member_id'] = $val->user ? $val->user->membership_id : '-/-';
                $transactions[$k]['order_id'] = $val->order ? $val->order->id : '-';
                $transactions[$k]['order_num'] = $val->order->order_num ?? '-/-';
                $transactions[$k]['package_name'] = $val->package ? '<a href="' . route("packages.edit", $val->package->id) . '">' . $val->package->name . '</a>' : '-';
                $transactions[$k]['coupon_name'] = $val->promo ? '<a href="' . route("promo-code.edit", $val->promo->id) . '">' . $val->promo->name . '</a>' : '-';
                $transactions[$k]['discount_amount'] = $val->getDiscount();
                $transactions[$k]['payable'] = '£' . $val->payable_amount;
                $transactions[$k]['total_amount'] = $val->getTotal();
                $transactions[$k]['transaction_type'] = ucfirst($val->transaction_type);
                $transactions[$k]['date'] = date("Y-m-d", strtotime($val->created_at));
                $transactions[$k]['trans_status_view'] = get_status_view($val->status);
                $transactions[$k]['action'] = view('admin.transactions.action')->with('transaction', $val)->render();
                // $transactions[$k]['total_amount'] = str_replace('£', '', $val->total_amount);
                // $transactions[$k]['discount_amount'] = str_replace('£', '', $val->discount_amount);
                $transactions[$k]['payable_amount'] = str_replace('£', '', $val->payable_amount);
                $transactions[$k]['service_name'] = $val->getService();
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


    public function viewOrderPayments()
    {
        $transactions = Transaction::with(['user', 'order', 'promo', 'package'])->latest()->get();
        return view('admin.dashboard.payments_order', compact('transactions'));
    }

    public function viewBookServicePayments()
    {
        $transactions = Transaction::with(['user', 'order', 'promo', 'package'])->latest()->get();
        return view('admin.dashboard.payments_book_service', compact('transactions'));
    }



    public function dashOrderDataTable(Request $request)
    {

        try {
            $data = $request->all();
            $search = @$data['search']['value'];
            $iTotalRecords = $this->transaction;
            $transactions = $this->transaction->with('user', 'package', 'promo');


            if ($request->filter_type == 'order') {
                $transactions = $transactions->where('transaction_type', 'order')->whereNotNull("order_id");
            } else {
                $transactions = $transactions->where('transaction_type', '<>', 'order'); // or '<>'
            }



            if (!empty($search)) {
                $transactions = $transactions->search($search);
            }
            $totalRecordswithFilter = clone $transactions;
            $transactions->orderBy('id', 'ASC');



            // transaction_type == order

            /*Set limit offset */
            $transactions = $transactions->offset(intval($data['start']));
            $transactions = $transactions->limit(intval($data['length']));

            $transactions = $transactions->get();


            foreach ($transactions as $k => $val) {
                $transactions[$k]['customer_name'] = $val->user ? '<a href="' . route("users.edit", $val->user->id) . '">' . $val->user->full_name . ' (' . $val->user->email . ')</a>' : '-';
                $transactions[$k]['member_id'] = $val->user ? $val->user->membership_id : '-/-';
                $transactions[$k]['order_id'] = $val->order ? $val->order->id : '-';
                $transactions[$k]['order_num'] = $val->order->order_num ?? '-/-';
                $transactions[$k]['package_name'] = $val->package ? '<a href="' . route("packages.edit", $val->package->id) . '">' . $val->package->name . '</a>' : '-';
                $transactions[$k]['coupon_name'] = $val->promo ? '<a href="' . route("promo-code.edit", $val->promo->id) . '">' . $val->promo->name . '</a>' : '-';
                $transactions[$k]['package_amount'] = $val->package ? '£' . $val->package->price : '-';
                $transactions[$k]['discount_amount'] = $val->getDiscount();
                $transactions[$k]['total_amount'] = $val->getTotal();
                $transactions[$k]['date'] = date("Y-m-d", strtotime($val->created_at));
                $transactions[$k]['trans_status_view'] = get_status_view($val->status);
                $transactions[$k]['action'] = view('admin.transactions.action')->with('transaction', $val)->render();
                $transactions[$k] = $val;
            }

            return response()->json([
                'draw' => intval($data['draw']),
                'iTotalRecords' => $iTotalRecords->count(),
                'iTotalRecords' => $iTotalRecords,
                // $iTotalRecords = $this->transaction->whereNotNull("order_id")->count();

                'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
                'aaData' => $transactions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function viewOrderEarnings()
    {
        // return view("admin.transactions.index");
        $transactions = Transaction::with(['user', 'order', 'promo', 'package'])->latest()->get();
        // return $transactions;
        return view('admin.dashboard.earnings_order', compact('transactions'));
    }

    public function viewBookServiceEarnings()
    {
        // return view("admin.transactions.index");
        $transactions = Transaction::with(['user', 'order', 'promo', 'package'])->latest()->get();
        // return $transactions;
        return view('admin.dashboard.earnings_book_service', compact('transactions'));
    }



    public function dashServiceDataTable(Request $request)
    {

        try {
            $data = $request->all();
            $search = @$data['search']['value'];
            $iTotalRecords = $this->transaction;
            $transactions = $this->transaction->with('user', 'package', 'promo');


            if ($request->filter_type == 'order') {
                $transactions = $transactions->where('transaction_type', 'order')->whereNotNull("order_id");
            } else {
                $transactions = $transactions->where('transaction_type', '<>', 'order'); // or '<>'
            }



            if (!empty($search)) {
                $transactions = $transactions->search($search);
            }
            $totalRecordswithFilter = clone $transactions;
            $transactions->paid()->orderBy('id', 'ASC');



            // transaction_type == order

            /*Set limit offset */
            $transactions = $transactions->offset(intval($data['start']));
            $transactions = $transactions->limit(intval($data['length']));

            $transactions = $transactions->get();


            foreach ($transactions as $k => $val) {
                $transactions[$k]['customer_name'] = $val->user ? '<a href="' . route("users.edit", $val->user->id) . '">' . $val->user->full_name . ' (' . $val->user->email . ')</a>' : '-';
                $transactions[$k]['member_id'] = $val->user ? $val->user->membership_id : '-/-';
                $transactions[$k]['order_id'] = $val->order ? $val->order->id : '-';
                $transactions[$k]['order_num'] = $val->order->order_num ?? '-/-';
                $transactions[$k]['package_name'] = $val->package ? '<a href="' . route("packages.edit", $val->package->id) . '">' . $val->package->name . '</a>' : '-';
                $transactions[$k]['coupon_name'] = $val->promo ? '<a href="' . route("promo-code.edit", $val->promo->id) . '">' . $val->promo->name . '</a>' : '-';
                $transactions[$k]['package_amount'] = $val->package ? '£' . $val->package->price : '-';
                $transactions[$k]['discount_amount'] = $val->getDiscount();
                $transactions[$k]['total_amount'] = $val->getTotal();
                $transactions[$k]['date'] = date("Y-m-d", strtotime($val->created_at));
                $transactions[$k]['trans_status_view'] = get_status_view($val->status);
                $transactions[$k]['action'] = view('admin.transactions.action')->with('transaction', $val)->render();
                $transactions[$k] = $val;
            }

            return response()->json([
                'draw' => intval($data['draw']),
                'iTotalRecords' => $iTotalRecords->count(),
                'iTotalRecords' => $iTotalRecords,
                // $iTotalRecords = $this->transaction->whereNotNull("order_id")->count();

                'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
                'aaData' => $transactions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
