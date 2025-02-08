<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Pour le moment, on laisse passer tout le monde pour les tests
        return $next($request);
    }
} 