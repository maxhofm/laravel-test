<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RequireRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @param $role
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role): Response|RedirectResponse
    {
        $roles = array_unique([User::ADMIN_ROLE, $role]);
        abort_unless(
            auth()->check() && (Auth::user()->hasAnyRole($roles)),
            403,
            "У вас нет прав просматривать эту страницу",
        );
        return $next($request);
    }
}
