<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookFeild extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "book_service_id",
        "field_id",
        "value",
        "status",
    ];

    public function scopeSearch($query, $search)
    {
        $query->where('user_id', $search["user_id"])->where('book_service_id', $search["book_service_id"]);
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', "id");
    }

    public function getField()
    {
        $field = $this->field()->first();
        return $field;
    }
}
