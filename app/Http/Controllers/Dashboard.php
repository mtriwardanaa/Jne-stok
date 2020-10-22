<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Session;

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
    	$id_kategori = Auth::user()->id_agen_kategori;

        $year = date('Y');

        if ($request->has('tahun')) {
            $year = $request->get('tahun');
        }

        $fitur = session()->get('fitur');

        if (in_array(31, $fitur)) {
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

            $year = date('Y');
            $month = date('m');
            // $month = "09";

            $laporan_divisi = DB::select("SELECT divisi.nama as label, (COUNT(stok_barang_keluar.id_divisi) * 100 / (Select COUNT(*) From stok_barang_keluar WHERE year(stok_barang_keluar.tanggal) = ".$year." AND month(stok_barang_keluar.tanggal) = ".$month.")) as y FROM stok_barang_keluar, divisi WHERE year(stok_barang_keluar.tanggal) = ".$year." AND month(stok_barang_keluar.tanggal) = ".$month." AND stok_barang_keluar.id_divisi=divisi.id GROUP BY stok_barang_keluar.id_divisi");

            $laporan_hybrid = DB::select("SELECT users.nama as label, (COUNT(stok_barang_keluar.id_agen) * 100 / (Select COUNT(*) From stok_barang_keluar WHERE stok_barang_keluar.id_divisi=13 AND year(stok_barang_keluar.tanggal) = ".$year." AND month(stok_barang_keluar.tanggal) = ".$month.")) as y FROM stok_barang_keluar, users WHERE stok_barang_keluar.id_divisi=13 AND year(stok_barang_keluar.tanggal) = ".$year." AND month(stok_barang_keluar.tanggal) = ".$month." AND stok_barang_keluar.id_agen=users.id GROUP BY users.nama");

            $laporan_sub = DB::select("SELECT agen_kategori.nama as label, (COUNT(stok_barang_keluar.id_kategori) * 100 / (Select COUNT(*) From stok_barang_keluar WHERE stok_barang_keluar.id_divisi=23 AND year(stok_barang_keluar.tanggal) = ".$year." AND month(stok_barang_keluar.tanggal) = ".$month.")) as y FROM stok_barang_keluar, agen_kategori WHERE stok_barang_keluar.id_divisi=23 AND year(stok_barang_keluar.tanggal) = ".$year." AND month(stok_barang_keluar.tanggal) = ".$month." AND stok_barang_keluar.id_kategori=agen_kategori.id GROUP BY agen_kategori.nama");
            // return $laporan_sub;

            $data = [
                'order_pending'       => $order_pending,
                'total_barang_masuk'  => $total_barang_masuk,
                'total_barang_keluar' => $total_barang_keluar,
                'barang'              => $barang,
                'masuk'               => $return_masuk,
                'keluar'              => $return_keluar,
                'laporan_divisi'      => $laporan_divisi,
                'laporan_hybrid'      => $laporan_hybrid,
                'laporan_sub'         => $laporan_sub,
            ];

	    	return view('dashboard_ga', $data);
    	}

        $user = Auth::user();
    	$total_order = Order::where('id_divisi', $id_divisi)->where('id_kategori', $id_kategori);
    	// $total_pending = Order::where('id_divisi', $id_divisi)->where('id_kategori', $id_kategori)->whereNull('approved_by');

    	// $order = Order::with('divisi', 'kategori', 'created_user')->where('id_divisi', $id_divisi)->where('id_kategori', $id_kategori)->orderBy('tanggal', 'DESC')->limit(10);

        if ($user->level == 'general') {
            if ($user->id_divisi == 13 || $user->id_divisi == 23) {
                // $order = $order->whereHas('created_user', function ($query) use ($user) {
                //     return $query->whereRaw("lower(REPLACE(nama, ' ', '')) = ?", [strtolower(str_replace(' ', '', $user->nama))]);
                // });

                $total_order = $total_order->whereHas('created_user', function ($query) use ($user) {
                    return $query->whereRaw("lower(REPLACE(nama, ' ', '')) = ?", [strtolower(str_replace(' ', '', $user->nama))]);
                });

                // $total_pending = $total_pending->whereHas('created_user', function ($query) use ($user) {
                //     return $query->whereRaw("lower(REPLACE(nama, ' ', '')) = ?", [strtolower(str_replace(' ', '', $user->nama))]);
                // });
            }
        }

        $list = BarangKeluar::with('user', 'agen', 'userUpdate', 'details.stokBarang.stokBarangSatuan', 'detailStok.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->where('distribusi_sales', 1)->orderBy('tanggal', 'DESC');

        if (!in_array(31, $fitur)) {
            $list = $list->where('id_divisi', $user->id_divisi);

            if (isset($user->id_agen_kategori)) {
                $list = $list->where('id_kategori', $user->id_agen_kategori);
            }
        }

        if ($user->level == 'general') {
            if ($user->id_divisi == 13) {
                $list = $list->whereHas('agen', function ($query) use ($user) {
                    return $query->whereRaw("lower(REPLACE(nama, ' ', '')) = ?", [strtolower(str_replace(' ', '', $user->nama))]);
                });
            }
        }

        $list = $list->count();

        // $order = $order->get();
        $total_order = $total_order->count();
        // $total_pending = $total_pending->count();

    	$data = [
    		// 'total_pending' => $total_pending,
            'total_order' => $total_order,
            // 'order'    => $order,
            'total_dis'   => $list,
    	];

    	return view('dashboard_user', $data);
    }
}