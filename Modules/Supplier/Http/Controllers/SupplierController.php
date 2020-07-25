<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Supplier;
use App\User;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list()
    {
    	$supp = Supplier::get()->toArray();
        return view('supplier::list_supplier', ['supp' => $supp]);
    }

    public function create()
    {
    	return view('supplier::create_supplier');
    }

    public function store(Request $request)
    {
    	$post = $request->except('_token');

    	$create = Supplier::create(['nama_supplier' => $post['nama_supplier']]);
    	if (!$create) {
    		return back()->withErrors(['Simpan data gagal'])->withInput();
    	}

    	return redirect('supplier')->with(['success' => ['Simpan data berhasil']]);
    }

    public function edit(Request $request, $id)
    {
    	$check = Supplier::where('id', $id)->first();
    	if (empty($check)) {
    		return back()->withErrors(['Datq tidak ditemukan'])->withInput();
    	}

    	return view('supplier::edit_supplier', ['data' => $check]);
    }

    public function update(Request $request, $id)
    {
    	$post = $request->except('_token');

    	$update = Supplier::where('id', $id)->update(['nama_supplier' => $post['nama_supplier']]);
    	if (!$update) {
    		return back()->withErrors(['Update data gagal'])->withInput();
    	}

    	return redirect('supplier')->with(['success' => ['Update data berhasil']]);
    }

    public function delete(Request $request, $id)
    {
    	$check_barang = Supplier::where('id', $id)->first();
    	if (empty($check_barang)) {
    		return back()->withErrors(['Supplier tidak ditemukan'])->withInput();
    	}

    	$check_barang->delete();
    	if (!$check_barang) {
    		return back()->withErrors(['Hapus data gagal'])->withInput();
    	}

    	return redirect('supplier')->with(['success' => ['Hapus supplier berhasil']]);
    }
}
