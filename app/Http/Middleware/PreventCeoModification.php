<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCeoModification
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $targetUser = $request->route('user');

        // بما أننا قمنا بعمل Cast في الموديل، $targetUser->role أصبح Enum الآن
        if ($targetUser && $targetUser->role === \App\Enums\UserRole::CEO) {

            if ($request->expectsJson()) {
                return response()->json(['message' => 'لا يمكن تعديل المدير التنفيذي.'], 403);
            }

            return redirect()->back()->with('error', 'عذراً، لا يمكن إجراء أي تعديل على حساب المدير التنفيذي (CEO).');
        }

        return $next($request);
    }
}