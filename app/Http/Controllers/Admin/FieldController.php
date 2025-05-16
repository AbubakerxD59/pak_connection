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
