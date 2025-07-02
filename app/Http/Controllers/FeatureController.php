<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Field;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    private $feature;
    private $field;
    public function __construct(Feature $feature, Field $field)
    {
        // permissions
        $this->middleware('permission:view_feature', ['only' => ['index']]);
        $this->middleware('permission:add_feature', ['only' => ['create']]);
        $this->middleware('permission:edit_feature', ['only' => ['edit']]);
        $this->middleware('permission:delete_feature', ['only' => ['destroy']]);
        // permissions

        $this->feature = $feature;
        $this->field = $field;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.features.index');
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
        $data = $request->validate([
            'name' => 'required',
            'icon' => 'required'
        ]);
        $data = [
            "name" => $request->name,
            "icon" => saveImage($request->File("icon"))
        ];
        $feature = $this->feature->create($data);
        if ($feature) {
            $response = [
                'status' => true,
                'message' => 'Feature added Successfully!'
            ];
        } else {
            $response = [
                'status' => false,
                'error' => 'Unable to add Feature!'
            ];
        }
        return response()->json($response);
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
        // $feature = $this->feature->find($id);

        $feature = $this->feature->with('bookServices')->find($id);
        // $book_services = $feature->bookServices;
        $book_services = $feature->bookServices()->with('user')->get();

        $role = auth()->user()?->roles->pluck('name')->first();

        // return $role;


        $fields = $this->field->orderBy('name', 'ASC')->get();

        // return $book_services;
        return view('admin.features.edit', compact('feature', 'fields', 'book_services','role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'icon' => 'required|file',
        ]);
        $feature = $this->feature->find($id);
        if ($feature) {
            $data = [
                'name' => $request->name,
            ];
            if ($request->has('icon')) {
                $data['icon'] = saveImage($request->File('icon'));
            }
            $feature->update($data);
            return redirect(route('features.index'))->with('success', 'Feature update successfully!');
        } else {
            return back()->with('error', 'Unable to update Feature!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $feature = $this->feature->find($id);
        if ($feature) {
            $feature->delete();
            return back()->with('success', 'Feature deleted Successfully!');
        } else {
            return back()->with('error', 'Unable to delete Feature!');
        }
    }

    public function datatable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        // $order = end($data['order']);
        // $orderby = $data['columns'][$order['column']]['data'];
        $iTotalRecords = $this->feature;
        $features = new $this->feature;

        if (!empty($search)) {
            $features = $features->search($search);
        }
        $totalRecordswithFilter = clone $features;
        $features->orderBy('id', 'ASC');

        /*Set limit offset */
        $features = $features->offset(intval($data['start']));
        $features = $features->limit(intval($data['length']));

        $features = $features->orderBy("order", "ASC")->get();
        foreach ($features as $k => $val) {
            $features[$k]['name_link'] = '<a href=' . route('features.edit', $val->id) . '>' . $val->name . '</a>';
            $features[$k]['order_span'] = '<span class="order_row pointer" data-id="' . $val->id . '" data-order="' . ($k + 1) . '"><i class="fa fa-arrows-alt"></i></span>';
            $features[$k]['icon_image'] = '<img src="' . $val->icon . '" width="100px" class="rounded">';
            $features[$k]['action'] = view('admin.features.action')->with('feature', $val)->render();
            $features[$k] = $val;
        }

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords->count(),
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $features,
        ]);
    }

    public function addField(Request $request)
    {
        $data = $request->validate([
            'feature_id' => 'required'
        ]);
        $id = $request->feature_id;
        $feature = $this->feature->find($id);
        if ($feature) {
            $field_ids = explode(',', $request->field_ids);
            $feature->fields()->sync($field_ids);
            $response = [
                'status' => true,
                'message' => 'Fields added Successfully!'
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Unable to add Fields!'
            ];
        }
        return response()->json($response);
    }

    public function saveOrder(Request $request)
    {
        $data = $request->validate([
            "feature_id" => "required",
            "new_order" => "required",
            "total_records" => "required",
            "page" => "required",
        ]);
        $id = $request->feature_id;
        $feature = $this->feature->find($id);
        if ($feature) {
            $page = $request->page;
            $total_records = (int) $request->total_records;
            $old_order = $feature->order;
            $new_order = $page > 1 ? $total_records * ($page - 1) + $request->new_order : $request->new_order;
            $offset =  $total_records * ($page - 1);
            if ($new_order != $old_order) {
                $features = $this->feature;
                if ($new_order < $old_order) {
                    $features = $features->lesserOrder(['new_order' => $new_order, 'old_order' => $old_order]);
                } else if ($new_order > $old_order) {
                    $features = $features->greaterOrder(['new_order' => $new_order, 'old_order' => $old_order]);
                }
                $features = $features->get();
                foreach ($features as $val) {
                    if ($new_order < $old_order) {
                        $order = $val->order + 1;
                    } else {
                        $order = $val->order - 1;
                    }
                    $val->update(["order" => $order]);
                }
            }
            $feature->update(["order" => $new_order]);
            $response = [
                "success" => true,
                "message" => "Row sorting updated Successfully!"
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Something went Wrong!"
            ];
        }
        return response()->json($response);
    }
}
