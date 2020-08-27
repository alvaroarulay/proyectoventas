<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;
use App\Persona;
use App\Venta;

class StoreController extends Controller
{
    public function index(){
        $articulos = Articulo::where('stock','>',5)->get()->take(12);
        return view('store.index',compact('articulos'));
    }
    public function show($id){
        $articulo = Articulo::where('id',$id)->first();
        //dd($articulo);
        return view('store.show',compact('articulo'));
    }
    public function getDatosUser(){
        $persona = Persona::where('id','=',\Auth::user()->id)->first();
        $pedidos = Venta::select('id','tipo_comprobante','fecha_hora','total')->where('idusuario','=',\Auth::user()->id)->get();
        return view('store.datos',compact('persona','pedidos'));
    }
    public function updateUser(Request $request){
        $persona = Persona::findOrFail(\Auth::user()->id);
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
        return redirect()->route('home');
    }
    public function searchProduct(Request $request){
        $nombre = $request->nombre;
        $articulos = Articulo::where('nombre', 'like', '%'. $nombre . '%')->get();
        return view('store.index',compact('articulos'));
    }
    public function buscarProducto(Request $request){
        $nombre = $request->nombre;
        $articulos = Articulo::where('nombre', 'like', '%'. $nombre . '%')->get();
        return view('home',compact('articulos'));
    }
}
