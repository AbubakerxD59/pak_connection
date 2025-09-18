<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        "package_id",
        "type",
        "price",
        "stripe_id",
    ];
    public function package(){
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
    public function getTypeTextAttribute()
    {
        $value = $this->type;
        if ($value > 1) {
            $value = $value . " Months";
        } else {
            $value = $value . " Month";
        }
        return $value;
    }
}
