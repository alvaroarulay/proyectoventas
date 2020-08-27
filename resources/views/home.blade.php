@extends('layouts.app')
@if(\Session::has('message'))
    @include('store.partials.message')
@endif
@section('content')
@if(Auth::check())
            @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>
            <template v-if="menu==1">
                <comprobante></comprobante>
            </template>
            <template v-if="menu==2">
                <empresa></empresa>
            </template>
            <template v-if="menu==3">
                <categoria></categoria>
            </template>

            <template v-if="menu==4">
                <articulo></articulo>
            </template>

            <template v-if="menu==5">
                <indexmarcas></indexmarcas>
            </template>

            <template v-if="menu==6">
                <indexunidades></indexunidades>
            </template>

            <template v-if="menu==7">
                <ingreso></ingreso>
            </template>

            <template v-if="menu==8">
                <proveedor></proveedor>
            </template>

            <template v-if="menu==9">
                <venta></venta>
            </template>

            <template v-if="menu==10">
                <indexclientes></indexclientes>
            </template>

            <template v-if="menu==11">
                <user></user>
            </template>

            <template v-if="menu==12">
                <rol></rol>
            </template>

            <template v-if="menu==13">
                <reporte></reporte>
            </template>
            <template v-if="menu==15">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==16">
                <h1>Acerca de</h1>
            </template>
            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <vendedor></vendedor>
            </template>
            <template v-if="menu==9">
                <venta></venta>
            </template>
            <template v-if="menu==10">
                <indexclientes></indexclientes>
            </template>
            <template v-if="menu==13">
                <h1>Reporte de ventas</h1>
            </template>

            <template v-if="menu==14">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==15">
                <h1>Acerca de</h1>
            </template>
            @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>
            <template v-if="menu==3">
                <categoria></categoria>
            </template>

            <template v-if="menu==4">
                <articulo></articulo>
            </template>

            <template v-if="menu==5">
                <indexmarcas></indexmarcas>
            </template>

            <template v-if="menu==6">
                <indexunidades></indexunidades>
            </template>

            <template v-if="menu==7">
                <ingreso></ingreso>
            </template>

            <template v-if="menu==8">
                <proveedor></proveedor>
            </template>
            <template v-if="menu==11">
                <h1>Reporte ingresos</h1>
            </template>
            <template v-if="menu==13">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==14">
                <h1>Acerca de</h1>
            </template>
            @elseif (Auth::user()->idrol == 4)
            <template v-if="menu==13">
            </template>

            <template v-if="menu==14">
                <h1>Acerca de</h1>
            </template>
            <main class="app-content">
            <div class="container text-center">
                <h1 class="titulo">Catalogo de Productos</h1>
                <hr>
                <form action="{{route('searchproducto')}}" method="get" class="d-flex">
                @csrf
                    <input type="text" name="nombre" placeholder="Encuentra tu articulo" class="form-control is-valid">
                    <br>
                    <button type="submit" class="btn btn-warning btn-large"><i class="fa fa-search"></i></button>
                </form>
                <hr>
                <div id="productos">
                    @foreach($articulos as $p)
                    <div class="product white-panel">
                        <h3>{{$p->nombre}}</h3><hr>
                        <img id="image" src="/img/articulos/{{$p->image}}" alt="">
                        <div class="product-info card-panel">
                            <p>{{$p->descripcion}}</p>
                            <p style="font-size:15px;"><span class="card mb-3 text-white bg-success"><strong>Precio: B$. {{number_format($p->precio,2)}}</strong></span></p>
                            <p>
                                <button class="btn btn-warning" onclick="carrito({{$p['id']}})"><i class="fa fa-cart-plus"></i> La Quiero</button>
                                <a href="{{ route('product-detail',$p->id)}}" class="btn btn-primary"><i class="fa fa-chevron-circle-right"></i> Leer más</a>
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            </main>
            @endif
    @endif
    @endsection
@section('scripts')
<script>
      function carrito(id){
        axios.get(`/cart/add/${id}`).then((response)=>{
           
           swal.fire({
             title: "Producto Agregado al Carrito",
             showConfirmButton: false,
             showCancelButton: false,
               icon: "success",
             timer:1100
             });
       })
      }
    </script>
@endsection

