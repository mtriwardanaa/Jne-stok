<?php

namespace Modules\BarangMasuk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Barang;
use App\BarangMasuk;
use App\BarangMasukDetail;
use App\Supplier;

use Auth;
use DB;

class BarangMasukController extends Controller
{
    public function list()
    {
    	$list = BarangMasuk::with('user', 'userUpdate', 'details.stokBarang.stokBarangSatuan', 'details.stokSupplier')->whereNull('deleted_at')->orderBy('tanggal', 'DESC')->get()->toArray();
    	// return $list;
        return view('barangmasuk::list_barang_masuk', ['list' => $list]);
    }

    public function create(Request $request)
    {
    	$barang = Barang::get()->toArray();
    	if (empty($barang)) {
    		return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
    	}

    	$supplier = Supplier::get()->toArray();
    	if (empty($supplier)) {
    		return redirect('supplier')->withErrors(['Supplier masih kosong, silahkan input supplier terlebih dahulu']);
    	}

    	return view('barangmasuk::create_barang_masuk', ['barang' => $barang, 'supplier' => $supplier]);
    }

    public function detail(Request $request, $id)
    {
    	$list = BarangMasuk::with('user', 'userUpdate', 'details.stokBarang.stokBarangSatuan', 'details.stokSupplier')->where('id', $id)->whereNull('deleted_at')->orderBy('tanggal', 'DESC')->first();
    	if (empty($list)) {
    		return back()->withErrors(['Data barang masuk tidak ditemukan']);
    	}
    	
    	$total_barang = 0;
    	$total_stok = 0;
    	$total_harga = 0;

    	if (isset($list['details'])) {
    		foreach ($list['details'] as $key => $value) {
    			$total_barang++;
    			$total_stok = $total_stok + $value['qty_barang'];
    			$total_harga = $total_harga + $value['harga_barang'];
    		}
    	}

    	$data = [
			'total_barang' => $total_barang,
			'total_stok'   => $total_stok,
			'total_harga'  => $total_harga,
    	];

    	return view('barangmasuk::detail_barang_masuk', ['data' => $list, 'post' => $data]);
    }

    public function store(Request $request)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	$user = Auth::user();
    	$data_barang_masuk = [
			'tanggal'         => date('Y-m-d H:i:s', strtotime($post['tanggal'].' '.$post['jam'])),
			'no_barang_masuk' => 'NBK-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
			'created_by'      => $user->id
    	];

    	$create_barang_masuk = BarangMasuk::create($data_barang_masuk);
    	if (!$create_barang_masuk) {
    		DB::rollback();
    		return back()->withErrors(['Tambah barang masuk gagal'])->withInput();
    	}

    	if (isset($post['id_barang'])) {
    		$data_barang_masuk_detail = [];
    		$data_barang_harga = [];
    		foreach ($post['id_barang'] as $key => $value) {
    			$data_detail = [
					'id_barang_masuk' => $create_barang_masuk['id'],
					'id_barang'       => $value,
					'qty_barang'      => $post['jumlah_barang'][$key],
					'harga_barang'    => $post['harga'][$key],
					'id_supplier'     => $post['supplier_barang'][$key],
					'created_at'      => date('Y-m-d H:i:s'),
					'updated_at'      => date('Y-m-d H:i:s'),
    			];

    			$data_barang_masuk_detail[] = $data_detail;

    			$check_barang = Barang::where('id', $value)->first();
    			if (empty($check_barang)) {
    				DB::rollback();
    				return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    			}

    			$data_stok = [];

    			$check_barang->qty_barang = $check_barang->qty_barang + $post['jumlah_barang'][$key];
    			$check_barang->update();
    			if (!$check_barang) {
    				DB::rollback();
    				return back()->withErrors(['Update jumlah barang gagal'])->withInput();
    			}
    		}

    		if (!empty($data_barang_masuk_detail)) {
    			$create_detail = BarangMasukDetail::insert($data_barang_masuk_detail);
    			if (!$create_detail) {
    				DB::rollback();
    				return back()->withErrors(['Tambah barang masuk detail gagal'])->withInput();
    			}
    		}
    	}

    	DB::commit();
    	return redirect('barangmasuk')->with(['success' => ['Tambah barang masuk berhasil']]);
    }

    public function edit(Request $request, $id)
    {
    	$barang = Barang::get()->toArray();
    	if (empty($barang)) {
    		return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
    	}

    	$supplier = Supplier::get()->toArray();
    	if (empty($supplier)) {
    		return redirect('supplier')->withErrors(['Supplier masih kosong, silahkan input supplier terlebih dahulu']);
    	}

    	$data = BarangMasuk::with('details')->where('id', $id)->first();
    	if (empty($data)) {
    		return redirect('barangmasuk')->withErrors(['Data tidak ditemukan']);
    	}

    	return view('barangmasuk::edit_barang_masuk', ['data' => $data, 'barang' => $barang, 'supplier' => $supplier]);
    }

    public function delete(Request $request, $id)
    {
    	Db::beginTransaction();
    	$check = BarangMasuk::with('details')->where('id', $id)->first();
    	if (empty($check)) {
    		DB::rollback();
			return back()->withErrors(['Data tidak ditemukan'])->withInput();
    	}

    	if (isset($check->details)) {
    		foreach ($check->details as $key => $value) {
    			$check_barang = Barang::where('id', $value['id_barang'])->first();
    			if (empty($check_barang)) {
    				DB::rollback();
					return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    			}

    			$check_barang->qty_barang = $check_barang->qty_barang - $value['qty_barang'];
    			$check_barang->update();
    			if (!$check_barang) {
    				DB::rollback();
					return back()->withErrors(['Update jumlah barang gagal'])->withInput();
    			}
    		}
    	}

    	$check->deleted_at = date('Y-m-d H:i:s');
    	$check->update();
    	if (!$check) {
    		DB::rollback();
			return back()->withErrors(['Hapus barang masuk gagal'])->withInput();
    	}

    	DB::commit();
    	return back()->with(['success' => ['Hapus barang masuk berhasil']]);
    }
}
