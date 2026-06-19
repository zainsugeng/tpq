<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMurid
{
    public function handle(Request $request, Closure $next): Response
    {
        // Middleware 'auth' sudah jalan duluan, jadi user pasti sudah login.
        // Kalau yang login bukan murid (berarti admin), lempar ke dashboard admin.
        if (Auth::user()->role !== 'murid') {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
