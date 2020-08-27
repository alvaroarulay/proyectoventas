@extends('layouts.app')

@section('content')
<section class="material-half-bg ">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>Ventas</h1>
      </div>
      <div class="login-box">
        <form class="login-form was-validated" method="POST" action="{{ route('registrar-nuevo') }}">
          @csrf
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>Registro de Usuario</h3>
          <div class="row">
              <div class="col-md-6"><label class="control-label">Nombre y Apellido</label>
          <div class="form-group mb-3{{$errors->has('nombre' ? 'is-invalid' : '')}}">
          <input type="text" value="{{old('nombre')}}" name="nombre" id="nombre" class="form-control" placeholder="nombre" required autofocus>
                {!!$errors->first('nombre','<span class="invalid-feedback">:message</span>')!!}
          </div>
          <label class="control-label">C.I. o NIT</label>
          <div class="form-group mb-4{{$errors->has('nit' ? 'is-invalid' : '')}}">
          <input type="nit" name="nit" id="nit" class="form-control" placeholder="nit" required>
                {!!$errors->first('nit','<span class="invalid-feedback">:message</span>')!!}
          </div>
          <label class="control-label">Teléfono</label>
          <div class="form-group mb-4{{$errors->has('telefono' ? 'is-invalid' : '')}}">
          <input type="telefono" name="telefono" id="telefono" class="form-control" placeholder="telefono" required>
                {!!$errors->first('telefono','<span class="invalid-feedback">:message</span>')!!}
          </div></div>
              <div class="col-md-6"><label class="control-label">E-mail</label>
          <div class="form-group mb-4{{$errors->has('email' ? 'is-invalid' : '')}}">
          <input type="email" name="email" id="email" class="form-control" placeholder="email" required>
                {!!$errors->first('email','<span class="invalid-feedback">:message</span>')!!}
          </div>
          <label class="control-label">Nombre de Usuario</label>
          <div class="form-group mb-4{{$errors->has('usuario' ? 'is-invalid' : '')}}">
          <input type="usuario" name="usuario" id="usuario" class="form-control" placeholder="usuario" required>
                {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
          </div>
          <label class="control-label">Contraseña</label>
          <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
          <input type="password" name="password" id="password" class="form-control" placeholder="password" required>
                {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
          </div></div>
          </div>
          <div class="form-group btn-container">
              <div class="row">
                  <div class="col-md-6"><button class="btn btn-primary btn-block" type="submit"><i class="fa fa-save fa-lg fa-fw"></i>Registrar</button></div>
                  <div class="col-md-6"><a href="/" class="btn btn-danger btn-block"><i class="fa fa-trash fa-lg fa-fw"></i>Cancelar</a></div>   
              </div>
          </div>
        </form>
        
    </section>
@endsection
