<?php

namespace Modules\BarangMasuk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Barang;
use App\BarangMasuk;
use App\BarangHarga;
use App\BarangMasukDetail;
use App\Supplier;

use Auth;
use DB;

class BarangMasukController extends Controller
{
    public function list()
    {
    	$list = BarangMasuk::with('user', 'userUpdate', 'details.stokBarang.stokBarangSatuan', 'details.stokSupplier')->whereNull('deleted_at')->orderBy('tanggal', 'DESC')->get()->toArray();

        $data_list = array_map(function($arr) {
            $input = [];
            $ringkasan = '-';

            if (isset($arr['details'])) {
                $barang = [];
                foreach ($arr['details'] as $key => $value) {
                    $barang[] = $value['stok_barang']['nama_barang'];
                }
            }

            if (!empty($barang)) {
                $ringkasan = implode(', ', $barang);
            }

            $input['ringkasan'] = $ringkasan;
            return $arr + $input;
        }, $list);
        
        return view('barangmasuk::list_barang_masuk', ['list' => $data_list]);
    }

    public function create(Request $request)
    {
    	$barang = Barang::whereNull('deleted_at')->get()->toArray();
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
			'no_barang_masuk' => 'NBM-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
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

    			$data_stok = [
					'id_barang_masuk' => $create_barang_masuk['id'],
					'id_barang'       => $value,
					'qty_barang'      => $post['jumlah_barang'][$key],
					'harga_barang'    => $post['harga'][$key],
					'tanggal_barang'  => $create_barang_masuk['tanggal'],
					'created_at'      => date('Y-m-d H:i:s'),
					'updated_at'      => date('Y-m-d H:i:s'),
    			];

    			$data_barang_harga[] = $data_stok;

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

    		if (!empty($data_barang_harga)) {
    			$create_stok = BarangHarga::insert($data_barang_harga);
    			if (!$create_stok) {
    				DB::rollback();
    				return back()->withErrors(['Tambah barang masuk stok gagal'])->withInput();
    			}
    		}
    	}

    	DB::commit();
    	return redirect('barangmasuk')->with(['success' => ['Tambah barang masuk berhasil']]);
    }

    public function edit(Request $request, $id)
    {
    	$barang = Barang::whereNull('deleted_at')->get()->toArray();
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

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $post = $request->except('_token');
        $user = Auth::user();

        $data = BarangMasuk::with('details')->where('id', $id)->first();
        if (empty($data)) {
            return back()->withErrors(['Data tidak ditemukan']);    
        }

        $data_barang_masuk = [
            'tanggal'         => date('Y-m-d H:i:s', strtotime($post['tanggal'].' '.$post['jam'])),
            'no_barang_masuk' => 'NBM-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
            'created_by'      => $user->id
        ];

        $update_barang_masuk = BarangMasuk::where('id', $id)->update($data_barang_masuk);
        if (!$update_barang_masuk) {
            DB::rollback();
            return back()->withErrors(['Edit barang masuk gagal'])->withInput();
        }

        $barang_now = [];
        // return $data;
        if (isset($data['details'])) {
            $barang_now = array_column($data['details']->toArray(), 'id_barang');

            foreach ($data['details'] as $key => $value) {
                if (!in_array($value['id_barang'], $post['id_barang'])) {
                    $delete_barang_detail = BarangMasukDetail::where('id', $value['id'])->first();
                    if (empty($delete_barang_detail)) {
                        DB::rollback();
                        return back()->withErrors(['Data detail tidak ditemukan']);
                    }

                    $update_jumlah_barang = Barang::where('id', $value['id_barang'])->first();
                    if (empty($update_jumlah_barang)) {
                        return back()->withErrors(['Data barang tidak ditemukan']);
                    }

                    $update_jumlah_barang->qty_barang = $update_jumlah_barang->qty_barang - $delete_barang_detail->qty_barang;
                    $update_jumlah_barang->update();
                    if (!$update_jumlah_barang) {
                        DB::rollback();
                        return back()->withErrors(['Update jumlah barang gagal']);
                    }

                    $delete_barang_detail->delete();
                    if (!$delete_barang_detail) {
                        DB::rollback();
                        return back()->withErrors(['Detail barang gagal di hapus']);
                    }

                    $barang_harga = BarangHarga::where('id_barang_masuk', $id)->where('id_barang', $value['id_barang'])->first();
                    if (isset($barang_harga->min_barang)) {
                        $check_stok = BarangHarga::where('id', '!=', $barang_harga->id)->where('id_barang', $value['id_barang'])->where('qty_barang', '>', DB::raw('min_barang'))->whereNotNull('id_barang_masuk')->orderBy('tanggal_barang', 'asc')->get()->toArray();
                        if (empty($check_stok)) {
                            DB::rollback();
                            return back()->withErrors(['Stok barang sudah terpakai ('.$update_jumlah_barang->nama_barang.'), tidak bisa dihapus'])->withInput();
                        }

                        $total_pesan = $barang_harga->min_barang;
                        
                        foreach ($check_stok as $row => $stok) {
                            if ($total_pesan < 1) {
                                break;
                            }

                            $total_minta = $total_pesan;
                            $stok_sekarang = ($stok['qty_barang'] - $stok['min_barang']);
                            if ($total_minta > $stok_sekarang) {
                                $total_minta = $stok_sekarang;
                            }

                            $update_stok = BarangHarga::where('id', $stok['id'])->update(['min_barang' => ($total_minta + $stok['min_barang'])]);
                            if (!$update_stok) {
                                DB::rollback();
                                return back()->withErrors(['Update jumlah barang stok gagal'])->withInput();
                            }

                            $total_pesan = $total_pesan - $total_minta;
                        }
                    }

                    $barang_harga->delete();
                    if (!$barang_harga) {
                        DB::rollback();
                        return back()->withErrors(['Detail barang harag gagal di hapus']);
                    }
                }

                if (in_array($value['id_barang'], $post['id_barang'])) {
                    $key_data = array_search($value['id_barang'], $post['id_barang']);
                    if (gettype($key_data) != 'integer') {
                        DB::rollback();
                        return back()->withErrors(['Data invalid'])->withInput();
                    }

                    $delete_barang_detail = BarangMasukDetail::where('id', $value['id'])->first();
                    if (empty($delete_barang_detail)) {
                        DB::rollback();
                        return back()->withErrors(['Data detail tidak ditemukan']);
                    }

                    $update_jumlah_barang = Barang::where('id', $value['id_barang'])->first();
                    if (empty($update_jumlah_barang)) {
                        return back()->withErrors(['Data barang tidak ditemukan']);
                    }

                    $update_jumlah_barang->qty_barang = ($update_jumlah_barang->qty_barang - $delete_barang_detail->qty_barang) + $post['jumlah_barang'][$key_data];
                    $update_jumlah_barang->update();
                    if (!$update_jumlah_barang) {
                        DB::rollback();
                        return back()->withErrors(['Update jumlah barang gagal']);
                    }
                }
            }
        }

        return redirect('barangmasuk')->with(['success' => ['Barang masuk berhasil diupdate']]);
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
