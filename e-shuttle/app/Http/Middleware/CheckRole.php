<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            // Store intended URL in session
            $request->session()->put('url.intended', $request->url());
            
            if ($role === 'admin') {
                return redirect()->route('admin.login')->with('error', 'Please login as admin first.');
            } else {
                return redirect()->route('user.login')->with('error', 'Please login first.');
            }
        }

        if ($request->user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            
            // Redirect to appropriate dashboard based on user's actual role
            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Access denied for this section.');
            } else {
                return redirect()->route('user.dashboard')->with('error', 'Access denied for this section.');
            }
        }

        return $next($request);
    }
}