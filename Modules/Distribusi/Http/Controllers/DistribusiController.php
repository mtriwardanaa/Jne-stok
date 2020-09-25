<?php

namespace Modules\Distribusi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\BarangKeluar;
use Auth;

class DistribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
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

        $fitur = session()->get('fitur');
        $user = Auth::user();
        
        if (in_array(31, $fitur)) {
            $list = BarangKeluar::with('user', 'agen', 'userUpdate', 'details.stokBarang.stokBarangSatuan', 'detailStok.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->where('distribusi_sales', 1)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'DESC');
        } else {
            $list = BarangKeluar::with('user', 'agen', 'userUpdate', 'details.stokBarang.stokBarangSatuan', 'detailStok.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->where('distribusi_sales', 1)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'DESC');
        }

        if (!in_array(31, $fitur)) {
            $list = $list->where('id_divisi', $user->id_divisi);

            if (isset($user->id_agen_kategori)) {
                $list = $list->where('id_kategori', $user->id_agen_kategori);
            }
        }

        if ($user->level == 'general') {
            if ($user->id_divisi == 13) {
                $list = $list->whereHas('agen', function ($query) use ($user) {
                    return $query->whereRaw("lower(REPLACE(nama, ' ', '')) = ?", [strtolower(str_replace(' ', '', $user->nama))]);
                });
            }
        }

        $list = $list->get()->toArray();

        // return $list;
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

        return view('distribusi::list_distribusi', ['list' => $data_list, 'bulan' => $bulan, 'tahun' => $tahun]);
    }

    public function update(Request $request, $id)
    {
        $check_data = BarangKeluar::where('id', $id)->first();
        if (empty($check_data)) {
            return back()->withErrors(['Data tidak ditemukan']);
        }

        if (isset($check_data->tanggal_distribusi)) {
            return back()->withErrors(['Status telah diselesaikan, tidak bisa di update kembali']);
        }

        $user = Auth::user();

        $check_data->tanggal_distribusi = date('Y-m-d H:i:s');
        $check_data->user_distribusi = $user->id;
        $check_data->update();
        if (!$check_data) {
            return back()->withErrors(['Distribusi gagal diselesaikan']);
        }

        return back()->with(['success' => ['Distribusi berhasil diselesaikan']]);
    }

    public function detail(Request $request, $id)
    {
        $list = BarangKeluar::with('userDis', 'user', 'agen', 'userUpdate', 'invoice', 'details.stokBarang.stokBarangSatuan', 'detailStok.stokBarang.stokBarangSatuan', 'divisi', 'kategori')->whereNull('deleted_at')->where('id', $id)->orderBy('tanggal', 'DESC')->first();
        if (empty($list)) {
            return back()->withErrors(['Data tidak ditemukan']);
        }

        return view('distribusi::detail_distribusi', ['data' => $list]);
    }
}
