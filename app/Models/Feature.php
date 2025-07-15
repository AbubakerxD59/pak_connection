<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'name',
        'icon',
        'order',
    ];

    protected $appends = ["book"];

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'field_features');
    }

    public function bookServices()
    {
        return $this->hasMany(BookService::class, 'service_id', 'id');
    }

    public function bookFields()
    {
        return $this->hasMany(BookFeild::class, 'service_id', 'id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value != '' && $value != null) ? url(getImage('', $value)) : no_image(),
        );
    }

    protected function book(): Attribute
    {
        return Attribute::make(
            get: function () {
                $user = Auth::user();
                $package = $user->getPackage();
                if ($package) {
                    $bookedService = $this->bookServices()->search(["user_id" => $user->id, "package_id" => $package->id, "service_id" => $this->id])->where('status', '!=', 10)->get();
                } else {
                    $bookedService = [];
                }
                // return $bookedService ? $bookedService->status : null;
                return count($bookedService) > 0 ? true : false;
            }
        );
    }

    public function scopeLesserOrder($query, $order)
    {
        $query->where('order', '>=', $order["new_order"])->where('order', '<', $order["old_order"]);
    }

    public function scopeGreaterOrder($query, $order)
    {
        $query->where('order', '>', $order["old_order"])->where('order', '<=', $order["new_order"]);
    }
}
