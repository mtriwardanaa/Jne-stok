<?php

namespace Modules\Barang\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Barang;
use App\BarangSatuan;
use App\User;

use DB;

class BarangController extends Controller
{
    public function list()
    {
    	$list = Barang::with('stokBarangSatuan')->whereNull('deleted_at')->get()->toArray();
    	return view('barang::list_barang', ['list' => $list]);
    }

    public function create()
    {
    	$satuan = BarangSatuan::get()->toArray();
    	return view('barang::create_barang', ['satuan' => $satuan]);
    }

    public function store(Request $request)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	$check_code = Barang::where('kode_barang', $post['kode_barang'])->whereNull('deleted_at')->first();
    	if (!empty($check_code)) {
    		DB::rollback();
    		return back()->withErrors(['Kode barang sudah digunakan, silahkan masukkan kode yang lain atau edit barang dengan kode yang sama'])->withInput();
    	}

    	$check_satuan = strpos($post['id_satuan_barang'], 'ThIsiSNeW#');
    	if ($check_satuan !== false) {
          	$satuan = explode("#", $post['id_satuan_barang']); 

          	$check_all_satuan = BarangSatuan::all()->toArray();
          	foreach ($check_all_satuan as $key => $value) {
              	if (strcasecmp($value['nama_satuan'], $satuan['1']) == 0) {
                    $idb = $value['id'];
                    break;
                } else {
                    $idb = 0;
                }
          	}

          	if ($idb == 0) {
              	$create_satuan = BarangSatuan::create(['nama_satuan'=>$satuan['1']]);
              	if (!$create_satuan) {
              		DB::rollback();
    				return back()->withErrors(['Tambah satuan gagal'])->withInput();
              	}

              	$post['id_satuan_barang'] = $create_satuan->id;
          	} else {
            	$post['id_satuan_barang'] = $idb;
          	}
        }

    	$check_delete = Barang::where('kode_barang', $post['kode_barang'])->whereNotNull('deleted_at')->first();
    	if (!empty($check_delete)) {
			$check_delete->kode_barang      = $post['kode_barang'];
			$check_delete->nama_barang      = $post['nama_barang'];
			$check_delete->qty_barang       = $post['jumlah_barang'];
			$check_delete->id_barang_satuan = $post['id_satuan_barang'];
			$check_delete->deleted_at       = null;
    		$check_delete->update();
    		if (!$check_delete) {
    			DB::rollback();
				return back()->withErrors(['Tambah barang gagal'])->withInput();
    		}
    	} else {
    		$data_create = [
				'kode_barang'      => $post['kode_barang'],
				'nama_barang'      => $post['nama_barang'],
				'qty_barang'       => $post['jumlah_barang'],
				'id_barang_satuan' => $post['id_satuan_barang'],
    		];

    		$create_barang = Barang::create($data_create);
    		if (!$create_barang) {
    			DB::rollback();
				return back()->withErrors(['Tambah barang baru gagal'])->withInput();
    		}
    	}

    	DB::commit();
    	return redirect('barang')->with(['success' => ['Tambah barang berhasil']]);
    }

    public function edit(Request $request, $id)
    {
    	$check_barang = Barang::where('id', $id)->first();
    	if (empty($check_barang)) {
    		return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    	}

    	$satuan = BarangSatuan::get()->toArray();

    	return view('barang::edit_barang', ['satuan' => $satuan, 'barang' => $check_barang]);
    }

    public function history(Request $request, $id)
    {
    	$check_barang = Barang::where('id', $id)->first();
    	if (empty($check_barang)) {
    		return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    	}

    	$audits = Barang::find($id)->audits->toArray();
    	if (!empty($audits)) {
    		$audits = array_map(function ($row) {
    			$item = [];
    			$user = User::where('id', $row['user_id'])->first();
    			$input['user'] = $user;

    			if ($row['auditable_type'] == 'App\Barang') {
    				$item = Barang::where('id', $row['auditable_id'])->first();
    			}
    			$input['item'] = $item;

    			$total = max(count($row['old_values']), count($row['new_values']));
    			$input['total'] = $total;
    			return $row + $input;
    		}, $audits);
    	}

    	// return $audits;

    	return view('barang::history_barang', ['audits' => $audits]);
    }

    public function update(Request $request, $id)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	$check_barang = Barang::where('id', $id)->first();
    	if (empty($check_barang)) {
    		return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    	}

    	$check_satuan = strpos($post['id_satuan_barang'], 'ThIsiSNeW#');
    	if ($check_satuan !== false) {
          	$satuan = explode("#", $post['id_satuan_barang']); 

          	$check_all_satuan = BarangSatuan::all()->toArray();
          	foreach ($check_all_satuan as $key => $value) {
              	if (strcasecmp($value['nama_satuan'], $satuan['1']) == 0) {
                    $idb = $value['id'];
                    break;
                } else {
                    $idb = 0;
                }
          	}

          	if ($idb == 0) {
              	$create_satuan = BarangSatuan::create(['nama_satuan'=>$satuan['1']]);
              	if (!$create_satuan) {
              		DB::rollback();
    				return back()->withErrors(['Tambah satuan gagal'])->withInput();
              	}

              	$post['id_satuan_barang'] = $create_satuan->id;
          	} else {
            	$post['id_satuan_barang'] = $idb;
          	}
        }

        $check_barang->kode_barang      = $post['kode_barang'];
		$check_barang->nama_barang      = $post['nama_barang'];
		$check_barang->qty_barang       = $post['jumlah_barang'];
		$check_barang->id_barang_satuan = $post['id_satuan_barang'];
		$check_barang->deleted_at       = null;
		$check_barang->update();
		if (!$check_barang) {
			DB::rollback();
			return back()->withErrors(['Update barang gagal'])->withInput();
		}

		DB::commit();
		return redirect('barang')->with(['success' => ['Update barang berhasil']]);
    }

    public function delete(Request $request, $id)
    {
    	$check_barang = Barang::where('id', $id)->first();
    	if (empty($check_barang)) {
    		return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    	}

    	$check_barang->deleted_at = date('Y-m-d H:i:s');
    	$check_barang->update();
    	if (!$check_barang) {
    		return back()->withErrors(['Hapus barang gagal'])->withInput();
    	}

    	return redirect('barang')->with(['success' => ['Hapus barang berhasil']]);
    }
}
