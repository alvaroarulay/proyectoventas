<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comprobante;
use Carbon\Carbon;
use App\Http\Requests\ComprobanteStoreRequest;
use App\Http\Requests\ComprobanteUpdateRequest;

class ComprobanteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $comprobante=Comprobante::all();
        //$comprobante=Comprobante::select('id','nombre','num_autorizacion','llave','cantidad','estado','DATE_FORMAT(fecha_lim_emision, "%Y/%m/%d") as fecha');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $comprobante = Comprobante::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $comprobante = Comprobante::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        

        return ['pagination' => [
                'total'        => $comprobante->total(),
                'current_page' => $comprobante->currentPage(),
                'per_page'     => $comprobante->perPage(),
                'last_page'    => $comprobante->lastPage(),
                'from'         => $comprobante->firstItem(),
                'to'           => $comprobante->lastItem(),
            ],'comprobante' => $comprobante];
    }
    public function selectComprobante(Request $request){
        if (!$request->ajax()) return redirect('/');
        $comprobante = Comprobante::where('estado','=','1')->select('id','nombre','num_autorizacion','cantidad','fecha_lim_emision')->get();
        return ['comprobante' => $comprobante];
    }
    public function store(ComprobanteStoreRequest $request)
    {
        $comprobante = new Comprobante();
        $comprobante->nombre = $request->nombre;
        $comprobante->num_autorizacion = $request->autorizacion;
        $comprobante->llave = $request->llave;
        $comprobante->cantidad=$request->cantidad;
        $comprobante->fecha_lim_emision = $request->fechalim;
        $comprobante->save();
        return response()->json(['success' => '¡Registro Completado!'], 200);
    }
    public function update(ComprobanteUpdateRequest $request)
    {
        $comprobante = Comprobante::findOrFail($request->id);
        $comprobante->nombre = $request->nombre;
        $comprobante->num_autorizacion = $request->autorizacion;
        $comprobante->llave = $request->llave;
        $comprobante->cantidad = $request->cantidad;
        $comprobante->fecha_lim_emision = $request->fechalim;
        $comprobante->save();
        return response()->json(['success' => '¡Actualización Exitosa!'], 200);
    }
    public function desactivar(Request $request)
    {
        $comprobante = Comprobante::findOrFail($request->id);
        $comprobante->estado = '0';
        $comprobante->save();
    }
    public function activar(Request $request)
    {
        $comprobante = Comprobante::findOrFail($request->id);
        $comprobante->estado = '1';
        $comprobante->save();
    }
}
