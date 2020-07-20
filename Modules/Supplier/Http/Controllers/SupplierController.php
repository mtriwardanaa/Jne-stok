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
}
