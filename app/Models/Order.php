<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "customer_id",
        "session_id",
        "package_id",
        "promo_id",
        "total_amount",
        "status",
    ];

    static public $status_array = [
        "0" => "Payment Pending",
        "1" => "Paid",
        "2" => "Payment Failed",
    ];

    protected $appends = ["status_view"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function promo()
    {
        return $this->belongsTo(PromoCode::class, 'promo_id', 'id');
    }

    public function scopeUnpaid($query)
    {
        $query->where('status', '0');
    }

    public function scopePaid($query)
    {
        $query->where('status', '1');
    }

    public function getDiscount()
    {
        $promo = $this->promo()->first();
        $package = $this->package()->first();
        if (!empty($promo) && !empty($package)) {
            $discount = calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 0);
            return '£' . $discount;
        } else {
            return '£' . "0";
        }
    }

    public function getTotal()
    {
        if (!empty($this->promo_id)) {
            $promo = $this->promo()->first();
            $package = $this->package()->first();
            $total = calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 1);
            return '£' . $total;
        } else {
            return '£' . $this->total_amount;
        }
    }

    public function totalEarning()
    {
        $total_earning = $this->sum("total_amount");
        return number_format($total_earning, 2);
    }

    protected function statusView(): Attribute
    {
        return Attribute::make(
            get: function () {
                $status = self::$status_array[$this->status];
                $div = "";
                if ($status == 'Payment Pending') {
                    $div = "<span class='badge badge-warning'>" . $status . "</span>";
                } else if ($status == "Paid") {
                    $div = "<span class='badge badge-success'>" . $status . "</span>";
                } elseif ($status == "unpaid") {
                    $div = "<span class='badge badge-danger'>" . $status . "</span>";
                }
                return $div;
            }
        );
    }

    public function getUser()
    {
        $user = $this->user()->first();
        return $user ? $user->full_name : '';
    }

    public function getPackage()
    {
        $package = $this->package()->first();
        return $package ? $package->name : '';
    }

    public function getCoupon()
    {
        $coupon = $this->promo()->first();
        return $coupon ? $coupon->name : '';
    }

    public function getPackageTotal()
    {
        $package = $this->package()->first();
        return $package ? $package->price : 0;
    }
}
