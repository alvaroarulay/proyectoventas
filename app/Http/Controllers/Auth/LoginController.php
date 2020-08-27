<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }
    public function validar(Request $request){
        $this->validateLogin($request);
        if(Auth::attempt(['usuario'=>$request->usuario,'password'=>$request->password,'condicion'=>1]) ){
               return redirect()->route('home');
        }
        return back()->withErros(['usuario'=>trans('auth.failed')])->withInput(request(['usuario']));
    }
    public function validateLogin(Request $request){
        $this->validate($request,['usuario'=>'required|string','password'=>'required|string']);
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}