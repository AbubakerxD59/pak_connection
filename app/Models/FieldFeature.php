<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_id',
        'field_id'
    ];
}
