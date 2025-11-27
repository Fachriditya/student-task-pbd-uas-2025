<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->idrole == 1) {
            return $next($request);
        }

        if (Auth::user()->idrole == 2) {
             return redirect()->route('stok-barang.index')->with('error', 'Akses Ditolak. Anda bukan Super Admin.');
        }
        
        abort(403, 'AKSES DITOLAK.');
    }
}