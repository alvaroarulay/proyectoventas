require ('./bootstrap');
window.Vue = require('vue');
Vue.component('categoria', require('./components/Categoria.vue').default);
Vue.component('articulo', require('./components/Articulo.vue').default);
Vue.component('indexclientes', require('./components/cliente/indexCliente.vue').default);
Vue.component('nuevocliente', require('./components/cliente/nuevoCliente.vue').default);
Vue.component('tablaclientes', require('./components/cliente/TablaClientes.vue').default);
Vue.component('proveedor', require('./components/Proveedor.vue').default);
Vue.component('rol', require('./components/Rol.vue').default);
Vue.component('user', require('./components/User.vue').default);
Vue.component('ingreso', require('./components/Ingreso.vue').default);
Vue.component('venta', require('./components/Venta.vue').default);
Vue.component('comprobante', require('./components/Comprobante.vue').default);
Vue.component('empresa', require('./components/Empresa.vue').default);
Vue.component('indexmarcas', require('./components/marca/indexMarca.vue').default);
Vue.component('nuevamarca', require('./components/marca/nuevaMarca.vue').default);
Vue.component('tablamarcas', require('./components/marca/tablaMarca.vue').default);
Vue.component('indexunidades', require('./components/unidad/indexUnidad.vue').default);
Vue.component('nuevaunidad', require('./components/unidad/nuevaUnidad.vue').default);
Vue.component('tablaunidades', require('./components/unidad/tablaUnidad.vue').default);
Vue.component('reporte', require('./components/reporteVentas.vue').default);
Vue.component('dashboard', require('./components/dashboar.vue').default);
Vue.component('vendedor', require('./components/empleado.vue').default);
Vue.component('validation-errors',require('./components/errors.vue').default);
const app = new Vue({
    el: '#app',
    data:{
        menu:0
    }
});