<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\EmpresaUpdateRequest;

use App\Empresa;

class EmpresaController extends Controller
{
  public function index(Request $request)
  {
      if (!$request->ajax()) return redirect('/');
      $empresa = Empresa::get();
      return $empresa;
  }
  public function selectIva(Request $request)
  {
      if (!$request->ajax()) return redirect('/');
      $empresa = Empresa::select('iva','mantenimiento')->first();
      return $empresa;
  }
  public function selectEmpresa()
  {
       $empresa = Empresa::all()->first();
      return $empresa;
  }
  public function update(EmpresaUpdateRequest $request)
      {
      if($request->imglocal){
        $image = $request->get('imglocal');
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        \Image::make($request->get('imglocal'))->save(public_path('img/').$name);
        try{
          $empresa = Empresa::findOrFail($request->id);
          $empresa->nombre_empresa = $request->nombre;
          $empresa->nit = $request->nit;
          $empresa->representante_legal = $request->representante_legal;
          $empresa->direccion = $request->direccion;
          $empresa->telefono = $request->telefono;
          $empresa->email = $request->email;
          $empresa->actividad = $request->actividad;
          $empresa->image_name = $name;
          $empresa->iva=$request->iva;
          $empresa->mantenimiento=$request->mantenimiento;
          $empresa->save();
          DB::commit();
        } catch (Exception $e){DB::rollBack();}
        return response()->json(['success' => '!Actualización Completa!'], 200);
      }
      else{
        try{
          $empresa = Empresa::findOrFail($request->id);
          $empresa->nombre_empresa = $request->nombre;
          $empresa->nit = $request->nit;
          $empresa->representante_legal = $request->representante_legal;
          $empresa->direccion = $request->direccion;
          $empresa->telefono = $request->telefono;
          $empresa->email = $request->email;
          $empresa->actividad = $request->actividad;
          $empresa->image_name = $request->image;
          $empresa->iva=$request->iva;
          $empresa->mantenimiento=$request->mantenimiento;
          $empresa->save();
          DB::commit();
        } catch (Exception $e){DB::rollBack();}
        return response()->json(['success' => '!Actualización Completa!'], 200);
      }
    }
}
