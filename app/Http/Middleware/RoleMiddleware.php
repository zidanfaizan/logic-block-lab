<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->user()) {
                // Redirect to correct dashboard
                $redirectRoute = $request->user()->isTeacher()
                    ? 'teacher.dashboard'
                    : 'student.dashboard';
                return redirect()->route($redirectRoute)
                    ->with('error', 'Kamu tidak punya akses ke halaman itu.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
