<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        "chat_id",
        "sender_type",
        "sender_id",
        "content",
    ];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
    public function getSenderNameAttribute()
    {
        $user = $this->sender()->first();
        return $user ? $user->full_name : "BOT";
    }
}
