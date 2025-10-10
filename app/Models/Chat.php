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
    public static $statuses = [
        "open",
        "pending_agent",
        "agent_assigned",
        "closed",
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function agent()
    {
        return $this->belongsTo(User::class, "agent_id", "id");
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }
    public function getUserNameAttribute()
    {
        $user = $this->user()->first();
        $view = view("admin.chats.ajax.user_name")->with("user", $user);
        return $view->render();
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
    public function getActionAttribute()
    {
        $view = view("admin.chats.ajax.action")->with("chat", $this);
        return $view->render();
    }
    public function scopePending($query)
    {
        $query->where("status", "pending_agent");
    }
}
