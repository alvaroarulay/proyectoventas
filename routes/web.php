<?php
    Route::get('/product/{id}',['as'=>'product-detail','uses'=>'StoreController@show']);
    Route::get('/store/buscar',['as'=>'buscar-producto','uses'=>'StoreController@searchProduct']);
    Route::get('/cart/show',['as'=>'cart-show','uses'=>'CartController@show']);
    Route::get('/cart/add/{id}',['as'=>'cart-add','uses'=>'CartController@add']);
    Route::get('/cart/delete/{id}',['as'=>'cart-delete','uses'=>'CartController@delete']);
    Route::get('/cart/trash',['as'=>'cart-trash','uses'=>'CartController@trash']);
    Route::get('/cart/update/{id}/{cantidad}',['as'=>'cart-update','uses'=>'CartController@update']);
Route::group(['middleware'=>['guest']],function(){
    Route::get('/','StoreController@index');
    Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::get('/registrar', ['as' => 'registrar', 'uses' => 'Auth\RegisterController@crearUsuario']);
    Route::post('/registrar/add', ['as' => 'registrar-nuevo', 'uses' => 'Auth\RegisterController@store']);
    Route::post('/validar', 'Auth\LoginController@validar')->name('validar');
});

Route::group(['middleware'=>['auth']],function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard','DasboardController');

    Route::group(['middleware' => ['Administrador']], function () {
        
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/comprobante', 'ComprobanteController@index');
        Route::post('/comprobante/registrar', 'ComprobanteController@store');
        Route::put('/comprobante/actualizar', 'ComprobanteController@update');
        Route::put('/comprobante/desactivar', 'ComprobanteController@desactivar');
        Route::put('/comprobante/activar', 'ComprobanteController@activar');
        Route::get('/comprobante/selectcomprobante', 'ComprobanteController@selectComprobante');

        Route::get('/marcas', 'MarcasController@index');
        Route::post('/marcas/registrar', 'MarcasController@store');
        Route::put('/marcas/actualizar', 'MarcasController@update');
        Route::put('/marcas/desactivar', 'MarcasController@desactivar');
        Route::put('/marcas/activar', 'MarcasController@activar');
        Route::get('/marcas/selectMarca', 'MarcasController@selectMarca');

        Route::get('/unidades', 'UnidadesController@index');
        Route::post('/unidades/registrar', 'UnidadesController@store');
        Route::put('/unidades/actualizar', 'UnidadesController@update');
        Route::put('/unidades/desactivar', 'UnidadesController@desactivar');
        Route::put('/unidades/activar', 'UnidadesController@activar');
        Route::get('/unidades/selectUnidad', 'UnidadesController@selectUnidad');

        Route::get('/factura/selectfactura', 'FacturaController@selectFactura');
        Route::post('/factura/registrar', 'FacturaController@store');

        Route::get('/empresa', 'EmpresaController@index');
        Route::put('/empresa/actualizar', 'EmpresaController@update');
        Route::get('/empresa/selectIva', 'EmpresaController@selectIva');
        Route::get('/empresa/selectempresa', 'EmpresaController@selectEmpresa');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/cantidadArticulo', 'ArticuloController@cantidadArticulos');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
        Route::get('/articulo/listarPdf','ArticuloController@listarPdf')->name('articulos_pdf');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::get('/ingreso/cantidad','IngresoController@numeroCompras');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');


        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/actualizar', 'VentaController@update');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::get('/venta/obtenerFactura', 'VentaController@obtenerFactura');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/reportePdf','VentaController@reportePdf')->name('ventas_pdf');
        Route::get('/venta/diarioPdf','VentaController@diarioPdf')->name('diario_pdf');
        Route::get('/venta/cantidad','VentaController@numeroVentas');
        Route::get('/venta/charVentas','VentaController@charVentas');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');
        Route::get('/user/cantidad','UserController@numeroUsuarios');
        Route::get('/user/usuario','UserController@selectUser');
    });
    Route::group(['middleware' => ['Almacenero']], function () {

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');

        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
        Route::get('/articulo/listarPdf','ArticuloController@listarPdf')->name('articulos_pdf');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

    });
    Route::group(['middleware' => ['Vendedor']], function () {
        
        Route::get('/user/usuario','UserController@selectUser');

        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/cantidadArticulo', 'ArticuloController@cantidadArticulos');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/actualizar', 'VentaController@update');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::get('/venta/obtenerFactura', 'VentaController@obtenerFactura');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/reportePdf','VentaController@reportePdf')->name('ventas_pdf');
        Route::get('/venta/diarioPdf','VentaController@diarioPdf')->name('diario_pdf');
        Route::get('/venta/cantidad','VentaController@numeroVentas');
        Route::get('/venta/charVentas','VentaController@charVentas');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        
        Route::get('/empresa/selectempresa', 'EmpresaController@selectEmpresa');
    });
    Route::group(['middleware' => ['Cliente']], function(){
        Route::get('/order-detail',['as'=>'order-detail','uses'=>'CartController@orderDetail']);
        Route::get('/add-venta',['as'=>'add-venta','uses'=>'CartController@addVenta']);
        Route::get('/datos',['as'=>'user-datos','uses'=>'StoreController@getDatosUser']);
        Route::post('/update-user',['as'=>'update-user','uses'=>'StoreController@updateUser']);
        Route::get('/store/search',['as'=>'searchproducto','uses'=>'StoreController@buscarProducto']);

        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
    });
});
//Route::get('/','Auth\LoginController@showLoginForm'); // convenciones de laravel showloginform
//Route::post('/login', 'Auth\LoginController@login')->name('login');
