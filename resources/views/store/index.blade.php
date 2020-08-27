@extends('layouts.app')
@section('content')
@include('store.partials.slider')
<main>
<div class="container text-center">
    <h1 class="titulo">Catalogo de Productos</h1>
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

