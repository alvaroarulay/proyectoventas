<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Articulo;
use Carbon\Carbon;
use App\Venta;
use App\DetalleVenta;

class CartController extends Controller
{
    public function __construct(){if(!\Session::has('cart')) \Session::put('cart',array());}
    public function show(){
        //$sesion \Session::put('cart',['variable1','variable2']);
        //dd(session()->get('cart'));
        $cart = \Session::get('cart');
        $total = $this->total();
        return  view('store.cart',compact('cart', 'total'));
    }
    public function add($id){
        $articulo = Articulo::select('id','nombre','stock','precio','image')->where('id','=',$id)->first();
        //dd($articulo);
        //$articulo=0;
        $cart = \Session::get('cart');
        $newArt = ['id'=>$articulo->id,'nombre'=>$articulo->nombre,'stock'=>$articulo->stock,'precio'=>$articulo->precio,'image'=>$articulo->image,'cantidad'=>1];
        $cart[$id] = $newArt;
        \Session::put('cart',$cart);
        //dd( \Session::get('cart'));
        return redirect()->route('cart-show');
    }
    public function delete($id){
        $cart = \Session::get('cart');
        unset($cart[$id]);
        \Session::put('cart',$cart);
        return redirect()->route('cart-show');
    }
    public function update($id,$cantidad){
        $cart = \Session::get('cart');
        $cart[$id]['cantidad']=$cantidad;
        \Session::put('cart',$cart);
        return redirect()->route('cart-show');
    }
    public function trash(){
        \Session::forget('cart');
        return redirect()->route('cart-show');
        \Session::put('cart',$cart);
        return redirect()->route('cart-show');
    }
    private function total(){
        $cart = \Session::get('cart');
        $total = 0;
        foreach($cart as $item){
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }
    public function orderDetail(){
        if(count(\Session::get('cart')) <= 0) return redirect()->route('home');
        $cart = \Session::get('cart');
        $total = $this->total();
        return view('store.order-detail', compact('cart','total'));
    }
    public function addVenta(Request $request){
        $cart = \Session::get('cart');
        $total = $this->total();

        $mytime= Carbon::now();

        $venta = new Venta();
        $venta->idcliente = \Auth::user()->id;
        $venta->idusuario = \Auth::user()->id;
        $venta->idfactura = null;
        $venta->tipo_comprobante = 'PROFORMA';
        $venta->num_comprobante = 0;
        $venta->fecha_hora = $mytime->toDateString();
        $venta->impuesto = 0.13;
        $venta->total = $total;
        $venta->estado = 'Registrado';
        $venta->origen = 'Virtual';
        $venta->save();
        //Array de detalles
        //Recorro todos los elementos
        foreach($cart as $ep=>$det){
            $detalle = new DetalleVenta();
            $detalle->idventa = $venta->id;
            $detalle->idarticulo = $det['id'];
            $detalle->cantidad = $det['cantidad'];
            $detalle->precio = $det['precio'];
            $detalle->descuento = 0;         
            $detalle->save();

            $articulo = Articulo::findOrFail($detalle->idarticulo);
            $articulo->stock -= $det['cantidad'];
            $articulo->save();
        }
        
        \Session::forget('cart');
        return \Redirect::route('home')->with('message','Pedido Realizado de forma Correcta');
    }
}
