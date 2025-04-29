<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMobileIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && is_null($user->mobile_verified_at) && !$request->routeIs('filament.admin.pages.profile-edit')) {
            return redirect()->route('filament.admin.pages.profile-edit');
        }

        return $next($request);
    }
}
