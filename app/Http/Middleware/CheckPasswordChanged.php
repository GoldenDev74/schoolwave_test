<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordChanged
{
    /**
     * Les routes qui ne nécessitent pas de vérification de changement de mot de passe
     */
    protected $except = [
        'login',
        'logout',
        'change-password',
        'change-password/*'
    ];

    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur n'est pas connecté ou si la route est dans les exceptions
        if (!Auth::check() || $this->shouldPassThrough($request)) {
            return $next($request);
        }

        // Si l'utilisateur n'a pas encore changé son mot de passe
        if (!Auth::user()->password_changed) {
            return redirect()->route('change.password')
                ->with('warning', 'Vous devez changer votre mot de passe avant de continuer.');
        }

        return $next($request);
    }

    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $route) {
            if ($request->is($route)) {
                return true;
            }
        }

        return false;
    }
}
