<?php

namespace Modules\BarangKeluar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\BarangKeluar;
use App\BarangHarga;
use App\BarangKeluarDetail;
use App\Barang;
use App\Supplier;
use App\User;
use App\Divisi;
use App\AgenKategori;

use DB;
use Auth;

class BarangKeluarController extends Controller
{
    public function list(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');


        if ($request->has('bulan')) {
            $bulan = $request->get('bulan');
        }

        if ($request->has('tahun')) {
            $tahun = $request->get('tahun');
        }

    	$list = BarangKeluar::with('user', 'userUpdate', 'details.stokBarang.stokBarangSatuan', 'detailStok.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->orderBy('tanggal', 'DESC')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->toArray();
    	
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

        // return $data_list;

        return view('barangkeluar::list_barang_keluar', ['list' => $data_list, 'bulan' => $bulan, 'tahun' => $tahun]);
    }

    public function suratJalan(Request $request, $id)
    {
        $get = BarangKeluar::with('user', 'agen', 'userUpdate', 'invoice', 'detailStok.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $id)->first();
        if (empty($get)) {
            return back()->withErrors(['Data tidak ditemukan']);
        }
        // return $get;
        return view('barangkeluar::surat_jalan', ['detail' => $get]);
    }

    public function detail(Request $request, $id)
    {
    	$list = BarangKeluar::with('user', 'agen', 'userUpdate', 'invoice', 'details.stokBarang.stokBarangSatuan', 'detailStok.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->where('id', $id)->orderBy('tanggal', 'DESC')->first();
    	// return $list;

    	$total_barang = 0;
    	$total_stok = 0;
    	$total_harga = 0;

    	$id_barang = [];

    	if (isset($list['detailStok'])) {
    		foreach ($list['detailStok'] as $key => $value) {
    			if (!in_array($value['id_barang'], $id_barang)) {
    				$id_barang[] = $value['id_barang'];
    				$total_barang++;
    			}

    			$total_stok = $total_stok + $value['qty_barang'];
    			$total_harga = $total_harga + ($value['harga_barang'] * $value['qty_barang']);
    		}
    	}

    	$data = [
			'total_barang' => $total_barang,
			'total_stok'   => $total_stok,
			'total_harga'  => $total_harga,
    	];

        return view('barangkeluar::detail_barang_keluar', ['data' => $list, 'post' => $data]);
    }

    public function create(Request $request)
    {
    	$barang = Barang::whereNull('deleted_at')->with('stokBarangSatuan')->get()->toArray();
    	if (empty($barang)) {
    		return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
    	}

    	$divisi = Divisi::get()->toArray();
    	if (empty($divisi)) {
    		return redirect('divisi')->withErrors(['Divisi masih kosong, silahkan input divisi terlebih dahulu']);
    	}

    	$kategori = AgenKategori::get()->toArray();
    	if (empty($kategori)) {
    		return redirect('kategori')->withErrors(['Agen Kategori masih kosong, silahkan input kategori terlebih dahulu']);
    	}

    	$user = User::where('id_divisi', 13)->get()->toArray();

    	return view('barangkeluar::create_barang_keluar', ['barang' => $barang, 'user' => $user, 'divisi' => $divisi, 'kategori' => $kategori]);
    }

    public function store(Request $request)
    {
    	DB::beginTransaction();
    	$post = $request->except('_token');
    	$user = Auth::user();
    	$data_barang_keluar = [
			'tanggal'           => date('Y-m-d H:i:s', strtotime($post['tanggal'].' '.$post['jam'])),
			'no_barang_keluar'  => 'NBK-'.date('md').'-'.$user->id.$user->id_divisi.date('His'),
			'id_divisi'         => $post['id_divisi'],
			'id_kategori'       => $post['id_kategori'],
			'created_by'        => $user->id,
			'id_agen'           => $post['id_agen'],
			'nama_user_request' => $post['nama_user'],
            'distribusi_sales'  => $post['distribusi_sales'],
    	];

    	$create_barang_keluar = BarangKeluar::create($data_barang_keluar);
    	if (!$create_barang_keluar) {
    		DB::rollback();
    		return back()->withErrors(['Tambah barang keluar gagal'])->withInput();
    	}

    	if (isset($post['id_barang'])) {
    		$data_barang_keluar_detail = [];
    		$data_stok = [];
    		foreach ($post['id_barang'] as $key => $value) {
    			$data_detail = [
					'id_barang_keluar' => $create_barang_keluar['id'],
					'id_barang'        => $value,
					'qty_barang'       => $post['jumlah_barang'][$key],
					'created_at'       => date('Y-m-d H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s'),
    			];

    			$check_barang = Barang::where('id', $value)->first();
    			if (empty($check_barang)) {
    				DB::rollback();
    				return back()->withErrors(['Barang tidak ditemukan'])->withInput();
    			}

    			$check_stok = BarangHarga::where('id_barang', $value)->where('qty_barang', '>', DB::raw('min_barang'))->whereNotNull('id_barang_masuk')->orderBy('tanggal_barang', 'asc')->get()->toArray();
    			if (empty($check_stok)) {
    				DB::rollback();
    				return back()->withErrors(['Stok barang tidak ada'])->withInput();
    			}

    			$total_pesan = $post['jumlah_barang'][$key];
    			
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
						'id_barang'        => $value,
						'qty_barang'       => $total_minta,
						'harga_barang'     => $stok['harga_barang'],
						'tanggal_barang'   => date('Y-m-d H:i:s'),
						'created_at'       => date('Y-m-d H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s'),
    				];

    				$update_stok = BarangHarga::where('id', $stok['id'])->update(['min_barang' => ($total_minta + $stok['min_barang'])]);
    				if (!$update_stok) {
    					DB::rollback();
    					return back()->withErrors(['Update jumlah barang stok gagal'])->withInput();
    				}

    				$total_pesan = $total_pesan - $total_minta;
    			}

    			$data_barang_keluar_detail[] = $data_detail;


    			$check_barang->qty_barang = $check_barang->qty_barang - $post['jumlah_barang'][$key];
    			$check_barang->update();
    			if (!$check_barang) {
    				DB::rollback();
    				return back()->withErrors(['Update jumlah barang gagal'])->withInput();
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

    	DB::commit();
    	return redirect('barangkeluar')->with(['success' => ['Tambah barang keluar berhasil']]);
    }

    public function edit(Request $request, $id)
    {
    	$barang = Barang::whereNull('deleted_at')->with('stokBarangSatuan')->get()->toArray();
    	if (empty($barang)) {
    		return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
    	}

    	$divisi = Divisi::get()->toArray();
    	if (empty($divisi)) {
    		return redirect('divisi')->withErrors(['Divisi masih kosong, silahkan input divisi terlebih dahulu']);
    	}

    	$kategori = AgenKategori::get()->toArray();
    	if (empty($kategori)) {
    		return redirect('kategori')->withErrors(['Agen Kategori masih kosong, silahkan input kategori terlebih dahulu']);
    	}

    	$data = BarangKeluar::with('details')->where('id', $id)->first();
    	if (empty($data)) {
    		return redirect('barangkeluar')->withErrors(['Data tidak ditemukan']);
    	}

    	return view('barangkeluar::edit_barang_keluar', ['barang' => $barang, 'divisi' => $divisi, 'kategori' => $kategori, 'data' => $data]);
    }

    public function delete(Request $request, $id)
    {
    	Db::beginTransaction();
    	$check = BarangKeluar::with('details')->where('id', $id)->first();
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

    			$check_barang->qty_barang = $check_barang->qty_barang + $value['qty_barang'];
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
			return back()->withErrors(['Hapus barang keluar gagal'])->withInput();
    	}

    	DB::commit();
    	return back()->with(['success' => ['Hapus barang keluar berhasil']]);
    }
}