<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'discount_type',
        'discount_amount',
        'status'
    ];

    protected $appends = ['expires_at'];

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'LIKE', "%{$search}%");
    }

    public function expiresAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $created_at = $this->created_at;
                $duration = '+ ' . $this->duration . 'days';
                return date('Y-m-d H:i:s', strtotime($created_at . $duration));
            }
        );
    }

    public function valid()
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $expires_at = strtotime($this->expires_at);
        return $expires_at >= $now ? true : false;
    }
}
