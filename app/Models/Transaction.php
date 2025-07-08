<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'session_id',
        'package_id',
        'promo_id',
        'total_amount',
        'package_amount',
        'discount_amount',
        'payable_amount',
        'transaction_type',
        'status',
        'book_service_id',
        'customer_id'
    ];

    // In a model or config
    static public $status_array = [
        0 => 'order',
        1 => 'invoice',
        2 => 'deposit',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // not in use
    public function bookService()
    {
        return $this->belongsTo(BookService::class, 'order_id', 'id');
    }


    // not in use
    static public $transaction_types = [
        0 => 'order',
        1 => 'invoice',
        2 => 'deposit',
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

    public function scopeSearch($query, $search)
    {
        $query->whereHas("user", function ($q) use ($search) {
            $q->where("full_name", "like", "%{$search}%");
            $q->orWhere("email", "like", "%{$search}%");
        })
            ->orWhereHas("package", function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%");
            });
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
            if ($package) {
                $total = calculate_discount_price($package->price, $promo->discount_amount, $promo->discount_type, 1);
            } else {
                $total = 0;
            }
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

    
    public function scopePaymentLink($query,$paymentLink)
    {
        $query->where('session_id', $paymentLink);
    }

    
    public function scopeSession($query,$session_id)
    {
        $query->where('session_id', $session_id);
    }
    
}
