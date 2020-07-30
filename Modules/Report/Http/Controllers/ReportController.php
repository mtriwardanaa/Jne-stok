<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Divisi;
use App\AgenKategori;
use App\BarangKeluar;

use Excel;

use App\Exports\ReportExport;

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

        return view('report::index', ['divisi' => $divisi, 'kategori' => $kategori]);
    }

    public function print(Request $request)
    {
    	$post = $request->except('_token');

    	$check_divisi = Divisi::where('id', $post['id_divisi'])->first();
    	if (empty($check_divisi)) {
    		return back()->withErrors(['Divisi tidak ditemukan']);
    	}

    	$tanggal_mulai = date('Y-m-d 00:00:00', strtotime($post['tanggal_mulai']));
    	$tanggal_selesai = date('Y-m-d 23:59:59', strtotime($post['tanggal_selesai']));

    	$barang_keluar = BarangKeluar::with('detailStok.stokBarang.stokBarangSatuan')->where('id_divisi', $post['id_divisi'])->where('tanggal', '>=', $tanggal_mulai)->where('tanggal', '<=', $tanggal_selesai);
    	if (isset($post['id_kategori'])) {
    		$barang_keluar = $barang_keluar->where('id_kategori', $post['id_kategori']);
    	}

    	$barang_keluar = $barang_keluar->get()->toArray();

    	$data_print = [];
		$harga_total  = 0;
		$total_barang = 0;
		$total_stok   = 0;
		$data_barang = [];

    	$data_report = array_map(function($arr) use (&$data_print, &$harga_total, &$total_barang, &$total_stok, &$data_barang) {
			$data_print[] = [
				'tanggal'       => date('d-m-Y H:i', strtotime($arr['tanggal'])),
				'no_transaksi'  => $arr['no_barang_keluar'],
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
			'kode_barang'   => '',
			'nama_barang'   => '',
			'jumlah_barang' => '',
			'harga_satuan'  => 'Total Harga',
			'harga_total'   => $harga_total,
        ];

    	$nama = 'REPORT';
    	$data = json_decode(json_encode($data_print, JSON_NUMERIC_CHECK), true);

    	$heading = $this->heading();
		return Excel::download(new ReportExport($data, $heading, $check_divisi->nama, 0), $nama.'.xlsx');
    }

    public function heading()
    {
		return [
			'TANGGAL',
            'No TRX',
            'KODE BARANG',
            'NAMA_BARANG',
            'JUMLAH BARANG',
            'HARGA SATUAN',
            'HARGA TOTAL',
        ];
    }

}
