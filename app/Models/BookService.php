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
        "status",
    ];

    static public $status_array = [
        "1" => "Order Received",
        "2" => "Deposit Requested",
        "3" => "Order in Progress",
        "4" => "Invoice Created",
        "5" => "Full Payment Received",
        "6" => "Schedule Created",
        "7" => "Pre Arrival",
        "8" => "Member Arrived",
        "9" => "Order Completed",
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

    public function bookFields()
    {
        return $this->hasMany(BookFeild::class, 'book_service_id', 'id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where('user_id', $search["user_id"])->where('package_id', $search["package_id"])->where('service_id', $search["service_id"]);
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

    public function getService()
    {
        $service = $this->service()->first();
        return $service ? $service->name : '';
    }

    public static function getStatuses()
    {
        $status = self::$status_array;
        return $status;
    }

    public function depositStatus()
    {
        if ($this->status == 1 && $this->deposit_status == 0 && empty($this->deposit_url)) {
            return true;
        }
        return false;
    }

    public function invoiceStatus()
    {
        if ($this->status == 3 && $this->invoice_status == 0 && empty($this->invoice_url)) {
            return true;
        }
        return false;
    }
}
