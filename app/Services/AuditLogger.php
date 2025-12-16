<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditLogger
{
    public static function log(
        string $module,
        string $action,
        ?int $objectId = null,
        ?array $changes = null,
        ?Request $request = null
    ): void {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'module'     => $module,
            'object_id'  => $objectId,
            'action'     => $action,
            'changes'    => $changes,
            'ip'         => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
