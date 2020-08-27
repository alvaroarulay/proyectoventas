@extends('layouts.app')
@section('content')

<main class="app-content">
<div class="container text-center">
    <div class="page-header">
        <h1 class="titulo"><i class="fa fa-shopping-cart"></i> Detalle del Pedido</h1>
    </div>
    <hr>
    <div class="page table-cart" >
        <div class="table-responsive">
            <h3>Datos del Usuario</h3>
            
            <table class="table table-striped table-hover table-bordered">
                <tr><td>Nombre: </td><td>{{Auth::user()->usuario}}</td> </tr>
            </table>
        </div>
        <div class="table-responsive">
            @csrf
            <h3>Datos del Pedido</h3>
            <table class="table table-striped table-hover table-bordered">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
                @foreach($cart as $item)
                    <tr>
                        <td>{{$item['nombre']}}</td>
                        <td>B$. {{number_format($item['precio'],2)}}</td>
                        <td>{{$item['cantidad']}}</td>
                        <td>B$. {{number_format($item['precio'] * $item['cantidad'])}}</td>
                    </tr>
                @endforeach
            </table>
            <hr>
            <h3><label class="card mb-3 text-white bg-success">Total: B$. {{ number_format($total,2) }}</label></h3>
            <hr>
            <a href="{{ route('cart-show') }}" class="btn btn-primary">
                <i class="fa fa-chevron-circle-left"></i> Regresar
            </a>
            <a href="{{ route('add-venta') }}" class="btn btn-warning">Procesar Pedido <i class="fa fa-chevron-circle-right"></i></a>
        </div>

    </div>
</div>
</main>
@endsection


