<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home');   // murid diarahkan ke beranda
        }
        return $next($request);
    }
}
