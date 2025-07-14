<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedServicePdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'booked_service_id',
        'subject',
        'text',
        'file',
    ];

    public function bookedService()
    {
        return $this->belongsTo(BookService::class);
    }
}
