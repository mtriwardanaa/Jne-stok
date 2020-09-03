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
    
    public function dashboard(Request $request)
    {
    	$id_divisi = Auth::user()->id_divisi;
    	$id_kategori = Auth::user()->id_kategori;

        $year = date('Y');
        if ($request->has('tahun')) {
            $year = $request->get('tahun');
        }


    	if ($id_divisi == 10) {
            $order_pending       = Order::whereNull('approved_by')->count();
            $total_barang_masuk  = BarangMasuk::count();
            $total_barang_keluar = BarangKeluar::count();
            $barang              = Barang::where('qty_barang', '<', DB::raw('warning_stok'))->count();

            $column_keluar = [];
            $column_masuk = [];

            $return_keluar = [];
            $return_masuk = [];
            
            $data_keluar         = DB::select("SELECT MONTH(stok_barang_harga.tanggal_barang) as label, SUM(stok_barang_harga.harga_barang * stok_barang_harga.qty_barang) as y FROM stok_barang_harga WHERE year(stok_barang_harga.tanggal_barang) = ".$year." AND stok_barang_harga.id_barang_keluar IS NOT NULL GROUP BY MONTH(stok_barang_harga.tanggal_barang)");
            $data_masuk         = DB::select("SELECT MONTH(stok_barang_harga.tanggal_barang) as label, SUM(stok_barang_harga.harga_barang * stok_barang_harga.qty_barang) as y FROM stok_barang_harga WHERE year(stok_barang_harga.tanggal_barang) = ".$year." AND stok_barang_harga.id_barang_masuk IS NOT NULL GROUP BY MONTH(stok_barang_harga.tanggal_barang)");

            if (!empty($data_keluar)) {
                $column_keluar = array_column($data_keluar, 'label');
            }

            if (!empty($data_masuk)) {
                $column_masuk = array_column($data_masuk, 'label');
            }

            $data_bulan = [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des',
            ];

            $max = 0;

            if (!empty($column_masuk) && !empty($column_keluar)) {
                $max = max(max($column_masuk), max($column_keluar));
            } else {
                if (empty($column_masuk)) {
                    if (!empty($column_keluar)) {
                        $max = max($column_keluar);
                    }
                } else {
                    $max = max($column_masuk);
                }
            }

            foreach ($data_bulan as $row => $value) {
                if ($row < $max) {
                    $total_masuk = 0;
                    if (in_array(($row+1), $column_masuk)) {
                        $key = array_search(($row+1), $column_masuk);
                        if (gettype($key) == 'integer') {
                            $total_masuk = $data_masuk[$key]->y;
                        }
                    }

                    $return_masuk[] = ['label' => $value, 'y' => $total_masuk];

                    $total_keluar = 0;
                    if (in_array(($row+1), $column_keluar)) {
                        $key = array_search(($row+1), $column_keluar);
                        if (gettype($key) == 'integer') {
                            $total_keluar = $data_keluar[$key]->y;
                        }
                    }

                    $return_keluar[] = ['label' => $value, 'y' => $total_keluar];
                }
            }

            $data = [
                'order_pending'       => $order_pending,
                'total_barang_masuk'  => $total_barang_masuk,
                'total_barang_keluar' => $total_barang_keluar,
                'barang'              => $barang,
                'masuk'               => $return_masuk,
                'keluar'              => $return_keluar,
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