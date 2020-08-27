<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="/img/user.png" alt="User Image" title="{{ Auth::user()->usuario }}">
    </div>
    <ul class="app-menu">
    <li><a class="app-menu__item" href="{{ route('cart-show') }}"><i class="app-menu__icon fa fa-shopping-cart"></i><span class="app-menu__label">Mi Carrito </span></a></li>
    <li><a class="app-menu__item" href="{{ route('user-datos') }}"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Mis Datos</span></a></li>
    <li><a class="app-menu__item" href="#"><i class="app-menu__icon fa fa-file-pdf-o"></i><span class="app-menu__label">Ayuda </span></a></li>
    <li><a class="app-menu__item" href="#"><i class="app-menu__icon fa fa-info"></i><span class="app-menu__label"> Acerca de...</span></a></li>
    </ul>
</aside>
