<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LecturerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (!auth()->user()->isLecturer()) {
            abort(403, 'Access denied.');
        }
        return $next($request);
    }
}