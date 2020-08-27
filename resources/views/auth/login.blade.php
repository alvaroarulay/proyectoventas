@extends('layouts.app')
@if(\Session::has('message'))
    @include('store.partials.message')
@endif
@section('content')

<section class="material-half-bg ">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>Ventas</h1>
      </div>
      <div class="login-box">
        <form class="login-form was-validated" method="POST" action="{{ route('validar') }}">
          @csrf
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>Ingreso al Sistema</h3>
          <label class="control-label">Nombre de Usuario</label>
          <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
          <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="form-control" placeholder="Usuario" required autofocus>
                {!!$errors->first('usuario','<div class="bs-component">
                    <div class="alert alert-dismissible alert-danger">
                      <button class="close" type="button" data-dismiss="alert">×</button><strong>:message</strong>
                    </div>
                  </div>
                  ')!!}
          </div>
          <label class="control-label">Contraseña</label>
          <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
          </div>
          <div class="form-group">
            <div class="utility">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox"><span class="label-text">Recordarme</span>
                </label>
              </div>
              <hr>
                  <a class="semibold-text mb-2" href="" >
                      {{ __('Olvidaste tu password?') }}
                  </a>
            </div>
          </div>
          <div class="form-group btn-container">
            <div class="row">
              <div class="col-md-6"><button class="btn btn-primary btn-block" type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>Acceder</button></div>
              <div class="col-md-6"><a href="/" class="btn btn-danger btn-block"><i class="fa fa-trash fa-lg fa-fw"></i>Cancelar</a></div>
            </div>
          </div>
        </form>
        
    </section>
@endsection
