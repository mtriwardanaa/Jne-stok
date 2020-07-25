<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Barang;
use App\BarangKeluar;
use App\BarangKeluarDetail;
use App\Order;
use App\OrderDetail;
use DB;
use Auth;

class OrderController extends Controller
{
    public function list()
    {
    	$user = Auth::user();

    	$list = Order::with('approved_user', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->orderBy('tanggal', 'DESC');

    	if ($user->id_divisi != 10) {
	    	$list = $list->where('id_divisi', $user->id_divisi);

	    	if (isset($user->id_agen_kategori)) {
		    	$list = $list->where('id_kategori', $user->id_agen_kategori);
	    	}
    	}

    	$list = $list->get()->toArray();

        return view('order::list_order', ['list' => $list]);
    }

    public function create(Request $request)
    {
    	$barang = Barang::with('stokBarangSatuan')->get()->toArray();
    	if (empty($barang)) {
    		return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
    	}

    	return view('order::create_order', ['barang' => $barang]);
    }

    public function approve(Request $request, $id)
    {
    	$data = Order::with('approved_user', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $id)->first();
    	if (empty($data)) {
    		return back()->withErrors(['Data tidak ditemukan']);	
    	}

    	$barang = Barang::with('stokBarangSatuan')->get()->toArray();
    	if (empty($barang)) {
    		return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
    	}

    	return view('order::approve_order', ['barang' => $barang, 'data' => $data]);
    }

    public function updateApprove(Request $request, $id)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	// return $post;
    	$user = Auth::user();
    	if (empty($user)) {
    		DB::rollback();
    		return back()->withErrors(['User tidak ditemukan']);
    	}

    	$data = Order::with('approved_user', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $id)->first();
    	if (empty($data)) {
    		DB::rollback();
    		return back()->withErrors(['Data tidak ditemukan']);	
    	}

    	$data_barang_keluar = [
			'tanggal'     => date('Y-m-d H:i:s'),
			'id_divisi'   => $data['id_divisi'],
			'id_kategori' => $data['id_kategori'],
			'created_by'  => $user->id,
			'id_order'    => $data->id,
    	];

    	$create_barang_keluar = BarangKeluar::create($data_barang_keluar);
    	if (!$create_barang_keluar) {
    		DB::rollback();
    		return back()->withErrors(['Tambah barang keluar gagal'])->withInput();
    	}

    	if (isset($post['id_details'])) {
    		foreach ($post['id_details'] as $key => $value) {
    			$check_detail = OrderDetail::where('id', $value)->first();
    			if (empty($check_detail)) {
    				DB::rollback();
    				return back()->withErrors(['Data details order tidak ditemukan']);
    			}

    			$check_detail->jumlah_approve = $post['jumlah_approve'][$key];
    			$check_detail->update();
    			if (!$check_detail) {
    				DB::rollback();
    				return back()->withErrors(['Update data gagal']);
    			}

    			$data_detail = [
					'id_barang_keluar' => $create_barang_keluar['id'],
					'id_barang'       => $check_detail->id_barang,
					'qty_barang'      => $post['jumlah_approve'][$key],
    			];

    			$create_detail = BarangKeluarDetail::create($data_detail);
    			if (!$create_detail) {
    				DB::rollback();
    				return back()->withErrors(['Tambah barang keluar detail gagal'])->withInput();
    			}
    		}
    	}
    		
    	$data->tanggal_approve = date('Y-m-d H:i:s');
    	$data->approved_by = $user->id;
    	$data->update();
    	if (!$data) {
    		DB::rollback();
			return back()->withErrors(['Approve data gagal']);
    	}

    	DB::commit();
    	return redirect('order')->with(['success' => ['Approve data sukses']]);
    }

    public function store(Request $request)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	$user = Auth::user();
    	$data_barang_order = [
			'tanggal'     => date('Y-m-d H:i:s'),
			'no_order'    => 'TRX-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
			'id_divisi'   => $user->id_divisi,
			'id_kategori' => $user->id_kategori ?? null,
			'created_by'  => $user->id
    	];

    	$create_barang_order = Order::create($data_barang_order);
    	if (!$create_barang_order) {
    		DB::rollback();
    		return back()->withErrors(['Tambah order gagal'])->withInput();
    	}

    	if (isset($post['id_barang'])) {
    		$data_barang_order_detail = [];
    		foreach ($post['id_barang'] as $key => $value) {
    			$data_detail = [
					'id_order' => $create_barang_order['id'],
					'id_barang'       => $value,
					'qty_barang'      => $post['jumlah_barang'][$key],
					'created_at'      => date('Y-m-d H:i:s'),
					'updated_at'      => date('Y-m-d H:i:s'),
    			];

    			$data_barang_order_detail[] = $data_detail;

    			$check_barang = Barang::where('id', $value)->first();
    			if (empty($check_barang)) {
    				DB::rollback();
    				return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    			}

    			$check_barang->qty_barang = $check_barang->qty_barang - $post['jumlah_barang'][$key];
    			$check_barang->update();
    			if (!$check_barang) {
    				DB::rollback();
    				return back()->withErrors(['Update jumlah barang gagal'])->withInput();
    			}
    		}

    		if (!empty($data_barang_order_detail)) {
    			$create_detail = OrderDetail::insert($data_barang_order_detail);
    			if (!$create_detail) {
    				DB::rollback();
    				return back()->withErrors(['Tambah request detail gagal'])->withInput();
    			}
    		}
    	}

    	DB::commit();
    	return redirect('order')->with(['success' => ['Tambah request berhasil']]);
    }
}
