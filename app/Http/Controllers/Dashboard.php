<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Order;

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
	    	return view('dashboard_ga');
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
