<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "status",
        "is_first_contact",
        "is_automated",
        "agent_id",
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function agent()
    {
        return $this->belongsTo(User::class, "agent_id", "id");
    }
    public function getUserNameAttribute()
    {
        $user = $this->user()->first();
        if ($user) {
            $value = $user->full_name ? $user->full_name : $user->email;
        }
        return $user ? $value : "-";
    }
    public function getAgentNameAttribute()
    {
        $agent = $this->agent()->first();
        return $agent ? $agent->full_name : '-';
    }
    public function getStatusViewAttribute()
    {
        $view = view("admin.chats.ajax.status")->with("chat", $this);
        return $view->render();
    }
}
