<?php

namespace App\Http\Middleware;

use Closure;

class FeatureControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $feature)
    {
        $fitur = session()->get('fitur');
        if (empty($fitur)) {
            return redirect('dashboard')->withErrors(['Anda tidak memiliki akses untuk fitur ini Silakan hubungi administrator.']);
        }

        if (!in_array($feature, $fitur)) {
            return redirect('dashboard')->withErrors(['Anda tidak memiliki akses untuk fitur ini Silakan hubungi administrator.']);
        }

        return $next($request);
    }
}
