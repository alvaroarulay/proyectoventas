<div class="main-banner">
  <div class="main-container main-banner__data-container">
    <!--Datos-->
    <div class="container">
    <div class="main-banner__data">
      <!--Titulo-->
      <h2 class="main-banner__title">Ventas con Honestidad</h2>
      <!--Subtitulo-->
      <p class="main-banner__subtitle">variedad de productos</p>
      <!--Descripcion-->
      <p class="main-banner__description">Repuestos de electrónica, Robótica, Audio, Video, Accesorios de Celulares, Computación, redes, Herramientas en General, Ventas por mayor y menor.</p>
      <form action="{{route('buscar-producto')}}" method="get" class="d-flex">
      @csrf
        <input type="text" name="nombre" placeholder="Encuentra tu articulo" class="form-control is-valid">
        <br>
        <button type="submit" class="btn btn-warning btn-large"><i class="fa fa-search"></i></button>
      </form>
    </div>
    </div>
  </div>
</div>