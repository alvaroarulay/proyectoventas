<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Persona;
use App\User;

class RegisterController extends Controller
{
    
    public function crearUsuario(){
        return view('auth.register');
    }
    public function store(Request $request){
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = 'C.I.';
        $persona->num_documento = $request->nit;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();

        $user = new User();
        $user->id = $persona->id;
        $user->idrol = 4;
        $user->usuario = $request->usuario;
        $user->password = bcrypt( $request->password);
        $user->condicion = '1';            
        $user->save();

        return view('auth.login');
    }
}
