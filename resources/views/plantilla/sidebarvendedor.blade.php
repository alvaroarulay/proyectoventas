<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="/img/user.png" alt="User Image" title="{{ Auth::user()->usuario }}">
    </div>
      <ul class="app-menu">
      <li @click="menu=0"><a class="app-menu__item" href="#"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Mis Datos</span></a></li>
       <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Ventas</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li @click="menu=9"><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Ventas</a></li>
            <li @click="menu=10"><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Clientes</a></li>
          </ul>
        </li>
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-bar-chart"></i><span class="app-menu__label">Reportes</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li @click="menu=12"><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Reporte de Ventas</a></li>
          </ul>
        </li>
        <li @click="menu=13"><a class="app-menu__item" href="#"><i class="app-menu__icon fa fa-file-pdf-o"></i><span class="app-menu__label">Ayuda </span></a></li>
        </li>
        <li @click="menu=14"><a class="app-menu__item" href="#"><i class="app-menu__icon fa fa-info"></i><span class="app-menu__label"> Acerca de...</span></a></li>
        </li>
       </ul>
    </aside>