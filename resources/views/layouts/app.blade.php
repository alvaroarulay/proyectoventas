<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ventas') }}</title>
    <!-- Styles -->
    
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
  </head>
  <body class="app sidebar-mini">
  <div id="app">
  @guest 
  <header class="app-header">
    <a class="app-header__logo" href="/">Vali</a>
     <ul class="app-nav">
     <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('cart-show')}}"><i class="fa fa-shopping-cart"></i> </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white" href="/nosotros">{{ __('Conocenos') }} </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white" href="/contacto">{{ __('Contacto') }} </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('registrar')}}">{{ __('Registrar') }} </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white" href="/login">{{ __('Iniciar Sesión ') }} </a>
      </li>
    </ul> 
    </header>
  @endguest
  @auth
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="/">Ventas</a>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
          <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
              <li><a class="dropdown-item" href="#"><i class="fa fa-user fa-lg"></i> {{ Auth::user()->usuario }} </a></li>
              <li><a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i> {{ __('Salir') }}</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
              </li>
            </ul>
          </li> 
        </ul>
    </header>
    @if (Auth::user()->idrol == 1)
          @include('plantilla.sidebaradministrador')
      @elseif (Auth::user()->idrol == 2)
          @include('plantilla.sidebarvendedor')
      @elseif (Auth::user()->idrol == 3)
          @include('plantilla.sidebaralmacenero')
      @elseif (Auth::user()->idrol == 4)
          @include('plantilla.sidebarcliente')
      @else
    @endif
   @endauth
        <!-- Contenido Principal -->
        @yield('content')
        <!-- /Fin del contenido principal -->
    </div>
    <br>
    <br>
    <br>
  @guest
  <footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
              <h3>Mi Tienda en Linea</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi odio nulla sed praesentium quaerat vitae doloremque esse ab accusantium. Ipsam?</p>
            </div>
            <div class="col-md-4">
              <h3>Desarrollado por:</h3>
              <div class="autor-info">
                <img src="/img/logo.jpg" alt="" class="avatar">
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Architecto placeat saepe aliquam voluptatibus alias odio quos dolorum quod quis, eligendi sunt aut cumque eos nulla. Labore ullam alias, nulla incidunt soluta a corrupti facilis dolorum voluptate perferendis aliquid reprehenderit. Ipsum?</p>
              </div>
            </div>
            <div class="col-md-4">
              <h3>Siguenos</h3>
              <ul class="redes">
                <li><a href=""><i class="fa fa-facebook-square fa-2x"></i></a></li>
                <li><a href=""><i class="fa fa-twitter-square fa-2x"></i></a></li>
                <li><a href=""><i class="fa fa-google-plus-square fa-2x"></i></a></li>
                <li><a href=""><i class="fa fa-linkedin-square fa-2x"></i></a></li>
                <li><a href=""><i class="fa fa-youtube-square fa-2x"></i></a></li>
              </ul>
              <h3>Escribenos:</h3>
              <i class="fa fa-at"></i><a href=""> alvarolp1@gmail.com</a>
            </div>
        </div>
    </div>
</footer><!--/#footer-->
  @endguest
   <!-- Essential javascripts for application to work-->
   <script src="{{ asset('js/jquery-3.2.1.min.js') }}" defer></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/plugins/pinterest_grid.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>  
    <!--The javascript plugin to display page loading on top-->
    <script src="{{ asset('js/plugins/pace.min.js') }}" defer></script>
    <script src="{{ asset('js/plugins/sweetalert.min.js') }}" defer></script>
    <script src="{{ asset('js/plugins/Chart.min.js') }}" defer></script>
    
    @yield('scripts')
  </body>
