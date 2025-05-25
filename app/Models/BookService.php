<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookService extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "package_id",
        "service_id",
        "field_id",
        "value",
        "status",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Feature::class, 'service_id', 'id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('user_id', $search["user_id"])->where('package_id', $search["package_id"])->where('service_id', $search["service_id"]);
    }
}
