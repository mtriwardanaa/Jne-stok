<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\User;

class UserController extends Controller
{
    public function list()
    {
    	$user = User::with('divisi', 'kategori')->get()->toArray();
        return view('user::list_user', ['user' => $user]);
    }
}
