<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VentasStoreRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Venta;
use App\DetalleVenta;
use App\Articulo;
use App\Comprobante;
use App\Empresa;
use App\Factura;
use App\Persona;
use App\User;
use App\NumeroLetras\NumeroLetras;
use App\CodeControl\ControlCode;
use PDF;
class VentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $q=Venta::select('num_comprobante')->where('tipo_comprobante','=','PROFORMA');
        $role = User::select('idrol')->where('id','=',\Auth::user()->id)->first();
        if($q->max('num_comprobante')==null){
            $recibo=0;
        }else{
             $recibo=$q->max('num_comprobante');
        }
        if ($buscar==''){
            if($role->idrol == 1){
                 $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
                'ventas.estado','personas.nombre','users.usuario')
                ->orderBy('ventas.id', 'desc')->paginate(10);
            }
            else{
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
                'ventas.estado','personas.nombre','users.usuario')
                ->where('ventas.idusuario','=',\Auth::user()->id)
                ->orderBy('ventas.id', 'desc')->paginate(10);
            }
           
        }
        else{
            if($role->idrol == 1){
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
                    'ventas.estado','personas.nombre','users.usuario')
                    ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('ventas.id', 'desc')->paginate(10);
            }
            else{
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
                    'ventas.estado','personas.nombre','users.usuario')
                    ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')->where('ventas.idusuario','=',\Auth::user()->id)
                    ->orderBy('ventas.id', 'desc')->paginate(10);
            }
            
        };
        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas,'recibo'=>$recibo
        ];
    }
    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select(['ventas.id','ventas.tipo_comprobante',
        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
        'ventas.estado','personas.nombre','users.usuario','personas.id as pid'])
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();
        
        return ['venta' => $venta];
    }
    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();
        
        return ['detalles' => $detalles];
    }
    public function obtenerFactura(){
        $comprobante = Comprobante::where('estado','=','1')->where('nombre','=','FACTURA')->select('id','nombre','num_autorizacion','cantidad','fecha_lim_emision')->get();
        return ['comprobante' => $comprobante];
    }
    public function pdf(Request $request,$id){
        $empresa=Empresa::first();
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado','personas.nombre','users.usuario','personas.num_documento')
                    ->where('ventas.id','=',$id)->orderBy('ventas.id', 'desc')->take(1)->get();
        $detalles=DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','articulos.nombre as articulo')->where('detalle_ventas.idventa','=',$id)->orderBy('detalle_ventas.id','desc')->get();
        $numventa=Venta::select('num_comprobante')->where('id','=',$id)->get();
        $total = Venta::select('total')->where('id','=',$id)->first();
        $letras = new NumeroLetras();
        $literal=$letras->convertir($total->total,'Bolivianos',true);
        $factura=Factura::join('comprobantes','facturas.idcomprobantes','=','comprobantes.id')->select('facturas.id','comprobantes.fecha_lim_emision','comprobantes.num_autorizacion','facturas.codigo_control')->where('facturas.id','=',$numventa[0]->num_comprobante)->first();
        $pdf=\PDF::loadView('pdf.venta',['factura'=>$factura,'venta'=>$venta,'detalles'=>$detalles,'literal'=>$literal,'idventa'=>$request->id,'empresa'=>$empresa]);
        return $pdf->download('venta'.$numventa[0]->num_comprobante.'.pdf');                       
    }
    public function reportePdf(){
        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado','personas.nombre','users.usuario','personas.num_documento')
                    ->orderBy('ventas.id', 'desc')->get();
        $count=Venta::count();
        $pdf=\PDF::loadView('pdf.ventaspdf',['ventas'=>$ventas,'cont'=>$count]);
        return $pdf->download('ventas.pdf');
    }
    public function diarioPdf(){
        $mytime= Carbon::now('America/La_Paz');
        $total=0;
        $totala=0;
        $totalp=0;
        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total','ventas.estado','personas.nombre','users.usuario','personas.num_documento')
                    ->where('fecha_hora','=',$mytime->toDateString())->orderBy('ventas.id', 'desc')->get();
        $q = Venta::select('total')->where('fecha_hora','=',$mytime->toDateString())->get();

        $count=$q->count();
        for($i=0;$i<$count;$i++){
            if($ventas[$i]->estado=='Registrado'){$total +=$q[$i]->total;}
            if($ventas[$i]->estado=='Anulado'){$totala +=$q[$i]->total;}
            if($ventas[$i]->estado=='Pendiente'){$totalp +=$q[$i]->total;}
            }
        $pdf=\PDF::loadView('pdf.diariopdf',['ventas'=>$ventas,'cont'=>$count,'total'=>$total,'totala'=>$totala,'totalp'=>$totalp]);
        return $pdf->download('diario.pdf');
    }
    public function store(VentasStoreRequest $request)
    {
        
        $mytime= Carbon::now();
        $venta = new Venta();
        $venta->idcliente = $request->idcliente;
        $venta->idusuario = \Auth::user()->id;
        $venta->idfactura = $request->idfactura;
        $venta->tipo_comprobante = $request->tipo_comprobante;
        $venta->num_comprobante = $request->num_comprobante;
        $venta->fecha_hora = $mytime->toDateString();
        $venta->impuesto = $request->impuesto;
        $venta->total = $request->total;
        $venta->estado = $request->estado;
        $venta->origen = 'Presencial';
        $venta->save();
        $detalles = $request->data;
        //Array de detalles
        //Recorro todos los elementos
        foreach($detalles as $ep=>$det){
            $detalle = new DetalleVenta();
            $detalle->idventa = $venta->id;
            $detalle->idarticulo = $det['idarticulo'];
            $detalle->cantidad = $det['cantidad'];
            $detalle->precio = $det['precio'];
            $detalle->descuento = $det['descuento'];         
            $detalle->save();

            $articulo = Articulo::findOrFail($det['idarticulo']);
            $articulo->stock -= $det['cantidad'];
            $articulo->save();
        }
        return ['id'=>$venta->id]; 
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->idfactura = $request->idfactura;
        $venta->idusuario = \Auth::user()->id;
        $venta->tipo_comprobante = $request->tipo_comprobante;
        $venta->num_comprobante = $request->num_comprobante;
        $venta->estado = 'Registrado';
        $venta->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');   
        $detalles = DetalleVenta::where('idventa','=',$request->id)->get();

        foreach($detalles as $ep=>$det){

            $articulo = Articulo::findOrFail($det['idarticulo']);
            $articulo->stock += $det['cantidad'];
            $articulo->save();
        }
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();

    }
    public function numeroVentas(){
        $v = Venta::all();
        $venta = $v->count();
        return $venta;
    }
    public function charVentas(Request $request){
        $dia=date('d');
        $mes=$request->mes;
        $anio=date('Y');
        $arrayPresencial=[];
        $arrayVirtual=[];
        $ventasDia = Venta::join('personas','ventas.idcliente','=','personas.id')->select('personas.nombre','ventas.total')->orderBy('ventas.total','asc')->whereDay('ventas.fecha_hora',$dia)->where('ventas.tipo_comprobante','=','FACTURA')->where('estado','=','Registrado')->get();
        $ventasVendedores = DB::table('personas as p')->join('users as u','p.id','=','u.id')->join('ventas as v','v.idusuario','=','u.id')->select('p.nombre',DB::raw('SUM(v.total) as total'))->groupBy('p.nombre')->orderBy('total','desc')->where('u.idrol','=','2')->whereMonth('v.fecha_hora', '=', $mes)->whereYear('v.fecha_hora','=',$anio)->get();
        if($mes == '01' || $mes == '03' || $mes == '05' || $mes == '07' || $mes == '08' || $mes == '10' || $mes == '12'){
            for ($i=1;$i<=31;$i++){
                $q=DB::table('ventas as v')->select(DB::raw('SUM(v.total) as total'))->whereDay('v.fecha_hora',$i)->where('v.tipo_comprobante','=','FACTURA')->where('v.estado','=','Registrado')->where('v.origen','=','Virtual')->whereMonth('v.fecha_hora', '=', $mes)->first();
                $z=DB::table('ventas as v')->select(DB::raw('SUM(v.total) as total'))->whereDay('v.fecha_hora',$i)->where('v.tipo_comprobante','=','FACTURA')->where('v.estado','=','Registrado')->where('v.origen','=','Presencial')->whereMonth('v.fecha_hora', '=', $mes)->first();
                if($q->total==null){
                    $arrayPresencial[]=['dia'=>$i,'total'=>0];
                    $arrayVirtual[]=['dia'=>$i,'total'=>0];
                }else{
                    $arrayPresencial[]=['dia'=>$i,'total'=>$q->total];
                    $arrayVirtual[]=['dia'=>$i,'total'=>$z->total];
                }
                }
        }else{
            if($mes == '04' || $mes == '06' || $mes == '09' || $mes == '11' || $mes == '12'){
                for ($i=1;$i<=30;$i++){
                    $q=DB::table('ventas as v')->select(DB::raw('SUM(v.total) as total'))->whereDay('v.fecha_hora',$i)->where('v.tipo_comprobante','=','FACTURA')->where('v.estado','=','Registrado')->where('v.origen','=','Virtual')->whereMonth('v.fecha_hora', '=', $mes)->first();
                    $z=DB::table('ventas as v')->select(DB::raw('SUM(v.total) as total'))->whereDay('v.fecha_hora',$i)->where('v.tipo_comprobante','=','FACTURA')->where('v.estado','=','Registrado')->where('v.origen','=','Presencial')->whereMonth('v.fecha_hora', '=', $mes)->first();
                    if($q->total==null){
                        $arrayPresencial[]=['dia'=>$i,'total'=>0];
                        $arrayVirtual[]=['dia'=>$i,'total'=>0];
                    }else{
                        $arrayPresencial[]=['dia'=>$i,'total'=>$q->total];
                        $arrayVirtual[]=['dia'=>$i,'total'=>$z->total];
                    }
                }
            }else{
                if($mes == '02'){
                    for ($i=1;$i<=29;$i++){
                        $q=DB::table('ventas as v')->select(DB::raw('SUM(v.total) as total'))->whereDay('v.fecha_hora',$i)->where('v.tipo_comprobante','=','FACTURA')->where('v.estado','=','Registrado')->where('v.origen','=','Virtual')->whereMonth('v.fecha_hora', '=', $mes)->first();
                        $z=DB::table('ventas as v')->select(DB::raw('SUM(v.total) as total'))->whereDay('v.fecha_hora',$i)->where('v.tipo_comprobante','=','FACTURA')->where('v.estado','=','Registrado')->where('v.origen','=','Presencial')->whereMonth('v.fecha_hora', '=', $mes)->first();
                        if($q->total==null){
                            $arrayPresencial[]=['dia'=>$i,'total'=>0];
                            $arrayVirtual[]=['dia'=>$i,'total'=>0];
                        }else{
                            $arrayPresencial[]=['dia'=>$i,'total'=>$q->total];
                            $arrayVirtual[]=['dia'=>$i,'total'=>$z->total];
                        }
                        }
                    }
            }
            
        }
        

        return['vPresencial'=>$arrayPresencial,'vVirtual'=>$arrayVirtual,'vv'=>$ventasVendedores];
    }
}
