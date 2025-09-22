<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotCustomer
{
    public function handle(Request $request, Closure $next, $guard = 'customer')
    {
        if (!Auth::guard($guard)->check()) {
            // Store the intended URL for redirect after login
            $request->session()->put('url.intended', $request->url());
            
            return redirect()->route('customer.login')
                ->with('message', 'Please login or create an account to proceed with checkout.');
        }

        return $next($request);
    }
}
