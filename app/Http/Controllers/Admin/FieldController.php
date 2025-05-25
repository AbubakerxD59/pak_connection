<?php

namespace App\Http\Controllers\Admin;

use App\Imports\FieldImport;
use App\Models\Field;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class FieldController extends Controller
{
    private $field;
    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    public function index()
    {
        return view('admin.fields.index');
    }

    public function datatable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        // $order = end($data['order']);
        // $orderby = $data['columns'][$order['column']]['data'];
        $iTotalRecords = $this->field;
        $fields = new $this->field;

        if (!empty($search)) {
            $fields = $fields->search($search);
        }
        $totalRecordswithFilter = clone $fields;
        $fields->orderBy('id', 'ASC');

        /*Set limit offset */
        $fields = $fields->offset(intval($data['start']));
        $fields = $fields->limit(intval($data['length']));

        $fields = $fields->orderBy("order", "ASC")->get();
        foreach ($fields as $k => $val) {
            $val->update(["order" => ($k+1)]);
            $fields[$k]['order_span'] = '<span class="order_row pointer" data-id="' . $val->id . '" data-order="' . ($k + 1) . '"><i class="fa fa-arrows-alt"></i></span>';
            $fields[$k] = $val;
        }

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords->count(),
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $fields,
        ]);
    }

    public function saveOrder(Request $request)
    {
        $data = $request->validate([
            "field_id" => "required",
            "new_order" => "required",
            "total_records" => "required",
            "page" => "required",
        ]);
        $id = $request->field_id;
        $field = $this->field->find($id);
        if ($field) {
            $page = $request->page;
            $total_records = (int) $request->total_records;
            $old_order = $field->order;
            $new_order = $page > 1 ? $total_records * ($page - 1) + $request->new_order : $request->new_order;
            $offset =  $total_records * ($page - 1);
            if ($new_order != $old_order) {
                $fields = $this->field;
                if ($new_order < $old_order) {
                    $fields = $fields->lesserOrder(['new_order' => $new_order, 'old_order' => $old_order]);
                } else if ($new_order > $old_order) {
                    $fields = $fields->greaterOrder(['new_order' => $new_order, 'old_order' => $old_order]);
                }
                $fields = $fields->get();
                foreach ($fields as $val) {
                    if ($new_order < $old_order) {
                        $order = $val->order + 1;
                    } else {
                        $order = $val->order - 1;
                    }
                    $val->update(["order" => $order]);
                }
            }
            $field->update(["order" => $new_order]);
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

    public function import(Request $request)
    {
        $request->validate([
            "import" => "required|file"
        ]);
        $file = $request->File("import");
        Excel::import(new FieldImport, $file);
        return back()->with('success', 'Fields imported Successfully!');
    }
}
