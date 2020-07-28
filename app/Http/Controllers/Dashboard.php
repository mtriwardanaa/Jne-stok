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
    	$id_divisi = Auth::user()->id_divisi;
    	$id_kategori = Auth::user()->id_kategori;

    	if ($id_divisi == 10) {
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

    	$total_order = Order::where('id_divisi', $id_divisi)->where('id_kategori', $id_kategori)->count();
    	$total_pending = Order::where('id_divisi', $id_divisi)->where('id_kategori', $id_kategori)->whereNull('approved_by')->count();

    	$order = Order::with('divisi', 'kategori')->where('id_divisi', $id_divisi)->where('id_kategori', $id_kategori)->orderBy('tanggal', 'DESC')->limit(10)->get();

    	$data = [
    		'total_pending' => $total_pending,
    		'total_order' => $total_order,
    		'order' => $order,
    	];
    	return view('dashboard_user', $data);
    }
}