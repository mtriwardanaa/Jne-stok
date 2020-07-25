<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class StokGA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->id_divisi != 10) {
            return back()->withErrors(['Anda tidak memiliki izin untuk mengakses fitur ini, silahkan kontak admin']);
        }
        
        return $next($request);
    }
}
