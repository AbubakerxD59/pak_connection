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
        
        $roles = $this->role->get();
        $promo_codes = $this->promo_code->get();

        return view('admin.users.edit', compact('roles', 'user', 'promo_codes'));
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
            $user->order()->delete();
            $user->bookServices()->delete();
            $user->bookFields()->delete();
            $user->transactions()->delete();
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
}
