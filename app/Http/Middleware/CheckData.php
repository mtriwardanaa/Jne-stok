<?php

namespace App\Http\Middleware;

use Closure;

use App\Order;
use App\Barang;

use DB;

class CheckData
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
    	$get_order = Order::whereNull('approved_by')->count();
    	$barang = Barang::where('qty_barang', '<', DB::raw('warning_stok'))->whereNull('deleted_at')->count();

    	session(['get_order' => $get_order, 'barang' => $barang]);

        return $next($request);
    }
}
