<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Divisi;
use App\AgenKategori;
use App\BarangKeluar;
use App\Barang;
use App\BarangHarga;
use App\User;

use Excel;
use Auth;
use PDF;

use App\Exports\ReportExport;
use App\Lib\MyHelper;

class ReportController extends Controller
{
    public function index()
    {
    	$divisi = Divisi::get()->toArray();
    	if (empty($divisi)) {
    		return redirect('divisi')->withErrors(['Divisi masih kosong, silahkan input divisi terlebih dahulu']);
    	}

    	$kategori = AgenKategori::get()->toArray();
    	if (empty($kategori)) {
    		return redirect('kategori')->withErrors(['Agen Kategori masih kosong, silahkan input kategori terlebih dahulu']);
    	}

    	$user = User::where('id_divisi', 13)->get()->toArray();

    	$bulan = date('m');
    	$tahun = date('Y');

        return view('report::index', ['divisi' => $divisi, 'user' => $user, 'bulan' => $bulan, 'tahun' => $tahun, 'kategori' => $kategori]);
    }

    public function print(Request $request)
    {
    	$post = $request->except('_token');
    	// return $post;
    	$check_divisi = Divisi::where('id', $post['id_divisi'])->first();
    	if (empty($check_divisi)) {
    		return back()->withErrors(['Divisi tidak ditemukan']);
    	}

    	$title = str_replace( array( '\'', '"', ',' , ';', '<', '>' , '/'), ' ', $check_divisi->nama);

    	if (isset($post['id_kategori'])) {
    		$check_kategori = AgenKategori::where('id', $post['id_kategori'])->first();
    		if (empty($check_kategori)) {
	    		return back()->withErrors(['Sub Agen tidak ditemukan']);
	    	}

	    	$title = str_replace( array( '\'', '"', ',' , ';', '<', '>' , '/'), ' ', $check_divisi->nama.' ('.$check_kategori->nama.')');
    	}

    	if (isset($post['id_agen'])) {
    		$check_agen = User::where('id', $post['id_agen'])->first();
    		if (empty($check_agen)) {
	    		return back()->withErrors(['Sub Agen tidak ditemukan']);
	    	}

	    	$title = str_replace( array( '\'', '"', ',' , ';', '<', '>' , '/'), ' ', $check_agen->nama);
    	}

    	$tanggal_mulai = date('Y-m-d 00:00:00', strtotime($post['tanggal_mulai']));
    	$tanggal_selesai = date('Y-m-d 23:59:59', strtotime($post['tanggal_selesai']));

    	$barang_keluar = BarangKeluar::with('detailStok.stokBarang.stokBarangSatuan', 'user')->where('id_divisi', $post['id_divisi'])->where('tanggal', '>=', $tanggal_mulai)->where('tanggal', '<=', $tanggal_selesai);

    	if (isset($post['id_kategori'])) {
    		$barang_keluar = $barang_keluar->where('id_kategori', $post['id_kategori']);
    	}

    	if (isset($post['id_agen'])) {
    		$all_id_agen = [];
    		$get_all_agen = User::where('nama', $check_agen->nama)->get()->toArray();
    		if (!empty($get_all_agen)) {
    			$all_id_agen = array_column($get_all_agen, 'id');
    		}

    		$barang_keluar = $barang_keluar->whereIn('id_agen', $all_id_agen);
    	}

    	$barang_keluar = $barang_keluar->get()->toArray();
    	// return $barang_keluar;
    	$data_print = [];
		$harga_total  = 0;
		$total_barang = 0;
		$total_stok   = 0;
		$data_barang = [];

    	$data_report = array_map(function($arr) use (&$data_print, &$harga_total, &$total_barang, &$total_stok, &$data_barang) {
			$data_print[] = [
				'tanggal'       => date('d-m-Y H:i', strtotime($arr['tanggal'])),
				'no_transaksi'  => $arr['no_barang_keluar'],
				'request_by'    => strtoupper($arr['nama_user_request']),
				'proses_by'     => strtoupper($arr['user']['nama']),
				'kode_barang'   => $arr['detail_stok'][0]['stok_barang']['kode_barang'],
				'nama_barang'   => $arr['detail_stok'][0]['stok_barang']['nama_barang'],
				'jumlah_barang' => $arr['detail_stok'][0]['qty_barang']. " ".$arr['detail_stok'][0]['stok_barang']['stok_barang_satuan']['nama_satuan'],
				'harga_satuan'  => $arr['detail_stok'][0]['harga_barang'],
				'harga_total'   => ($arr['detail_stok'][0]['qty_barang'] * $arr['detail_stok'][0]['harga_barang']),
            ];

            $harga_total  = $harga_total + ($arr['detail_stok'][0]['qty_barang'] * $arr['detail_stok'][0]['harga_barang']);
			$total_stok   = $total_stok + $arr['detail_stok'][0]['qty_barang'];

			if (!in_array($arr['detail_stok'][0]['id_barang'], $data_barang)) {
				$total_barang++;
				$data_barang[] = $arr['detail_stok'][0]['id_barang'];
			}

            if (isset($arr['detail_stok'])) {
            	if (count($arr['detail_stok']) > 1) {
            		foreach ($arr['detail_stok'] as $key => $value) {
            			if ($key > 0) {
            				$data_print[] = [
								'tanggal'       => '',
								'no_transaksi'  => '',
								'request_by'    => '',
								'proses_by'     => '',
								'kode_barang'   => $value['stok_barang']['kode_barang'],
								'nama_barang'   => $value['stok_barang']['nama_barang'],
								'jumlah_barang' => $value['qty_barang']. " ".$value['stok_barang']['stok_barang_satuan']['nama_satuan'],
								'harga_satuan'  => $value['harga_barang'],
								'harga_total'   => ($value['qty_barang'] * $value['harga_barang']),
				            ];

				            $harga_total  = $harga_total + ($value['qty_barang'] * $value['harga_barang']);
							$total_stok   = $total_stok + $value['qty_barang'];

							if (!in_array($value['id_barang'], $data_barang)) {
								$total_barang++;
								$data_barang[] = $value['id_barang'];
							}
            			}
            		}
            	}
            }

        }, $barang_keluar);

        $data_print[] = [
			'tanggal'       => '',
			'no_transaksi'  => '',
			'request_by'    => '',
			'proses_by'     => '',
			'kode_barang'   => '',
			'nama_barang'   => '',
			'jumlah_barang' => '',
			'harga_satuan'  => 'Total Harga',
			'harga_total'   => $harga_total,
        ];

    	$nama = 'REPORT';
    	$data = json_decode(json_encode($data_print, JSON_NUMERIC_CHECK), true);

    	$heading = $this->heading();
		return Excel::download(new ReportExport($data, $heading, $title, 0), $nama.'.xlsx');
    }

    public function heading()
    {
		return [
			'TANGGAL',
            'No TRX',
            'Direquest Oleh',
            'Diproses Oleh',
            'KODE BARANG',
            'NAMA_BARANG',
            'JUMLAH BARANG',
            'HARGA SATUAN',
            'HARGA TOTAL',
        ];
    }

    public function stok(Request $request)
    {
    	$post = $request->except('_token');

    	$stock_no = $post['bulan'].'/SO/V/'.$post['tahun'];
    	$group = 'ALL';
    	$periode_mulai = date($post['tahun'].'-'.$post['bulan'].'-01');
    	$periode_akhir = date($post['tahun'].'-'.$post['bulan'].'-t');
    	$opname_no = 'F/PMD/'.date('m-d', strtotime($periode_mulai)).'/00';

    	$text_periode_awal = MyHelper::indonesian_date($periode_mulai, 'd F Y');
    	$text_periode_akhir = MyHelper::indonesian_date($periode_akhir, 'd F Y');

    	$report = [];
    	$barang = Barang::get()->toArray();
    	if (!empty($barang)) {
    		$sort = array_column($barang, 'nama_barang');

			array_multisort($sort, SORT_ASC, $barang);

    		$report = array_map(function($arr) use ($periode_mulai, $periode_akhir) {
    			$data = [
    				'kode_barang' => $arr['kode_barang'],
    				'nama_barang' => $arr['nama_barang'],
    			];

    			//jumlah sebelumnya
    			$total_masuk_awal = 0;
    			$total_keluar_awal = 0;

    			$masuk_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<', date('Y-m-d 00:00:00', strtotime($periode_mulai)))->whereNotNull('id_barang_masuk')->get()->toArray();
    			if (!empty($masuk_awal)) {
    				$column = array_column($masuk_awal, 'qty_barang');
    				$total_masuk_awal = array_sum($column);
    			}

    			$keluar_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<', date('Y-m-d 00:00:00', strtotime($periode_mulai)))->whereNotNull('id_barang_keluar')->get()->toArray();
    			if (!empty($keluar_awal)) {
    				$column = array_column($keluar_awal, 'qty_barang');
    				$total_keluar_awal = array_sum($column);
    			}

    			$data['stok'] = $total_masuk_awal - $total_keluar_awal;

    			//jumlah sekarang
    			$total_masuk_akhir = 0;
    			$total_keluar_akhir = 0;

    			$masuk_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<', date('Y-m-d 00:00:00', strtotime($periode_akhir)))->whereNotNull('id_barang_masuk')->get()->toArray();
    			if (!empty($masuk_awal)) {
    				$column = array_column($masuk_awal, 'qty_barang');
    				$total_masuk_akhir = array_sum($column);
    			}

    			$keluar_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<', date('Y-m-d 00:00:00', strtotime($periode_akhir)))->whereNotNull('id_barang_keluar')->get()->toArray();
    			if (!empty($keluar_awal)) {
    				$column = array_column($keluar_awal, 'qty_barang');
    				$total_keluar_akhir = array_sum($column);
    			}

    			$data['opname'] = $total_masuk_akhir - $total_keluar_akhir;
    			return $data;
			}, $barang);
    	}

    	$pelaksana = ucwords(strtolower(Auth::user()->nama));
    	$koordinator = ucwords(strtolower($post['koordinator']));
    	$audit = ucwords(strtolower($post['audit']));

    	$data = [
			'stock_no'           => $stock_no,
			'group'              => $group,
			'periode_mulai'      => $periode_mulai,
			'periode_akhir'      => $periode_akhir,
			'text_periode_awal'  => $text_periode_awal,
			'text_periode_akhir' => $text_periode_akhir,
			'opname_no'          => $opname_no,
			'pelaksana'          => $pelaksana,
			'koordinator'        => $koordinator,
			'audit'              => $audit,
			'report'             => $report,
    	];

    	return view('report::opname', $data);
    }

    public function stokCetak()
    {
    	$pdf = PDF::loadview('report::testing');
		return $pdf->stream();

    	$pdf = PDF::loadview('report::opname');
    	return $pdf->download('laporan-pdf.pdf');
    }

}
