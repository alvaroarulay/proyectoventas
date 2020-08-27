@extends('layouts.app')
@section('content')
<main class="app-content">
<br>
<br>
<br>
<br>
<div class="container text-center">
    <div class="page-header">
        <h1 class="titulo">
            <i class="fa fa-shopping-cart"></i> Detalle del Producto
        </h1>
    </div>
    <hr>
<div class="row">
    <div class="col-md-6 product-block">
        <img src="../img/articulos/{{$articulo->image}}" alt="{{$articulo->nombre}}" id="image">
    </div>
    <div class="col-md-6">
        <div class="product-block">
            <h4 class="card-header">{{$articulo->nombre}}</h4>
            <div class="card-body">
            <p class="card-text">{{$articulo->descripcion}}</p>
            </div>
            <p style="font-size:20px;"><span class="card mb-3 text-white bg-success"><strong>Precio: B$. {{number_format($articulo->precio,2)}}</strong></span></p>
            <a class="btn btn-warning btn-block" href="{{ route('cart-add',$articulo->id)}}"><i class="fa fa-cart-plus fa-2x"></i> lo Quiero</a>
        </div>
    </div>
    </div>
    <hr>
    <p>
        <a class="btn btn-primary" href="/"> <i class="fa fa-chevron-circle-left"></i> Regresar</a>
    </p>
</div>
</main>
<order></order>
@endsection