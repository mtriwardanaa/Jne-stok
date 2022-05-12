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
use App\User;
use DB;
use Auth;

class OrderController extends Controller
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
        
    	$user = Auth::user();

    	$list = Order::with('approved_user', 'created_user', 'updated_user', 'rejected_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->orderBy('tanggal', 'DESC')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);

    	$fitur = session()->get('fitur');

        if (!in_array(31, $fitur)) {
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

        $user = Auth::user();
        // return $user;
        if ($user->level == 'general') {
            if ($user->id_divisi == 13 || $user->id_divisi == 23) {
                $list = $list->whereHas('created_user', function ($query) use ($user) {
                    return $query->whereRaw("lower(REPLACE(nama, ' ', '')) = ?", [strtolower(str_replace(' ', '', $user->nama))]);
                });
            }
        }

    	$list = $list->get()->toArray();

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
        return view('order::list_order', ['list' => $data_list, 'req' => $req, 'bulan' => $bulan, 'tahun' => $tahun]);
    }

    public function tolak($id, $alasan)
    {
        DB::beginTransaction();
        $post = [];
        $get_data = Order::with('details')->where('id', $id)->first();
        if (empty($get_data)) {
            return response()->json(['status' => false, 'messages' => 'Data tidak ditemukan']);
        }

        $user = Auth::user();

        $get_data->tanggal_reject = date('Y-m-d H:i:s');
        $get_data->rejected_by = $user->id;
        $get_data->rejected_text = $alasan;
        $get_data->update();
        if (!$get_data) {
            DB::rollback();
            return response()->json(['status' => false, 'messages' => 'Data gagal diupdate']);
        }

        $this->curl($get_data, 'batal', $post, $user, $alasan);
        DB::commit();
        return response()->json(['status' => true]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
    	$barang = Barang::with('stokBarangSatuan')->whereNull('deleted_at');
        

        if ($user->id_divisi == 13) {
            $barang = $barang->where('agen', 1);
        }

        if ($user->id_divisi == 23) {
            $barang = $barang->where('subagen', 1);
        }

        if ($user->id_divisi == 29) {
            $barang = $barang->where('corporate', 1);
        }

        $barang = $barang->get()->toArray();
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
            'distribusi_sales'  => $post['distribusi_sales'],
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
    			if (!empty($check_stok)) {
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
                            'id_ref_min_barang'     => $stok['id'],
                            'tanggal_barang'   => date('Y-m-d H:i:s'),
                            'created_at'       => date('Y-m-d H:i:s'),
                            'updated_at'       => date('Y-m-d H:i:s'),
                        ];

                        $update_stok = BarangHarga::where('id', $stok['id'])->update(['min_barang' => $total_minta + $stok['min_barang']]);
                        if (!$update_stok) {
                            DB::rollback();
                            return back()->withErrors(['Update jumlah barang stok gagal'])->withInput();
                        }

                        $total_pesan = $total_pesan - $total_minta;
                    }
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

        $this->curl($data, 'selesai', $post, $user);

    	DB::commit();
    	return back()->with(['success' => ['Approve data sukses']]);
    }

    public function curl($check, $keterangan, $post, $user, $alasan=null)
    {
        $curl = curl_init();

        $ket = 'Terima Kasih';
        $tanggal = $check->all;
        
        $staff = $user->nama;
        $wa = $user->wa;

        if ($keterangan == 'selesai') {
            $total = count($post['id_detail_order']);
            $ket = 'Pemesanan barang anda melalui website belanja-ga.id telah DITERIMA oleh admin GA\n\nDetail pemesanan\nTanggal: '.$tanggal.'\nTotal item: '.$total.'\nAdmin GA: '.$staff.'\nWA: '.$wa.'\n\nTerima Kasih\nMohon untuk tidak membalas chat ini';
        }

        if ($keterangan == 'batal') {
            $total = count($check['details']);

            $ket = 'Maaf, Pemesanan barang anda melalui website belanja-ga.id telah DITOLAK oleh admin GA\n\nDetail pemesanan\nTanggal: '.$tanggal.'\nTotal item: '.$total.'\nKeterangan: '.$alasan.'\nAdmin GA: '.$staff.'\nWA: '.$wa.'\n\nTerima Kasih\nMohon untuk tidak membalas chat ini';
        }

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://waping.es/api/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
              "token":"b43ed5e6373b9dbb1f0957bfee0d10ba",
              "source":6282148745792,
              "destination":'.$check->created_user->send.',
              "type":"text",
              "body":{
                  "text":"'.$ket.'"
              }
            }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return response()->json($response);
    }

    public function detail(Request $request, $id)
    {
    	$data = Order::with('approved_user.divisi', 'rejected_user.divisi', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $id)->first();
    	if (empty($data)) {
    		return back()->withErrors(['Data tidak ditemukan']);	
    	}

        $user = Auth()->user();
        $user_create = User::where('id', $data->created_by)->first();
        if (empty($user_create)) {
            return back()->withErrors(['User tidak ditemukan']);
        }

        $old = [];

        if ($user->id_divisi == 10) {
            $old = Order::with('approved_user.divisi', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereMonth('tanggal', date('m', strtotime($data->tanggal)))->where('id_divisi', $data->id_divisi);

            if ($data->id_divisi == 13 || $data->id_divisi == 23) {
                $old = $old->whereHas('created_user', function ($query) use ($user_create) {
                    return $query->whereRaw("lower(REPLACE(nama, ' ', '')) = ?", [strtolower(str_replace(' ', '', $user_create->nama))]);
                });
            }

            $old = $old->where('id', '!=', $data->id)->get();

            // return $old;
        }

    	// return $data;
    	$req = null;
    	if ($request->has('status')) {
    		$req = $request->get('status');
    	}

    	return view('order::detail_order', ['data' => $data, 'req' => $req, 'old' => $old]);
    }

    public function edit(Request $request, $id)
    {
        $data = Order::with('approved_user.divisi', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $id)->first();
        if (empty($data)) {
            return back()->withErrors(['Data tidak ditemukan']);    
        }

        if (isset($data->approved_by)) {
            return back()->withErrors(['Order telah di terima oleh admin, tidak dapat diedit kembali']);
        }

        if (isset($data->rejected_by)) {
            return back()->withErrors(['Order telah di tolak oleh admin, tidak dapat diedit kembali']);
        }
        
        $req = null;
        if ($request->has('status')) {
            $req = $request->get('status');
        }

        $barang = Barang::with('stokBarangSatuan')->whereNull('deleted_at');
        
        $user = Auth::user();
        if ($user->id_divisi == 13) {
            $barang = $barang->where('agen', 1);
        }

        if ($user->id_divisi == 23) {
            $barang = $barang->where('subagen', 1);
        }

        if ($user->id_divisi == 29) {
            $barang = $barang->where('corporate', 1);
        }

        $barang = $barang->get()->toArray();

        if (empty($barang)) {
            return redirect('barang/create')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
        }

        // return $data;
        return view('order::edit_order', ['data' => $data, 'req' => $req, 'barang' => $barang]);
    }

   //  public function updateApprove(Request $request, $id)
   //  {
   //  	DB::beginTransaction();
   //  	$post = $request->except('_token');
   //  	// return $post;
   //  	$user = Auth::user();
   //  	if (empty($user)) {
   //  		DB::rollback();
   //  		return back()->withErrors(['User tidak ditemukan']);
   //  	}

   //  	$data = Order::with('approved_user', 'created_user', 'details.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->where('id', $id)->first();
   //  	if (empty($data)) {
   //  		DB::rollback();
   //  		return back()->withErrors(['Data tidak ditemukan']);	
   //  	}

   //  	$data_barang_keluar = [
			// 'tanggal'     => date('Y-m-d H:i:s'),
			// 'id_divisi'   => $data['id_divisi'],
			// 'id_kategori' => $data['id_kategori'],
			// 'created_by'  => $user->id,
			// 'id_order'    => $data->id,
   //  	];

   //  	$create_barang_keluar = BarangKeluar::create($data_barang_keluar);
   //  	if (!$create_barang_keluar) {
   //  		DB::rollback();
   //  		return back()->withErrors(['Tambah barang keluar gagal'])->withInput();
   //  	}

   //  	if (isset($post['id_details'])) {
   //  		foreach ($post['id_details'] as $key => $value) {
   //  			$check_detail = OrderDetail::where('id', $value)->first();
   //  			if (empty($check_detail)) {
   //  				DB::rollback();
   //  				return back()->withErrors(['Data details order tidak ditemukan']);
   //  			}

   //  			$check_detail->jumlah_approve = $post['jumlah_approve'][$key];
   //  			$check_detail->update();
   //  			if (!$check_detail) {
   //  				DB::rollback();
   //  				return back()->withErrors(['Update data gagal']);
   //  			}

   //  			$data_detail = [
			// 		'id_barang_keluar' => $create_barang_keluar['id'],
			// 		'id_barang'       => $check_detail->id_barang,
			// 		'qty_barang'      => $post['jumlah_approve'][$key],
   //  			];

   //  			$create_detail = BarangKeluarDetail::create($data_detail);
   //  			if (!$create_detail) {
   //  				DB::rollback();
   //  				return back()->withErrors(['Tambah barang keluar detail gagal'])->withInput();
   //  			}
   //  		}
   //  	}
    		
   //  	$data->tanggal_approve = date('Y-m-d H:i:s');
   //  	$data->approved_by = $user->id;
   //  	$data->update();
   //  	if (!$data) {
   //  		DB::rollback();
			// return back()->withErrors(['Approve data gagal']);
   //  	}

   //  	DB::commit();
   //  	return redirect('order')->with(['success' => ['Approve data sukses']]);
   //  }

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
            'hp_user_request'   => $post['no_user'],
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
                    'id_order'   => $create_barang_order['id'],
                    'id_barang'  => $value,
                    'qty_barang' => $post['jumlah_barang'][$key],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
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

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $post = $request->except('_token');
        // return $post;
        $check_detail = Order::with('details')->where('id', $id)->first();

        if (empty($check_detail)) {
            DB::rollback();
            return back()->withErrors(['Data tidak ditemukan']);
        }

        if (isset($check_detail->approved_by)) {
            DB::rollback();
            return back()->withErrors(['Order telah di terima oleh admin, tidak dapat diedit kembali']);
        }

        $user = Auth::user();
        $check_detail->tanggal_update = date('Y-m-d H:i:s');
        $check_detail->no_order = 'TRX-'.date('md').'-'.$user->id.$user->id_divisi.date('His');
        $check_detail->nama_user_request = $post['nama_user'];
        $check_detail->updated_by = $user->id;
        $check_detail->update();
        if (!$check_detail) {
            DB::rollback();
            return back()->withErrors(['Data gagal diupdate']);
        }

        $data_id = [];
        if (!empty($check_detail->details)) {
            $data_id = array_column($check_detail->details->toArray(), 'id');
        }

        if (!empty($post['id_detail_order'])) {
            $data_insert = [];
            foreach ($post['id_detail_order'] as $key => $value) {
                $data_detail = [
                    'id_order'   => $id,
                    'id_barang'  => $post['id_barang'][$key],
                    'qty_barang' => $post['jumlah_barang'][$key],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                if (in_array($value, $data_id)) {
                    $update = OrderDetail::where('id', $value)->update($data_detail);
                    if (!$update) {
                        DB::rollback();
                        return back()->withErrors(['Update detail order gagal']);
                    }
                } else {
                    $data_insert[] = $data_detail;
                }
            }

            if (!empty($data_insert)) {
                $create_detail = OrderDetail::insert($data_insert);
                if (!$create_detail) {
                    DB::rollback();
                    return back()->withErrors(['Update baru detail order gagal'])->withInput();
                }
            }

            if (!empty($data_id)) {
                foreach ($data_id as $key => $value) {
                    if (!in_array($value, $post['id_detail_order'])) {
                        $delete = OrderDetail::where('id', $value)->delete();
                        if (!$delete) {
                            DB::rollback();
                            return back()->withErrors(['Update no data order gagal']);
                        }
                    }
                }
            }
        }

        DB::commit();
        return redirect('order')->with(['success' => ['Update data order berhasil']]);
    }

    public function delete(Request $request, $id)
    {
        DB::beginTransaction();
        $data = Order::where('id', $id)->first();
        if (isset($data['tanggal_approve'])) {
            DB::rollback();
            return back()->withErrors(['Order sudah diterima, tidak bisa dihapus']);
        }

        $delete = $data->delete();
        if (!$delete) {
            DB::rollback();
            return back()->withErrors(['Order gagal dihapus']);
        }

        DB::commit();
        return back()->with(['success' => ['Order berhasil dihapus']]);
    }
}
