<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\Order;
use App\BarangMasuk;
use App\BarangKeluar;
use App\Barang;

class Dashboard extends Controller
{
	public function __construct()
    {
        $this->middleware('auth_user');
    }
    
    public function dashboard()
    {
    	if (Auth::user()->id_divisi == 10) {
            $order_pending      = Order::whereNull('approved_by')->count();
            $total_barang_masuk = BarangMasuk::count();
            $total_barang_keluar= BarangKeluar::count();
            $barang             = Barang::where('qty_barang', '<', DB::raw('warning_stok'))->count();

            $data = [
                'order_pending'      => $order_pending,
                'total_barang_masuk' => $total_barang_masuk,
                'total_barang_keluar'=> $total_barang_keluar,
                'barang'             => $barang,
            ];

	    	return view('dashboard_ga', $data);
    	}

    	return view('dashboard_user');
    }
}
