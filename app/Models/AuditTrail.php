<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $fillable = [
        'user_id', 'module', 'action', 'details', 'ip_address', 'user_agent'
    ];
    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
