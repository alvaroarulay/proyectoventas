<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Factura;
use Carbon\Carbon;
use App\Venta;
use App\Comprobante;
use App\Empresa;
use App\Persona;
use App\CodeControl\ControlCode;

class FacturaController extends Controller
{
    public function store(Request $request){
        $mytime= Carbon::now();
        $anio=$mytime->isoFormat('YYYY');
        $mes=$mytime->isoFormat('MM');
        $dia=$mytime->isoFormat('DD');

        $nitcliente = Persona::select('num_documento')->where('id','=',$request->idcliente)->first();
        $llave = Comprobante::select('llave')->where('id','=',$request->idcomprobante)->first();
        $fecha = $anio.$mes.$dia;
        $fechaqr =$dia.'/'.$mes.'/'.$anio;

        $cod = new ControlCode;
        $codigoControl=$cod->generate($request->serie_comprobante,$request->num_comprobante,$nitcliente->num_documento,$fecha,$request->total,$llave->llave);
        $nitempresa = Empresa::select('nit')->where('id','=',1)->first();
        $codigoqr=$nitempresa->nit.'|'.$request->num_comprobante.'|'.$request->serie_comprobante.'|'.$fechaqr.'|'. $request->total.'|'. $request->total.'|'.$codigoControl.'|'.$nitcliente->num_documento.'|0.00|0.00|0.00|0.00';
        $qr=\QrCode::format('png')->size(200)->generate($codigoqr, public_path('img/'.$request->num_comprobante.'.png'));
        //guardando factura
        $factura = new Factura();
        $factura->codigo_control = $codigoControl;
        $factura->codigo_qr=$codigoqr;
        $factura->idcomprobantes=$request->idcomprobante;
        $factura->condicion=1;
        $factura->save();
        //actualizando comprobantes
        $comprobante = Comprobante::findOrFail($request->idcomprobante);
        $comprobante->cantidad -= 1;
        $comprobante->save(); 
        $comprobante2 = Comprobante::findOrFail($request->idcomprobante);
        if($comprobante2->cantidad==0 || $comprobante2->fecha_lim_emision < $mytime ){
            $comprobante2->estado=0;
            $comprobante2->save();
        }
        return ['id'=>$factura->id];
    }
    public function selectFactura(Request $request){
        if (!$request->ajax()) return redirect('/');
        if(Factura::max('id')==null){
            $factura=0;
        }else{
            $factura = Factura::max('id');
        }
        return ['factura' => $factura];
    }  
}
