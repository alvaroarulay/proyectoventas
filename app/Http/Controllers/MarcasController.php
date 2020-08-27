<?php

namespace App\Http\Controllers;

use App\Marcas;
use Illuminate\Http\Request;
use App\Http\Requests\MarcaStoreRequest;
use App\Http\Requests\MarcaUpdateRequest;

class MarcasController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $marcas=Marcas::all();
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $marcas = Marcas::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $marcas = Marcas::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $marcas->total(),
                'current_page' => $marcas->currentPage(),
                'per_page'     => $marcas->perPage(),
                'last_page'    => $marcas->lastPage(),
                'from'         => $marcas->firstItem(),
                'to'           => $marcas->lastItem(),
            ],
            'marcas' => $marcas
        ];
    }
    public function selectMarca(Request $request){
        if (!$request->ajax()) return redirect('/');
        $marcas = Marcas::where('condicion','=','1')
        ->select('id','nombre','inicial')->orderBy('nombre', 'asc')->get();
        return ['marcas' => $marcas];
    }
    public function store(MarcaStoreRequest $request)
    {
        if (!$request->ajax()) return redirect('/');
        $marcas = new Marcas();
        $marcas->nombre = $request->nombre;
        $marcas->inicial = $request->inicial;
        $marcas->descripcion = $request->descripcion;
        $marcas->condicion = '1';
        $marcas->save();
        return response()->json(['success' => '¡Registro Completado!'], 200);
    }
    public function update(MarcaUpdateRequest $request)
    {
        if (!$request->ajax()) return redirect('/');
        $marcas = Marcas::findOrFail($request->id);
        $marcas->nombre = $request->nombre;
        $marcas->inicial = $request->inicial;
        $marcas->descripcion = $request->descripcion;
        $marcas->condicion = '1';
        $marcas->save();
        return response()->json(['success' => '¡Actualización Exitosa!'], 200);
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $marcas = Marcas::findOrFail($request->id);
        $marcas->condicion = '0';
        $marcas->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $marcas = Marcas::findOrFail($request->id);
        $marcas->condicion = '1';
        $marcas->save();
    }
}
