<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Articulo;
use App\Empresa;
use App\Http\Requests\ArticuloStoreRequest;
use App\Http\Requests\ArticuloUpdateRequest;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->join('marcas','articulos.idmarca','=','marcas.id')
            ->join('unidades','articulos.idunidad','=','unidades.id')
            ->select('articulos.id','articulos.idcategoria','articulos.idmarca','articulos.idunidad','articulos.codigo','articulos.nombre','articulos.precio','articulos.stock','categorias.nombre as nom_categoria','marcas.nombre as nom_marca','unidades.nombre as nom_unidad','articulos.image','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id', 'desc')->paginate(15);
        }
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->join('marcas','articulos.idmarca','=','marcas.id')
            ->join('unidades','articulos.idunidad','=','unidades.id')
            ->select('articulos.id','articulos.idcategoria','articulos.idmarca','articulos.idunidad','articulos.codigo','articulos.nombre','articulos.precio','articulos.stock','categorias.nombre as nom_categoria','marcas.nombre as nom_marca','unidades.nombre as nom_unidad','articulos.image','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id', 'desc')->paginate(15);
        }
        
        return [
            'pagination' => [
                'total'        => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page'     => $articulos->perPage(),
                'last_page'    => $articulos->lastPage(),
                'from'         => $articulos->firstItem(),
                'to'           => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }

    public function listarArticulo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $empresa=Empresa::select('iva','mantenimiento')->first();
        $totalArticulos=Articulo::first()->count();
        if ($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','articulos.precio','articulos.stock','categorias.nombre as nombre_categoria','articulos.precio','articulos.stock','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id', 'desc')->paginate(15);
        }
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->join('marcas','articulos.idmarca','=','marcas.id')
            ->join('unidades','articulos.idunidad','=','unidades.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','articulos.precio','articulos.stock','categorias.nombre as nombre_categoria','articulos.precio','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id', 'desc')->paginate(15);
        }
        return ['articulos' => $articulos,'empresa'=>$empresa,'totalArticulos'=>$totalArticulos];
    }
    public function listarPdf(){
        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->join('marcas','articulos.idmarca','=','marcas.id')
            ->join('unidades','articulos.idunidad','=','unidades.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','articulos.precio','articulos.stock','categorias.nombre as nombre_categoria','marcas.nombre as nombre_marcas','unidades.nombre as nombre_unidades','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id', 'desc')->get();
        $count=Articulo::count();
        $pdf=\PDF::loadView('pdf.articulospdf',['articulos'=>$articulos,'cont'=>$count]);
        return $pdf->download('articulos.pdf');
    }
    public function listarArticuloVenta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        

        return ['articulos' => $articulos];
    }

    public function buscarArticulo(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=', $filtro)->select('id','nombre','stock','precio')->orderBy('nombre', 'asc')->first();
        if($articulos==null){
           $cantidad = 0; 
        }else{
            $cantidad = 1;
        }
        return ['articulos' => $articulos,'cantidad'=>$cantidad];
    }

    public function buscarArticuloVenta(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=', $filtro)
        ->select('id','nombre','stock','precio')
        ->where('stock','>','0')
        ->orderBy('nombre', 'asc')
        ->take(1)->get();

        return ['articulos' => $articulos];
    }
    
    public function store(ArticuloStoreRequest $request)
    {
        //if (!$request->ajax()) return redirect('/');
        if($request->imglocal){
            $image = $request->get('imglocal');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('imglocal'))->save(public_path('img/articulos/').$name);
            try{
                $articulo = new Articulo();
                $articulo->idcategoria = $request->idcategoria;
                $articulo->idmarca=$request->idmarca;
                $articulo->idunidad=$request->idunidad;
                $articulo->codigo = $request->codigo;
                $articulo->nombre = $request->nombre;
                $articulo->image=$name;
                $articulo->descripcion = $request->descripcion;
                $articulo->condicion = '1';
                $articulo->save();
              DB::commit();
              return response()->json(['success' => '¡Registro Completado!'], 200);
            } catch (Exception $e){DB::rollBack();}
            
          }
        
    }
    public function update(ArticuloUpdateRequest $request)
    {
        //if (!$request->ajax()) return redirect('/');
        if($request->imglocal){
            $image = $request->get('imglocal');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('imglocal'))->save(public_path('img/articulos/').$name);
            try{
                $articulo = Articulo::findOrFail($request->id);
                $articulo->idcategoria = $request->idcategoria;
                $articulo->idmarca=$request->idmarca;
                $articulo->idunidad=$request->idunidad;
                $articulo->codigo = $request->codigo;
                $articulo->nombre = $request->nombre;
                $articulo->image=$name;
                $articulo->descripcion = $request->descripcion;
                $articulo->condicion = '1';
                $articulo->save();
              DB::commit();
              return response()->json(['success' => '¡Actualización Exitosa!'], 200);
            } catch (Exception $e){DB::rollBack();}
            
          }
        else{
            $articulo = Articulo::findOrFail($request->id);
            $articulo->idcategoria = $request->idcategoria;
            $articulo->idmarca=$request->idmarca;
            $articulo->idunidad=$request->idunidad;
            $articulo->codigo = $request->codigo;
            $articulo->nombre = $request->nombre;
            $articulo->descripcion = $request->descripcion;
            $articulo->condicion = '1';
            $articulo->save();
            return response()->json(['success' => '¡Actualización Exitosa!'], 200);
        }
       
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }
    public function cantidadArticulos(){
        $cantidad=Articulo::all();
        $c=$cantidad->count();
        return $c;
    }
    
}
