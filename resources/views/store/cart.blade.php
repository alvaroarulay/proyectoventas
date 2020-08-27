@extends('layouts.app')
@section('content')
<main class="app-content">
<div class="container text-center">
    <div class="page-header">
        <h1 class="titulo">
            <i class="fa fa-shopping-cart"></i> Carrito de Compras
        </h1>
    </div>
    <hr>
<div class="table-cart">
    @if(count($cart))
    <p>
        <a href="{{route('cart-trash')}}" class="btn btn-danger">
            Vaciar Carrito <i class="fa fa-trash"></i>
        </a>
    </p>
    <div class="table-responsive tile">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Quitar</th>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr>
                    <td><img src="/img/articulos/{{$item['image']}}"> </td>
                    <td>{{$item['nombre']}}</td>
                    <td>{{number_format($item['precio'],2)}}</td>
                    <td>{{$item['stock']}}</td>
                    <td>
                        <input 
                            type="number"
                            min="1"
                            max="100"
                            value ="{{ $item['cantidad'] }}"
                            id="product_{{ $item['id'] }}"
                        >
                        <a href="#" 
                            class="btn btn-warning btn-sm btn-update-item"
                            data-href="/cart/update/{{$item['id']}}"
                            data-id ="{{ $item['id'] }}"
                            data-stock = "{{ $item['stock'] }}"
                        >
                            <i class="fa fa-refresh"></i>
                        </a>
                        </td>
                    <td>B$. {{number_format($item['precio'] * $item['cantidad'],2)}}</td>
                    <td>
                        <a href="{{route('cart-delete',$item['id'])}}" class="btn btn-danger">
                            <i class="fa fa-remove"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <h3>
            <label class="card mb-3 text-white bg-success">Total: B$. {{ number_format($total,2) }}</label>
        </h3>
    </div>
    @else
        <h3 class="label label warning">No Hay Productos en el Carrito :(</h3>
    @endif
    <hr>
    <a href="/" class="btn btn-primary">
        <i class="fa fa-chevron-circle-left"></i> Seguir Comprando
    </a>
    <a href="{{ route('order-detail') }}" class="btn btn-primary">
         Continuar <i class="fa fa-chevron-circle-right"></i>
    </a>
</div>
</main>
@endsection