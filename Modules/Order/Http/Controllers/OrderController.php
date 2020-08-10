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
use App\BarangHarga;
use DB;
use Auth;

class OrderController extends Controller
{
    public function list(Request $request)
    {
    	$user = Auth::user();

    	$list = Order::with('approved_user', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->orderBy('tanggal', 'DESC');

    	if ($user->id_divisi != 10) {
	    	$list = $list->where('id_divisi', $user->id_divisi);

	    	if (isset($user->id_agen_kategori)) {
		    	$list = $list->where('id_kategori', $user->id_agen_kategori);
	    	}
    	}

    	$req = '';

    	if ($request->has('status')) {
    		if ($request->get('status') == 'approve') {
    			$list = $list->whereNotNull('approved_by');
    		}

    		if ($request->get('status') == 'pending') {
    			$list = $list->whereNull('approved_by');
    		}

    		$req = $request->get('status');
    	}

    	$list = $list->get()->toArray();
    	// return $list;
        return view('order::list_order', ['list' => $list, 'req' => $req]);
    }

    public function create(Request $request)
    {
    	$barang = Barang::with('stokBarangSatuan')->get()->toArray();
    	if (empty($barang)) {
    		return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
    	}

    	return view('order::create_order', ['barang' => $barang]);
    }

    public function approve(Request $request)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	// return $post;

    	$user = Auth::user();
    	if (empty($user)) {
    		DB::rollback();
    		return back()->withErrors(['User tidak ditemukan']);
    	}

    	$data = Order::with('approved_user', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $post['id_order'])->first();
    	// return $data;
    	if (empty($data)) {
    		DB::rollback();
    		return back()->withErrors(['Data tidak ditemukan']);
    	}

    	$data_barang_keluar = [
			'tanggal'           => date('Y-m-d H:i:s'),
			'no_barang_keluar'  => 'NBKO-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
			'id_divisi'         => $data['id_divisi'],
			'id_kategori'       => $data['id_kategori'],
			'created_by'        => $user->id,
			'id_order'          => $data->id,
			'nama_user_request' => $data['nama_user_request'],
    	];

    	if ($data['id_divisi'] == 13) {
    		$data_barang_keluar['id_agen'] = $data['created_user']['id'];
    	}

    	$create_barang_keluar = BarangKeluar::create($data_barang_keluar);
    	if (!$create_barang_keluar) {
    		DB::rollback();
    		return back()->withErrors(['Tambah barang keluar gagal'])->withInput();
    	}
    	// return $post;
    	if (isset($post['id_detail_order'])) {
    		$data_barang_keluar_detail = [];
    		$data_stok = [];
    		foreach ($post['id_detail_order'] as $key => $value) {
    			$check_detail_order = OrderDetail::where('id', $value)->first();
    			// return $check_detail_order;
    			$data_detail = [
					'id_barang_keluar' => $create_barang_keluar['id'],
					'id_barang'        => $check_detail_order->id_barang,
					'qty_barang'       => $post['jumlah'][$key],
					'created_at'       => date('Y-m-d H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s'),
    			];

    			$check_barang = Barang::where('id', $check_detail_order->id_barang)->first();
    			if (empty($check_barang)) {
    				DB::rollback();
    				return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    			}

    			$check_stok = BarangHarga::where('id_barang', $check_detail_order->id_barang)->where('qty_barang', '>', DB::raw('min_barang'))->whereNotNull('id_barang_masuk')->orderBy('tanggal_barang', 'asc')->get()->toArray();
    			if (empty($check_stok)) {
    				DB::rollback();
    				return back()->withErrors(['Stok barang tidak ada'])->withInput();
    			}

    			$total_pesan = $post['jumlah'][$key];
    			
    			foreach ($check_stok as $row => $stok) {
    				if ($total_pesan < 1) {
    					break;
    				}

    				$total_minta = $total_pesan;
    				$stok_sekarang = ($stok['qty_barang'] - $stok['min_barang']);
    				if ($total_minta > $stok_sekarang) {
    					$total_minta = $stok_sekarang;
    				}

    				$data_stok[] = [
						'id_barang_keluar' => $create_barang_keluar['id'],
						'id_barang'        => $check_detail_order->id_barang,
						'qty_barang'       => $total_minta,
						'harga_barang'     => $stok['harga_barang'],
						'tanggal_barang'   => date('Y-m-d H:i:s'),
						'created_at'       => date('Y-m-d H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s'),
    				];

    				$update_stok = BarangHarga::where('id', $stok['id'])->update(['min_barang' => $total_minta]);
    				if (!$update_stok) {
    					DB::rollback();
    					return back()->withErrors(['Update jumlah barang stok gagal'])->withInput();
    				}

    				$total_pesan = $total_pesan - $total_minta;
    			}

    			$data_barang_keluar_detail[] = $data_detail;


    			$check_barang->qty_barang = $check_barang->qty_barang - $post['jumlah'][$key];
    			$check_barang->update();
    			if (!$check_barang) {
    				DB::rollback();
    				return back()->withErrors(['Update jumlah barang gagal'])->withInput();
    			}

    			$check_detail_order->jumlah_approve = $post['jumlah'][$key];
    			$check_detail_order->update();
    			if (!$check_detail_order) {
    				DB::rollback();
    				return back()->withErrors(['Update jumlah approve barang gagal'])->withInput();
    			}
    		}

    		if (!empty($data_barang_keluar_detail)) {
    			$create_detail = BarangKeluarDetail::insert($data_barang_keluar_detail);
    			if (!$create_detail) {
    				DB::rollback();
    				return back()->withErrors(['Tambah barang keluar detail gagal'])->withInput();
    			}
    		}

    		if (!empty($data_stok)) {
    			$create_stok = BarangHarga::insert($data_stok);
    			if (!$create_stok) {
    				DB::rollback();
    				return back()->withErrors(['Tambah barang keluar stok detail gagal'])->withInput();
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
    	return back()->with(['success' => ['Approve data sukses']]);
    }

    public function detail(Request $request, $id)
    {
    	$data = Order::with('approved_user.divisi', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $id)->first();
    	if (empty($data)) {
    		return back()->withErrors(['Data tidak ditemukan']);	
    	}
    	// return $data;
    	$req = null;
    	if ($request->has('status')) {
    		$req = $request->get('status');
    	}

    	return view('order::detail_order', ['data' => $data, 'req' => $req]);
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
			'tanggal'           => date('Y-m-d H:i:s'),
			'no_order'          => 'TRX-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
			'id_divisi'         => $user->id_divisi,
			'id_kategori'       => $user->id_agen_kategori ?? null,
			'created_by'        => $user->id,
			'nama_user_request' => $post['nama_user'],
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
