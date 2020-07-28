<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\Order;
use App\BarangMasuk;
use App\BarangKeluar;
use App\Barang;

use App\Order;

class Dashboard extends Controller
{
	public function __construct()
    {
        $this->middleware('auth_user');
    }
    
    public function dashboard()
    {

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
