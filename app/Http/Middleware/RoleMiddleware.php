<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Unauthorized. You do not have access to this area.');
        }

        $user = Auth::user();

        // Blocked users are logged out immediately
        if ($user->status === 'blocked') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been blocked. Please contact support.']);
        }

        // Sellers awaiting or rejected approval redirect to pending page
        if ($user->role === 'seller' && in_array($user->status, ['pending', 'rejected'])) {
            return redirect()->route('auth.pending');
        }

        return $next($request);
    }
}
