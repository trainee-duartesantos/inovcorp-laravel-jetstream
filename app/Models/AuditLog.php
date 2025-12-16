<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'module',
        'object_id',
        'action',
        'changes',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    // Relação com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
