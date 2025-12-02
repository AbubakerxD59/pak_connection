<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_path',
        'status',
        'admin_notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? url(getImage($this->document_path)) : null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function getUserDetailsAttribute()
    {
        $view = view("admin.verification.ajax.user_details")->with("user", $this->user);
        return $view->render();
    }

    public function getStatusBadgeAttribute()
    {
        $view = view("admin.verification.ajax.status_badge")->with("status", $this->status);
        return $view->render();
    }

    public function getActionAttribute()
    {
        $view = view("admin.verification.ajax.action")->with("document", $this);
        return $view->render();
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function getDocumentAttribute()
    {
        return ucwords($this->document_type);
    }
}
