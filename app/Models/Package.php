<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'icon',
        'personal_assistance',
        'stripe_product_id',
        'status'
    ];

    public static $prices = [
        "1" =>  "1 Month",
        "6" =>  "6 Months",
        "12" =>  "12 Months",
    ];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_packages');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'package_id', 'id');
    }

    public function bookServices()
    {
        return $this->hasMany(BookService::class, 'package_id', 'id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }

    public function scopePrice($query, $type)
    {
        $query->where("type", $type);
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value != '' && $value != null) ? url(getImage('', $value)) : '',
        );
    }

    public function getFeatures()
    {
        return $this->features()->pluck('name')->toArray();
    }

    public function checkFeatures()
    {
        $currentFeatures = $this->getFeatures();
        $features = check_features($currentFeatures, $this->id);
        return $features;
    }
    public function scopeActive($query)
    {
        $query->where("status", 1);
    }
    public function getIconViewAttribute()
    {
        $icon = "<img src='$this->icon' class='rounded-pill' width='50px'>";
        return $icon;
    }
    public function getPricingAttribute()
    {
        $pricing = $this->prices()->get();
        $view = view("admin.packages.dataTable.pricing")->with("pricing", $pricing);
        return $view->render();
    }
    public function getPersonalAttribute()
    {
        $view = view("admin.packages.dataTable.personal_assistance")->with("package", $this);
        return $view->render();
    }
    public function getActionAttribute()
    {
        $view = view("admin.packages.action")->with("package", $this);
        return $view->render();
    }
    public function getStatusViewAttribute()
    {
        $view = view("admin.packages.dataTable.status_view")->with("package", $this);
        return $view->render();
    }
}
