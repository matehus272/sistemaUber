<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Input;
use App\HTTP\Requests;

use App\Usuario;
use App\User;
use DB;
use Hash;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function __construct(Guard $auth)
    {
         $this->auth = $auth;
         $this->middleware('guest', ['except' => 'getLogout']);
    }

//login
       protected function getLogin()
    {
        return view('login');
    }
        public function postLogin(Request $request)
          {
            $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
             ]
            );
                $credentials = $request->only('email', 'password');
                   if ($this->auth->attempt($credentials, $request->has('remember')))
                         {
                               return redirect('aplicacion');
                         }else{
                             return redirect('/')->with('msjs',"email o contraseña incorrectos");
                        }
          }
//salir

protected function getLogout()
    {
          $this->auth->logout();
        return redirect('/');
    }

}
