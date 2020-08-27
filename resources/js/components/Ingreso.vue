<template>
<main class="app-content">
    <div class="app-title">
        <div>
          <h1><i class="fa fa-align-justify"></i> Ingresos</h1> 
          <br>
          <button type="button" @click="mostrarDetalle()" class="btn btn-secondary"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        </div>
    </div>
     <!--Listado-->
    <template v-if="listado==1">
        <div class="form-group row">
        <div class="col-md-6">
            <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                    <option value="tipo_comprobante">Tipo Comprobante</option>
                    <option value="num_comprobante">Número Comprobante</option>
                    <option value="fecha_hora">Fecha-Hora</option>
                </select>
                <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body table-responsive">
            
              <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Proveedor</th>
                        <th>Tipo Comprobante</th>
                        <th>Serie Comprobante</th>
                        <th>Número Comprobante</th>
                        <th>Fecha Hora</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                        <td v-text="ingreso.usuario"></td>
                        <td v-text="ingreso.nombre"></td>
                        <td v-text="ingreso.tipo_comprobante"></td>
                        <td v-text="ingreso.serie_comprobante"></td>
                        <td v-text="ingreso.num_comprobante"></td>
                        <td v-text="new Date(ingreso.fecha_hora).toLocaleDateString()"></td>
                        <td v-text="ingreso.total"></td>
                        <td v-text="ingreso.estado"></td>
                        <td>
                            <button type="button" @click="verIngreso(ingreso.id)" class="btn btn-success btn-sm">
                            <i class="fa fa-eye"></i>
                            </button> &nbsp;
                            <template v-if="ingreso.estado=='Registrado'">
                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </template>
                        </td>
                    </tr>                                
                </tbody>
            </table>
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                    </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                    </li>
                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                    </li>
                </ul>
            </nav>
          </div>
        </div>
    </div> 
    </template>
    <!--Fin Listado-->
    <!-- Detalle-->
    <template v-else-if="listado==0">
        <div class="row">
            <div class="col-md-6">
                <div class="tile">
                    <div class="form-group row ">
                    <div class="col-md-9">
                        <div class="form-group">
                            <strong><h4 for="">Proveedor(*)</h4></strong>
                            <v-select 
                                @search="selectProveedor"
                                label="nombre"
                                :options="arrayProveedor"
                                placeholder="Buscar Proveedores..."
                                @input="getDatosProveedor"                                        
                            >

                            </v-select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <strong><label for="">Impuesto(*)</label></strong>
                        <input type="text" class="form-control" v-model="impuesto">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong><label>Tipo Comprobante(*)</label></strong>
                            <select class="form-control"  @click="selectComprobante()" v-model="tipo_comprobante">
                                <option value="0" selected>Seleccione</option>
                                <option value="BOLETA">Boleta</option>
                                <option value="FACTURA">Factura</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong><label>Serie Comprobante</label></strong>
                            <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong><label>Número Comprobante(*)</label></strong>
                            <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                        </div>
                    </div>
                    <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                    <div v-show="errorIngreso" class="col-md-12 alert alert-dismissible alert-danger">
                        <div class="text-center">
                            <strong><div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error"></div></strong>
                        </div>
                    </div>
                </div>
                </div>
                
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h4>Total Compra</h4>
                        <hr>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control" v-model="total" id="total" disabled>
                    </div>
                    <div class="form-group">
                        <h4 style="display:inline;">Artículo</h4> <span style="color:red;" v-show="idarticulo==0">(*Seleccione)</span>
                        <div class="form-inline">
                            <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                            <button @click="abrirModal()" class="btn btn-primary">...</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="form-group row tile">
            <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Artículo</th>
                            <th>Precio de Compra (Unitario)</th>
                            <th>Cantidad</th>
                            <th>Precio de Venta</th>
                            <th>Subtotal</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody v-if="arrayDetalle.length">
                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                            <td v-text="detalle.articulo">
                            </td>
                            <td>
                                <input v-model="detalle.precio_compra" type="number" class="form-control">
                            </td>
                            <td>
                                <input v-model="detalle.cantidad" type="number" class="form-control">
                            </td>
                            <td>
                                Precio Sugerido:<strong style="color:red">{{ parseFloat((parseFloat(detalle.precio_compra)) + (parseFloat((detalle.precio_compra*detalle.impuesto)+(detalle.mantenimiento/(detalle.cantidad+detalle.cantidad_articulos))))).toFixed(2)}}
                                </strong>
                                <input v-model="detalle.precio_venta" type="number" class="form-control" >
                                 </td>
                            <td>
                                {{detalle.precio_venta*detalle.cantidad}}
                            </td>
                            <td>
                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                    <i class="fa fa-close"></i>
                                </button>
                            </td>
                        </tr>
                        <tr style="background-color: #a4ede6;">
                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                            <td>$ {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                        </tr>
                        <tr style="background-color: #a4ede6;">
                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                            <td>$ {{totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                        </tr>
                        <tr style="background-color: #a4ede6;">
                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                            <td>$ {{total=calcularTotal.toFixed(2)}}</td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6">
                                NO hay artículos agregados
                            </td>
                        </tr>
                    </tbody>                                    
                </table>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
            </div>
        </div>
    </template>
    <!-- Fin Detalle-->
    <!-- Ver ingreso -->
    <template v-else-if="listado==2">
        <div class="card-body">
            <div class="form-group row tile">
                <div class="col-md-9">
                    <div class="form-group">
                        <strong><label for="">Proveedor</label></strong>
                        <p v-text="proveedor"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <strong><label>Tipo Comprobante</label></strong>
                        <p v-text="tipo_comprobante"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <strong><label>Serie Comprobante</label></strong>
                        <p v-text="serie_comprobante"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <strong><label>Número Comprobante</label></strong>
                        <p v-text="num_comprobante"></p>
                    </div>
                </div>
            </div>
            <div class="form-group row tile">
                <div class="table-responsive col-md-12">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Artículo</th>
                                <th>Precio de Compra</th>
                                <th>Precio de Venta</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody v-if="arrayDetalle.length">
                            <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                <td v-text="detalle.articulo"></td>
                                <td v-text="detalle.precio_compra"></td>
                                <td v-text="detalle.precio_venta"></td>
                                <td v-text="detalle.stock"></td>
                            </tr>
                            <tr style="background-color: #a4ede6;">
                                <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                <td>$ {{total}}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="4">
                                    NO hay artículos agregados
                                </td>
                            </tr>
                        </tbody>                                    
                    </table>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                    
                </div>
            </div>
        </div>
    </template>
    <!-- fin ver ingreso -->
    <!-- Fin ejemplo de tabla Listado -->
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                    <option value="codigo">Código</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <td v-text="articulo.codigo"></td>
                                        <td v-text="articulo.nombre"></td>
                                        <td v-text="articulo.nombre_categoria"></td>
                                        <td v-text="articulo.precio"></td>
                                        <td v-text="articulo.stock"></td>
                                        <td>
                                            <div v-if="articulo.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                            
                                        </td>
                                        <td>
                                            <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                            <i class="fa fa-check"></i>
                                            </button>
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    export default {
        data (){
            return {
                ingreso_id: 0,
                idproveedor:0,
                proveedor:'',
                nombre : '',
                tipo_comprobante : 'FACTURA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto: 0.13,
                mantenimiento:0,
                total:0.0,
                totalImpuesto: 0.0,
                totalParcial: 0.0,
                arrayIngreso : [],
                arrayProveedor: [],
                arrayDetalle : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : '',
                criterioA:'nombre',
                buscarA: '',
                arrayArticulo: [],
                idarticulo: 0,
                codigo: '',
                articulo: '',
                precio:0,
                precio_venta: 0,
                precio_compra:1,
                cantidad:0,
                cantidad_articulos:0,
                validationErrors: ''
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio_venta*this.arrayDetalle[i].cantidad)
                }
                return resultado;
            },
            
        },
        methods : {
            listarIngreso (page,buscar,criterio){
                let me=this;
                var url= '/ingreso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProveedor(search,loading){
                let me=this;
                loading(true)

                var url= '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayProveedor=respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },
            buscarArticulo(){
                let me=this;
                var url= '/articulo/buscarArticulo?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    if (response.data.cantidad == 0){
                       swal.fire({
                            title: 'Error',
                            icon: 'error',
                            text: 'No Existe el Articulo que Ingreso!',
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "error",
                            timer:2000
                            });
                    }
                    else{
                        me.agregarDetalleModal(respuesta.articulos);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarIngreso(page,buscar,criterio);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idarticulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
                if(me.arrayDetalle.length==0){
                    me.total=0;
                }
            },
            agregarDetalle(){
                let me=this;
                if(me.idarticulo==0 || me.cantidad==0 || me.precio_compra==0){
                }
                else{
                    if(me.encuentra(me.idarticulo)){
                    const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                        })

                    swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: 'Ese artículo ya se encuentra agregado!',
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar!!',
                        reverseButtons: true
                        })
                    }
                    else{
                    
                       me.arrayDetalle.push({
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            precio_compra: me.precio_compra,
                            cantidad_articulos:me.cantidad_articulos,
                            impuesto:me.impuesto,
                            mantenimiento:me.mantenimiento,
                            cantidad:me.cantidad,
                            precio_venta: me.precio_venta

                        });
                        me.codigo="";
                        me.idarticulo=0;
                        me.articulo="";
                        me.cantidad=0;
                        me.precio_venta=0;
                        me.precio_compra=0;
                        me.impuesto=0;
                    }
                    
                }

                

            },
            agregarDetalleModal(data =[]){
                 let me=this;
                if(me.encuentra(data['id'])){
                    swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: 'Ese artículo ya se encuentra agregado!',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer:2000
                        })
                    }
                    else{
                        var url='/articulo/cantidadArticulo';
                        axios.get(url).then((response)=>{
                            me.cantidad_articulos=response.data;
                            me.arrayDetalle.push({
                                    idarticulo: data['id'],
                                    articulo: data['nombre'],
                                    precio_compra:1,
                                    cantidad_articulos:me.cantidad_articulos,
                                    impuesto:me.impuesto,
                                    mantenimiento:me.mantenimiento,
                                    cantidad:1,
                                    precio_venta: 0
                                }); 
                            });
                            
                        }
            },
            listarArticulo (buscar,criterio){
                let me=this;
                var url= '/articulo/listarArticulo?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectComprobante(){
                let me=this;
                var url= '/empresa/selectIva';
                axios.get(url).then((response)=>{
                    var respuesta= response.data;
                    if(me.tipo_comprobante=='FACTURA'){
                        me.impuesto=respuesta.iva / 100;
                    }
                    else{
                        if(me.tipo_comprobante=='RECIBO'){
                            me.impuesto=0;
                        }
                    }
                    me.mantenimiento=respuesta.mantenimiento;
                });
            },
            registrarIngreso(){
                if (this.validarIngreso()){
                    return;
                }
                let me = this;

                axios.post('/ingreso/registrar',{
                    'idproveedor': this.idproveedor,
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante' : this.serie_comprobante,
                    'num_comprobante' : this.num_comprobante,
                    'total' : this.total,
                    'data': this.arrayDetalle
                }).then((response)=>{
                    me.listado=1;
                    me.listarIngreso(1,'','num_comprobante');
                    swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                    me.idproveedor=0;
                    me.tipo_comprobante='';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0;
                    me.total=0.0;
                    me.idarticulo=0;
                    me.articulo='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            validarIngreso(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso =[];

                if (this.idproveedor==0) this.errorMostrarMsjIngreso.push("Seleccione un Proveedor");
                if (this.tipo_comprobante==0) this.errorMostrarMsjIngreso.push("Seleccione el comprobante");
                if (!this.num_comprobante) this.errorMostrarMsjIngreso.push("Ingrese el número de comprobante");
                if (!this.impuesto) this.errorMostrarMsjIngreso.push("Ingrese el impuesto de compra");
                if (this.arrayDetalle.length<=0) this.errorMostrarMsjIngreso.push("Ingrese detalles");

                if (this.errorMostrarMsjIngreso.length) this.errorIngreso = 1;

                return this.errorIngreso;
            },
            mostrarDetalle(){
                let me=this;
                me.listado=0;

                me.idproveedor=0;
                me.tipo_comprobante='';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.impuesto=0;
                me.total=0.0;
                me.idarticulo=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verIngreso(id){
                let me=this;
                me.listado=2;
                
                //Obtener los datos del ingreso
                var arrayIngresoT=[];
                var url= '/ingreso/obtenerCabecera?id=' + id;
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayIngresoT = respuesta.ingreso;

                    me.proveedor = arrayIngresoT[0]['nombre'];
                    me.tipo_comprobante=arrayIngresoT[0]['tipo_comprobante'];
                    me.serie_comprobante=arrayIngresoT[0]['serie_comprobante'];
                    me.num_comprobante=arrayIngresoT[0]['num_comprobante'];
                    me.impuesto=arrayIngresoT[0]['impuesto'];
                    me.total=arrayIngresoT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var urld= '/ingreso/obtenerDetalles?id=' + id;
                
                axios.get(urld).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });               
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            }, 
            abrirModal(){               
                this.arrayArticulo=[];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios artículos';
                
            },
            desactivarIngreso(id){
                const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })

                    swal.fire({
                    title: 'Esta seguro de anular este ingreso?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/ingreso/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIngreso(1,'','num_comprobante');
                        swal.fire(
                        'Anulado!',
                        'El ingreso ha sido anulado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    #total{
        background:#009688;
        color: aliceblue;
        font-size: 30px;
        text-align: center;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
