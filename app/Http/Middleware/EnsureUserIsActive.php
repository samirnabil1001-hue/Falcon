<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        // Highlighted Fix: Changed ->active to ->is_active
        if (Auth::check() && ! Auth::user()->is_active) {

            Auth::logout();

            // Invalidate the session to clear old data completely
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')
                ->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}