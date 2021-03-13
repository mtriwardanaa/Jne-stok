<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

use App\UserControl;
use Jenssegers\Agent\Agent;

class Login extends Controller
{
    public function login(Request $request)
    {
    	if (Auth::check()) {
    		return redirect('dashboard');
    	}

    	$method = $request->method();
		$post = $request->except('_token');

		if ($method == 'GET') {
            $mobile = 0;

            $agent = new Agent();
            
            if ($agent->isMobile()) {
                $mobile = 1;
            }

		    return view('login', ['mobile' => $mobile]);
		}

        $data_login = [
            'username' => strtoupper($post['username']),
            'password' => strtoupper($post['password']),
        ];

		if (Auth::attempt($data_login)) {
            $user = Auth::user();

            if ($user->active != 1) {
                Auth::logout();
                return redirect('login')->withErrors(['Status user anda tidak aktif. silahkan hubungi admin']);
            }

            $control = UserControl::where('id_user', $user->id)->get()->toArray();

            if (empty($control)) {
                Auth::logout();
                return redirect('login')->withErrors(['Akses tidak di izinkan']);
            }

            $user_feature = [];
            foreach ($control as $key => $value) {
                array_push($user_feature, $value['id_user_feature']);
            }

            Session::put('fitur', $user_feature);

			return redirect('dashboard');
		}

		return redirect('login')->withErrors(['Username atau Password salah']);
    }

    public function username() {
        return 'username';
    }
}