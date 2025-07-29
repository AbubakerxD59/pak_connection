<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "type",
        "options",
        "order",
    ];

    protected $casts = [
        "options" => "array"
    ];

    public function scopeOrder($query)
    {
        $query->where("order", "ASC");
    }

    public function scopeLesserOrder($query, $order)
    {
        $query->where('order', '>=', $order["new_order"])->where('order', '<', $order["old_order"]);
    }

    public function scopeGreaterOrder($query, $order)
    {
        $query->where('order', '>', $order["old_order"])->where('order', '<=', $order["new_order"]);
    }

    public function scopeSearch($query, $search)
    {
        $query->where("name", "like", "%$search%");
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $type = str_replace(" ", "", $value);
                return strtolower($type);
            }
        );
    }
}
