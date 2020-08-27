@extends('layouts.app')
@section('content')
<main class="app-content">
  <div class="row user">
    <div class="col-md-12">
      <div class="profile">
        <div class="info">
          <img class="user-img" src="/img/user.png">
          <h4>{{$persona->nombre}}</h4>
          <p>{{$persona->email}}</p>
        </div>
        <div class="cover-image"></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="tile p-0">
        <ul class="nav flex-column nav-tabs user-tabs">
          <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Mis Pedidos</a></li>
          <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Configuraciones</a></li>
        </ul>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content">
        <div class="tab-pane active" id="user-timeline">
            <div class="tile">
              <h3 class="tile-title">Pedidos y/o Compras Realizadas</h3>
              <table class="table table-striped table-hover table-bordered table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Comprobante</th>
                    <th>Total</th>
                    <th>Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pedidos as $p)
                    <tr>
                      <td>{{ $p->id}}</td>
                      <td>{{ \Carbon\Carbon::parse($p->fecha_hora)->format('d/m/Y') }}</td>
                      <td>{{ $p->tipo_comprobante}}</td>
                      <td>{{ $p->total}}</td>
                      <td>
                        <button class="btn btn-info btn-sm" onclick="pdf({{$p->id}})"><i class="fa fa-download"></i> </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
        </div>
        <div class="tab-pane fade" id="user-settings">
          <div class="tile user-settings">
            <h4 class="line-head">Configuraciones</h4>
            <form method="POST" class="form-horizontal" action="{{route('update-user')}}">
              @csrf
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Nombre(*)</label>
                    <div class="col-md-9"><input type="text" class="form-control" required value="{{$persona->nombre}}" name="nombre" disabled></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input" >Tipo documento</label>
                    <div class="col-md-9"><input type="text" class="form-control" required value="{{$persona->tipo_documento}}" name="nombre" disabled></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="number-input">Número documento</label>
                    <div class="col-md-9"><input type="number"class="form-control" name="num_documento" required value="{{$persona->num_documento}}" disabled></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                    <div class="col-md-9"><input type="text" class="form-control" name="direccion" required value="{{$persona->direccion}}"></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                    <div class="col-md-9"><input type="text" class="form-control" name="telefono" required value="{{$persona->telefono}}"></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                    <div class="col-md-9"><input type="email" name="email" class="form-control" required value="{{$persona->email}}"></div>
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Actualizar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
@section('scripts')
  <script>
    function pdf(id){
      window.open('http://ventas.local/venta/pdf/'+ id + ',' + '_blank');
    }
  </script>
@endsection