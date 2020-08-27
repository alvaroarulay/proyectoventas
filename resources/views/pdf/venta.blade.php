<!DOCTYPE>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de venta</title>
</head>
   
<body>
    <style>
        @import url(http://fonts.googleapis.com/css?family=Bree+Serif);
        body, h1, h2, h3, h4, h5, h6{
            font-family: 'Bree Serif', serif;
        }
        body{
            width:19cm;
        }
        #logo{
            width: 195px;
            height: 275px;
        }
    
        #fact{
            text-align: center;
            width: 300px;
            height: 275px;
        }
        #imagen{
            align:center;
            width: 100px;
        }
        .nomEmpresa{
            font-family: Arial, sans-serif; 
            font-size: 20px;
        }
        #autorizacion{
            border-style: solid;
            border-radius: 15px;
            padding: 5px;
            font-size: 13px;
            line-height : 8px;
        }
        
        .datos{
            display:block;
            text-align: center;
            font-size: 15px;
            widows: 664px;
            height: 50px;
            line-height : 5px;
        }

        #facliente{
            display:block;
            padding: 10px;
            border-style: solid;
            border-radius: 10px;
            font-size: 12px;
            line-height : 8px;
        }

    
        #facarticulo{
            width: 674px;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
            border-style: solid;
            font-size: 12px;
        }
        .facdetalle{
                border-style: solid;
            }
        #titulos{
            background: #DAD9D8;
        }
        #facarticulo tr #codigo,#punitario,#subtotal,#detalle{
            padding: 10px;
            border-style: solid;
        }
        #codigo,#punitario{
            text-align: center;
            width: 50px;
        }
        #subtotal{
            text-align: center;
            width: 75px;
        }
        #detalle{
            text-align: left;
            width: 200px;
        }
        
        .facodigo .fapunitario{
            padding: 20px;
            border-style: solid;
            text-align: center;
            width: 71px;
            border-style: solid;
        }
        .fasubtotal{
            width: 85px;
            text-align: center;
            border-style: solid;
            padding: 20px;
            border-style: solid;
        }
        .fadetalle{
            text-align: left;
            padding-top: 20px;
            padding-left: 10px;
            padding-bottom: 20px;
            border-style: solid;
        }
        #fatotal{
            padding: 10px;
            border-style: solid;
            text-align: right;
            background: #DAD9D8;
            font-size: 14px;
            width: 529px;
        }
        #total{
            background: #DAD9D8;
            font-size: 14px;
            padding: 15px;
            text-align: center;
            width: 85px;
        }
        #literal{
            font-size:14px;
            border-style: solid;
        }
        #encabezado{
            display:block;
            height:5cm;
        }
        #encabezado #derecha{
            float:right;
            display:block;
        }
        #encabezado #izquierda{
            font-size:10px;
            text-align:center;
            float:left;
            line-height : 8px;
            display:block;
        }
        #nom_actividad{
            line-height : 5px;
        }
        .footable {
            width: 100%;
            font-size: 12px;
            color: #000;
            border: solid #000 1px;
            border-collapse: separate;
            border-spacing: 0;
        }
            
        .footable > tbody > tr > td, .footable > thead > tr > th {
            text-align:center;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
            
        .footable > thead > tr > th, .footable > thead > tr > td {
            text-align:center;
            background-color: #DAD9D8;
        }
    </style>
    <div id="encabezado">
        <div id="izquierda">
            <img src="img/{{$empresa->image_name}}" id="imagen">
            <br>
            <b class="nomEmpresa">{{$empresa->nombre_empresa}}</b><br>CASA MATRIZ<br>{{$empresa->direccion}}<br> 
            {{$empresa->telefono}} <br>El Alto - Bolivia<br>{{$empresa->email}}
        </div>
        <div id="derecha">
                @if($venta[0]->tipo_comprobante=='FACTURA')
                <table id="autorizacion">
                    <tr>
                        <td><b>NIT:</b></td>
                        <td>{{$empresa->nit}}</td>
                    </tr>
                    <tr>
                        <td><b>FACTURA Nº: </b></td>
                        <td>{{$venta[0]->num_comprobante}}</td>
                    </tr>
                    <tr>
                        <td><b>Nº AUTORIZACIÓN: </b></td>
                        <td>{{$factura->num_autorizacion}}</td>
                    </tr>
                </table>
                <br>
                <br>
                <div id="nom_actividad"> 
                    <b class="nomEmpresa">ORIGINAL</b>
                    <p>{{$empresa->actividad}}</p>
                </div>
            @endif
        </div>
    </div>
    <div class="datos">
        <p><b class="nomEmpresa">{{$venta[0]->tipo_comprobante}}</b><br></p>
        @if($venta[0]->tipo_comprobante=='FACTURA')
        @endif
        @if($venta[0]->tipo_comprobante=='RECIBO')
            <p>Nº {{$venta[0]->num_comprobante}}</p>
        @endif
    </div>
    @foreach ($venta as $v)
        <div id="facliente">
            <table>
                <tr>
                    <td><b>Lugar y Fecha: </b></td>
                    <td>La Paz, {{ \Carbon\Carbon::parse($v->fecha_hora)->format('d/m/Y')}}</td>
                </tr>
                <tr>
                    <td><b>Señor(es): </b></td>
                    <td>{{$v->nombre}}</td>
                    <td style="text-align: center;"><b>NIT/CI: </b></td>
                    <td>{{$v->num_documento}}</td>
                </tr>
            </table>
        </div>
    @endforeach
    <br>
        <div>
            <table class="footable">
                <thead>
                    <tr>
                        <td style="width:10%">CANTIDAD</td>
                        <td>DETALLE</td>
                        <td>P. UNITARIO</td>
                        <td>SUB-TOTAL</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $det)
                    <tr>
                        <td>{{$det->cantidad}}</td>
                        <td>{{$det->articulo}}</td>
                        <td>{{$det->precio}}</td>
                        <td>{{number_format($det->cantidad*$det->precio-$det->descuento,2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr id="fatotal">
                        <td colspan="3" ><b>TOTAL </b></td>
                        <td id="total"><b>Bs. {{$venta[0]->total}}</b></td>
                    </tr>
                    <tr id="literal">
                        <td colspan="4" style="height: 30px;"> <b>&nbsp;&nbsp;SON: </b>{{$literal}} BOLIVIANOS</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <br>
    <br>
    @if($venta[0]->tipo_comprobante=='FACTURA')
        <table style="width: 100%; font-size:12px">
        <tr>
            <td style="text-align: left; width: 75%;"><b>CODIGO DE CONTROL: </b>{{$factura->codigo_control}}</td>
            <td rowspan="2">
                <img src="img/{{$factura->id}}.png" style="width:4cm">
            </td>
        </tr>
        <tr>
            <td style="text-align: left; width: 75%;"><b>FECHA LIMITE DE EIMISIÓN: </b>{{ \Carbon\Carbon::parse($factura->fecha_lim_emision)->format('d/m/Y')}}</td>
        </tr>
        
        <tr style="width: 100%;">
            <td colspan="4" style="text-align: center;"><b>"Esta factura contribuye al desarrollo del País, el uso ilicito de ésta será sancionado de acuerdo a ley"</b><br>ley Nro. 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad.</td>
        </tr>
        </table>
    @endif
    </body>
</html>
