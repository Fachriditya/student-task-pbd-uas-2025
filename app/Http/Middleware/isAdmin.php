<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->idrole == 1 || Auth::user()->idrole == 2)) {
            return $next($request);
        }
        abort(403, 'AKSES DITOLAK. Anda tidak memiliki hak akses operasional.');
    }
}