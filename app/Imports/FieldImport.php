<?php

namespace App\Imports;

use App\Models\Field;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class FieldImport implements ToModel, WithHeadingRow
{
    use RemembersRowNumber;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $field;
    public function __construct()
    {
        $this->field = new Field();
    }
    public function model(array $row)
    {
        $getRowNumber = $this->getRowNumber();
        $data = Validator::make(
            $row,
            [
                "name" => "required",
                "type" => "required",
                "options" => "sometimes",
            ],
            [
                'name.required' => "The NAME field is required at ROW " . $getRowNumber,
                'type.required' => "The TYPE field is required at ROW " . $getRowNumber,
            ]
        )->validate();
        if ($data) {
            $field = $this->field->where("name", $data["name"])->first();
            if ($field) {
                $field = $this->field->update(
                    [
                        "type" => $data["type"],
                        "options" => explode(",", $data["options"]),
                    ]
                );
            } else {
                $field = $this->field->create(
                    [
                        "name" => $data["name"],
                        "type" => $data["type"],
                        "options" => explode(",", $data["options"]),
                        "order" => $this->field->count() + 1
                    ]
                );
            }

            return $field;
        }
    }
}
