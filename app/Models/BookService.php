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
        "3" => "Deposit Paid", 
        "4" => "Order in Progress",
        "5" => "Invoice Created",
        "6" => "Full Payment Received",
        "7" => "Schedule Created",
        "8" => "Pre Arrival",
        "9" => "Member Arrived",
        "10" => "Order Completed",
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

    public function scopeDatatableSearch($query, $search)
    {
        $query->whereHas("user", function ($q) use ($search) {
            $q->where("full_name", "like", "%$search%");
            $q->orWhere("email", "like", "%$search%");
            $q->orWhere("membership_id", "like", "%$search%");
            $q->orWhere("whatsapp_number", "like", "%{$search}%");
        })
            ->orWhereHas("service", function ($q) use ($search) {
                $q->where("name", "like", "%$search%");
            });
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


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'book_service_id', 'id');
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
        if ($this->status == 4 && $this->invoice_status == 0 && empty($this->invoice_url)) {
            return true;
        }
        return false;
    }

    // new status added


    public function depositPaidStatus()
    {
        return $this->status == 2 && $this->deposit_status == 1;
    }

    public function inprogressStatus()
    {
        return $this->status == 3;
    }


    public function fullPaymentStatus()
    {
        return $this->status == 5 && $this->invoice_status == 1;
    }

    public function scheduleStatus()
    {
        return $this->status == 6;
    }

    public function preArrivalStatus()
    {
        return $this->status == 7;
    }

    public function arrivalStatus()
    {
        return $this->status == 8;
    }

    public function completionStatus()
    {
        return $this->status == 9;
    }

    public function getTransactions()
    {
        $transactions = $this->transactions()->get();
        return $transactions;
    }
}
