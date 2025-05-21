<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'name',
        'icon'
    ];

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

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'field_features');
    }
}
