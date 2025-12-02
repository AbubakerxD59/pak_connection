<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function __construct()
    {
        // Add permissions if you have them
        $this->middleware('permission:view_settings', ['only' => ['index']]);
        $this->middleware('permission:edit_settings', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add_settings', ['only' => ['create', 'store']]);
        $this->middleware('permission:delete_settings', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key|max:255',
            'value' => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);

        DB::table('settings')->insert([
            'key' => $request->key,
            'value' => $request->value,
        ]);

        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setting = DB::table('settings')->where('id', $id)->first();

        if (!$setting) {
            return redirect()->route('settings.index')->with('error', 'Setting not found.');
        }

        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $setting = DB::table('settings')->where('id', $id)->first();

        if (!$setting) {
            return redirect()->route('settings.index')->with('error', 'Setting not found.');
        }

        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key,' . $id,
            'value' => 'required|string',
        ]);

        DB::table('settings')->where('id', $id)->update([
            'key' => $request->key,
            'value' => $request->value,
        ]);

        // Clear settings cache if enabled
        if (config('setting.cache.enabled')) {
            Cache::forget(config('setting.cache.key'));
        }

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $setting = DB::table('settings')->where('id', $id)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found.',
            ], 404);
        }

        DB::table('settings')->where('id', $id)->delete();

        // Clear settings cache if enabled
        if (config('setting.cache.enabled')) {
            Cache::forget(config('setting.cache.key'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully.',
        ]);
    }

    /**
     * DataTable for settings listing.
     */
    public function dataTable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];

        $iTotalRecords = DB::table('settings')->count();
        $settings = DB::table('settings')->select('*');

        if (!empty($search)) {
            $settings->where(function ($query) use ($search) {
                $query->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            });
        }

        $totalRecordswithFilter = clone $settings;
        $settings->orderBy('id', 'DESC');

        // Set limit offset
        $settings = $settings->offset(intval($data['start']));
        $settings = $settings->limit(intval($data['length']));

        $settings = $settings->get();

        foreach ($settings as $k => $val) {
            $settings[$k]->key = ucwords(str_replace('_', ' ', $val->key)); // Settings table doesn't have timestamps
            $settings[$k]->created = 'N/A'; // Settings table doesn't have timestamps
            $settings[$k]->action = view('admin.settings.action')->with('setting', $val)->render();
        }

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $settings,
        ]);
    }
}
