<?php

namespace Modules\Invoice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use DB;
use Auth;
use App\Invoice;
use App\BarangKeluar;
use App\BarangHarga;

class InvoiceController extends Controller
{
    public function list()
    {
    	$list = Invoice::with('stokBarangKeluar.detailStok.stokBarang.stokBarangSatuan', 'stokBarangKeluar.divisi', 'stokBarangKeluar.kategori', 'stokBarangKeluar.agen', 'user')->orderBy('id', 'DESC')->get()->toArray();
    	// return $list;
        return view('invoice::list_invoice', ['list' => $list]);
    }

    public function detail($id)
    {
    	$detail = Invoice::with('stokBarangKeluar.detailStok.stokBarang.stokBarangSatuan', 'stokBarangKeluar.divisi', 'stokBarangKeluar.kategori', 'stokBarangKeluar.agen', 'user')->where('id', $id)->first();
    	if (empty($detail)) {
    		return back()->withErrors(['Invoice tidak ditemukan']);
    	}

    	// return $detail;
    	return view('invoice::detail_invoice', ['detail' => $detail]);
    }

    public function generate(Request $request)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	// return $post;
    	$user = Auth::user();
    	$check_barang_keluar = BarangKeluar::where('id', $post['id_barang_keluar'])->first();

    	if (empty($check_barang_keluar)) {
    		DB::rollback();
    		return back()->withErrors(['Data barang keluar tidak ditemukan']);
    	}

    	$data_invoice = [
			'id_barang_keluar' => $post['id_barang_keluar'],
			'no_invoice'       => 'INV-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
			'created_by'       => $user->id,
			'tanggal_invoice'  => date('Y-m-d H:i:s'),
			'status'           => 'unpaid'
    	];

    	$create_invoice = Invoice::updateOrCreate(['id_barang_keluar' => $post['id_barang_keluar']], $data_invoice);
    	if (!$create_invoice) {
    		DB::rollback();
    		return back()->withErrors(['Invoice gagal dibuat']);
    	}

    	if (isset($post['id_barang_harga_detail'])) {
    		foreach ($post['id_barang_harga_detail'] as $key => $value) {
    			$check_harga = BarangHarga::where('id', $value)->first();
    			if (empty($check_harga)) {
    				DB::rollback();
    				return back()->withErrors(['Data barang tidak ditemukan']);
    			}

    			$check_harga->harga_barang_invoice = $post['harga'][$key];
    			$check_harga->update();
    			if (!$check_harga) {
    				DB::rollback();
    				return back()->withErrors(['Update harga invoice gagal']);
    			}
    		}
    	}

    	DB::commit();
    	return back()->with(['success' => ['Invoice berhasil dibuat']]);
    }
}
