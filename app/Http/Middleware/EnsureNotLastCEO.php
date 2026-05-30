<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotLastCEO
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->route('user');

        // التأكد من أن المستخدم موجود لتجنب أخطاء النطاق
        if ($user && $user->role === UserRole::CEO) {

            $ceoCount = User::where('role', UserRole::CEO)->count();

            if ($ceoCount <= 1) {
                return back()->with('error', 'لا يمكنك تغيير صلاحيات الـ CEO الوحيد في النظام.');
            }
        }

        return $next($request);
    }
}