<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // تجاهل الملفات والطلبات غير المهمة
        if (
            $request->is('build/*') ||
            $request->is('css/*') ||
            $request->is('js/*') ||
            $request->is('images/*') ||
            $request->is('favicon.ico')
        ) {
            return $response;
        }

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $response;
    }
}