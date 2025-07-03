<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'whatsapp_number',
        'phone_number',
        'city',
        'country',
        'address',
        'membership_id',
        'status',
        "stripe_id",
        "customer_id",
        "emergency_full_name",
        "emergency_phone_number",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function bookServices(){
        return $this->hasMany(BookService::class, 'user_id', 'id');
    }

    public function bookFields(){
        return $this->hasMany(BookFeild::class, 'user_id', 'id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('full_name', 'like', "%{$value}%")->orWhere('email', 'like', "%{$value}%");
    }

    public function scopeMembership($query, $search)
    {
        $query->where("membership_id", $search)->orWhere("email", $search);
    }

    protected function Password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => bcrypt($value),
        );
    }

    protected function profilePic(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value != '' && $value != null) ? url(getImage('users', $value)) : '',
        );
    }

    public function getRole()
    {
        $role = $this->roles()->first();
        return $role ? $role->name : '';
    }

    public function getPackage()
    {
        $latestOrder = $this->orders()->paid()->latest()->first();
        if ($latestOrder) {
            $package = $latestOrder->package()->first();
        }
        return $latestOrder ? $package : [];
    }
}
