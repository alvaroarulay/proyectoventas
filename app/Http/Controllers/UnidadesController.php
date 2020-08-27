<?php

namespace App\Http\Controllers;

use App\Unidades;
use Illuminate\Http\Request;
use App\Http\Requests\UnidadStoreRequest;
use App\Http\Requests\UnidadUpdateRequest;

class UnidadesController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidades=Unidades::all();
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $unidades = Unidades::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $unidades = Unidades::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $unidades->total(),
                'current_page' => $unidades->currentPage(),
                'per_page'     => $unidades->perPage(),
                'last_page'    => $unidades->lastPage(),
                'from'         => $unidades->firstItem(),
                'to'           => $unidades->lastItem(),
            ],
            'unidades' => $unidades
        ];
    }
    public function selectUnidad(Request $request){
        if (!$request->ajax()) return redirect('/');
        $unidades = Unidades::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre', 'asc')->get();
        return ['unidades' => $unidades];
    }
    public function store(UnidadStoreRequest $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidades = new Unidades();
        $unidades->nombre = $request->nombre;
        $unidades->descripcion = $request->descripcion;
        $unidades->condicion = '1';
        $unidades->save();
        return response()->json(['success' => '¡Registro Completado!'], 200);
    }
    public function update(UnidadUpdateRequest $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidades = Unidades::findOrFail($request->id);
        $unidades->nombre = $request->nombre;
        $unidades->descripcion = $request->descripcion;
        $unidades->condicion = '1';
        $unidades->save();
        return response()->json(['success' => '¡Actualización Exitosa!'], 200);
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidades = Unidades::findOrFail($request->id);
        $unidades->condicion = '0';
        $unidades->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidades = Unidades::findOrFail($request->id);
        $unidades->condicion = '1';
        $unidades->save();
    }
}
