<template>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Ventas</h1> 
            <br>
            <button type="button" @click="mostrarDetalle()" class="btn btn-secondary"> <i class="fa fa-plus"></i>&nbsp;Nueva Venta</button>
            <button type="button" @click="reportePdf()" class="btn btn-info"><i class="fa fa-file-pdf"></i>&nbsp;Reporte de Ventas</button>
            <button type="button" @click="diarioPdf()" class="btn btn-info"><i class="fa fa-file-pdf"></i>&nbsp;Ventas del Día</button>
        </div>
    </div>
     <!--Listado-->
    <template v-if="listado==1">
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body table-responsive">
              <div class="form-group row">
                <div class="col-md-6">
                    <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                            <option value="tipo_comprobante">Tipo Comprobante</option>
                            <option value="num_comprobante">Número Comprobante</option>
                            <option value="fecha_hora">Fecha-Hora</option>
                        </select>
                        <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                        <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </div>
            </div>
              <table class="table table-bordered table-striped table-sm">
                <thead>
                   <tr>
                        <th>Usuario</th>
                        <th>Cliente</th>
                        <th>Tipo Comprobante</th>
                        <th>Número Comprobante</th>
                        <th>Fecha Hora</th>
                        <th>Total</th>
                        <th>Impuesto</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                           <tr v-for="venta in arrayVenta" :key="venta.id">
                                        
                                <td v-text="venta.usuario"></td>
                                <td v-text="venta.nombre"></td>
                                <td v-text="venta.tipo_comprobante"></td>
                                <td v-text="venta.num_comprobante"></td>
                                <td v-text="new Date(venta.fecha_hora).toLocaleDateString()"></td>
                                <td v-text="venta.total"></td>
                                <td v-text="venta.impuesto"></td>
                                <td v-text="venta.estado"></td>
                                <td>
                                    <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></button> &nbsp;
                                    <template v-if="venta.estado=='Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)"><i class="fa fa-trash"></i></button>
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
        <nuevocliente @new="addCliente"></nuevocliente>
        <div class="row">
         <div class="col-md-6">
             <div class="tile">
                <h3>Clientes</h3>
                <label for="">Cliente(*)</label>
                <v-select id="vueselect" @search="selectCliente" label="nombre" :options="arrayCliente" placeholder="Buscar Clientes..." @input="getDatosCliente"></v-select> 
                <label for="">Nombre</label>
                <input type="text" class="form-control" disabled v-model="nomcli" required>
                <label for="">CI/NIT</label>
                <input type="text" class="form-control" disabled v-model="doccli" required>
                <br>
                 <h3 class="tile-title">Artículos</h3>
                <div class="form-inline" >
                    <label>Artículo <span style="color:red;" v-show="idarticulo==0">(*Seleccione)</span></label>&nbsp;&nbsp;&nbsp;
                    <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">&nbsp;
                    <button @click="abrirModal()" class="btn btn-primary">...</button>
                </div>
             </div>
         </div>
         <div class="col-md-6">
             <div class="tile">
                 <h3 class="tile-title">Venta</h3>
                 <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="text" class="form-control" v-model="total" id="total" disabled>
                </div>
                 <h3 class="tile-title">Comprobantes</h3>
                 <div class="form-group">
                     <div class="row">
                         <div class="col-md-6">
                            <label>Tipo Comprobante(*)</label>
                            <v-select  label="nombre" :options="arrayComprobantes" placeholder="Selecccionar Comprobante..." @input="getDatosComprobante"></v-select> 
                         </div>
                         <div class="col-md-6">
                              <label for="">Impuesto(*)</label>
                              <input type="text" class="form-control" v-model="impuesto" disabled>
                         </div>
                     </div>
                    
                    <div class="form-group">
                        <label>Número de Autorización</label>
                        <input type="text" class="form-control" v-model="serie_comprobante" disabled>
                    </div>
                    <div class="form-group">
                        <label>Número Factura/Proforma(*)</label>
                        <input type="text" class="form-control" v-model="num_comprobante" disabled>
                    </div>
                    <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                    <div v-show="errorVenta" class="alert alert-dismissible alert-danger">
                        <div class="text-center">
                            <strong><div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error"></div></strong>
                        </div>
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
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Descuento</th>
                        <th>Subtotal</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody v-if="arrayDetalle.length">
                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                        
                        <td v-text="detalle.articulo">
                        </td>
                        <td>
                            <input v-model="detalle.precio" type="number" class="form-control" disabled>
                        </td>
                        <td>
                            <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                            <input v-model="detalle.cantidad" type="number" class="form-control">
                        </td>
                        <td>
                            <span style="color:red;" v-show="detalle.descuento>(detalle.precio*detalle.cantidad)">Descuento superior</span>
                            <input v-model="detalle.descuento" type="number" class="form-control">
                        </td>
                        <td>
                            {{detalle.precio*detalle.cantidad-detalle.descuento}}
                        </td>
                        <td>
                            <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger">
                                <i class="fa fa-close"></i>
                            </button>
                        </td>
                    </tr>
                     <tr style="background-color: #CEECF5;">
                        <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                        <td>$ {{totalParcial=(parseFloat(total)-parseFloat(totalImpuesto)).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                        <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                        <td>$ {{totalImpuesto=((parseFloat(total)*parseFloat(impuesto))/(1+parseFloat(impuesto))).toFixed(2)}}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                        <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                        <td>$ {{total=calcularTotal}}</td>
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
                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
            </div>
        </div>
    </template>
    <!-- Fin Detalle-->
    <!-- Ver ingreso -->
    <template v-else-if="listado==2">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group tile">
                            <div class="form-group">
                                <label for="">Cliente</label>
                                <p v-text="cliente"></p>
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                                <label>Tipo Comprobante</label>
                                <p v-text="tipo_comprobante"></p>
                                <label>Número Comprobante</label>
                                <p v-text="num_comprobante"></p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm tile">
                            <thead>
                                <tr>
                                    <th>Artículo</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayDetalle.length">
                                <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                    <td v-text="detalle.articulo">
                                    </td>
                                    <td v-text="detalle.precio">
                                    </td>
                                    <td v-text="detalle.cantidad">
                                    </td>
                                    <td v-text="detalle.descuento">
                                    </td>
                                    <td>
                                        {{detalle.precio*detalle.cantidad-detalle.descuento}}
                                    </td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                    <td>$ {{totalParcial=(parseFloat(total)-parseFloat(totalImpuesto)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                    <td>$ {{totalImpuesto=(parseFloat(total)*parseFloat(impuesto)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color: #CEECF5;">
                                    <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                    <td>$ {{total}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="5">
                                        NO hay artículos agregados
                                    </td>
                                </tr>
                            </tbody>                                    
                        </table>
                    </div>
                    <div class="col-md-12">
                        <button type="button" v-if="proforma==1" @click="completarVenta()" class="btn btn-warning">Completar Venta</button>
                        <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <div class="d-flex justify-content-end">
                        <button @click="$refs.printpdf.print()" class="btn btn-warning"><i class="fa fa-print"></i></button>
                        <button @click="pdfVenta(idventa)"  class="btn btn-info"><i class="fa fa-download"></i></button>
                    </div>
                    <pdf ref="printpdf" :src="src" style="display: inline-block; width: 100%" ></pdf>
                </div>
            </div>
        </div>
    </template>
    <!-- fin ver ingreso -->
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
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                    <td>
                                        <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                        <i class="fa fa-check"></i>
                                        </button>
                                    </td>
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
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
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
    import pdf from 'vue-pdf';
    export default {
        data (){
            return {
                venta_id: 0,
                idcliente:0,
                idcomprobantes:0,
                cliente:'',
                tipo_comprobante : '',
                serie_comprobante : '',
                num_comprobante : 1,
                impuesto: 0.13,
                total:0.00,
                totalImpuesto: 0.0,
                totalParcial: 0.0,
                arrayVenta : [],
                arrayCliente: [],
                arrayDetalle : [],
                arrayComprobantes : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
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
                precio: 0.0,
                cantidad:0,
                descuento: 0.0,
                stock:0,
                nomcli:'',
                doccli:'',
                nombre_comprobante:'',
                numRecibo:0,
                idfactura:0,
                proforma:0,
                idventa:0,
                src:'',
                numPages: undefined,
                validationErrors: ''
            }
        },
        components: {
            vSelect,
            pdf
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
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento)
                }
                return resultado;
            }
        },
        props:['persona'],
        methods : {
            listarVenta (page,buscar,criterio){
                let me=this;
                var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                    me.numRecibo=respuesta.recibo;
                })
                .catch(function (error) {
                    console.log(error);
                });
                var url2= '/comprobante/selectcomprobante';
                axios.get(url2).then((response)=>{
                    let respuesta = response.data;
                    me.arrayComprobantes=respuesta.comprobante;
                })
                .catch(function (error) {
                    console.log(error);
                });
                var url3='/factura/selectfactura';
                axios.get(url3).then((response)=>{
                    let respuesta =response.data;
                    me.idfactura=respuesta.factura;
                })
            },
            selectCliente(search,loading){
                let me=this;
                loading(true)
                var url= '/cliente/selectCliente?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayCliente=respuesta.clientes;
                    loading(false);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
                me.nomcli=val1.nombre;
                me.doccli=val1.num_documento;
            },
            getDatosComprobante(valor){
                let me = this;
                if(valor.nombre=='FACTURA'){
                    if(valor.cantidad>0){
                        let limite = new Date(valor.fecha_lim_emision);
                        let fecha = new Date();
                        if(limite>=fecha){
                            me.idcomprobantes=valor.id;
                            me.nombre_comprobante=valor.nombre;
                            me.num_comprobante=me.idfactura+1;
                            me.serie_comprobante=valor.num_autorizacion;
                        }
                        else{
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Facturas Exipiradas!'
                        }).then((result) => {
                            if (result.value) {
                                me.nombre_comprobante='';
                                me.num_comprobante=0;
                                me.serie_comprobante=0;
                            }
                            })                        
                        }
                    }
                    else{
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No hay FACTURAS disponibles!'
                        }).then((result) => {
                            if (result.value) {
                                me.nombre_comprobante='';
                                me.num_comprobante=0;
                                me.serie_comprobante=0;
                            }
                            })
                    }
                }
                else{
                    if(valor.nombre=='PROFORMA'){
                        if(valor.cantidad>0){
                            me.idcomprobantes=valor.id;
                            me.nombre_comprobante=valor.nombre;
                            me.num_comprobante=parseInt(me.numRecibo)+1;
                            me.serie_comprobante=valor.num_autorizacion;
                        }
                        else{
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No hay PROFORMAS disponibles!'
                        }).then((result) => {
                            if (result.value) {
                                me.nombre_comprobante='';
                                me.num_comprobante=0;
                                me.serie_comprobante=0;
                            }
                            })
                        }
                    }
                }
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
            pdfVenta(id){
                window.open('http://3.23.211.103/venta/pdf/'+ id + ',' + '_blank');
            },
            reportePdf(){
                window.open('http://3.23.211.103/venta/reportePdf','_blank');
            },
            diarioPdf(){
                window.open('http://3.23.211.103/venta/diarioPdf','_blank');
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarVenta(page,buscar,criterio);
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
            },
            agregarDetalle(){
                let me=this;
                if(me.idarticulo==0 || me.cantidad==0 || me.precio==0){
                }
                else{
                    if(me.encuentra(me.idarticulo)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado!',
                            })
                    }
                    else{
                       if(me.cantidad>me.stock){
                           swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'NO hay stock disponible!',
                            })
                       } 
                       else{
                           me.arrayDetalle.push({
                                idarticulo: me.idarticulo,
                                articulo: me.articulo,
                                cantidad: me.cantidad,
                                precio: me.precio,
                                descuento: me.descuento,
                                stock: me.stock
                            });
                            me.codigo="";
                            me.idarticulo=0;
                            me.articulo="";
                            me.cantidad=0;
                            me.precio=0;
                            me.descuento=0;
                            me.stock=0
                       }
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
                       me.arrayDetalle.push({
                            idarticulo: data['id'],
                            articulo: data['nombre'],
                            cantidad: 1,
                            precio: data['precio'],
                            descuento:0,
                            stock:data['stock']
                        }); 
                    }
            },
            listarArticulo (buscar,criterio){
                let me=this;
                var url= '/articulo/listarArticuloVenta?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then((response)=> {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                }).catch((error)=> {
                    console.log(error);
                });
            },
            addCliente(persona){
                this.idcliente = persona.id;
                this.nomcli = persona.nombre;
                this.doccli = persona.num_documento;
            },  
            registrarVenta(){
                if (this.validarVenta()){
                    return;
                }
                let me = this;
               if(me.nombre_comprobante=='FACTURA'){
                    const paramFactura={
                        'idcliente':me.idcliente,
                        'idcomprobante':me.idcomprobantes,
                        'num_comprobante':me.num_comprobante,
                        'serie_comprobante':me.serie_comprobante,
                        'total':me.total
                    };
                   axios.post('/factura/registrar',paramFactura).then((response)=>{
                       const paramVenta={
                        'idcliente':me.idcliente,
                        'idfactura':response.data.id,
                        'tipo_comprobante':me.nombre_comprobante,
                        'num_comprobante':me.num_comprobante,
                        'impuesto':me.impuesto,
                        'total':me.total,
                        'estado':'Registrado',
                        'data': me.arrayDetalle};
                         axios.post('/venta/registrar',paramVenta).then((response)=>{
                            me.reset();
                            window.open(`http://3.23.211.103/venta/pdf/${response.data.id},_blank`);
                         }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
                        })
               }else{
                   const paramVenta={
                        'idcliente':me.idcliente,
                        'idfactura':null,
                        'tipo_comprobante':me.nombre_comprobante,
                        'num_comprobante':me.num_comprobante,
                        'impuesto':me.impuesto,
                        'total':me.total,
                        'estado':'Registrado',
                        'data': this.arrayDetalle};
                   axios.post('/venta/registrar',paramVenta).then((response)=>{
                             me.reset();
                             window.open('http://3.23.211.103/venta/pdf/'+ response.data.id + ',' + '_blank');
                         }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
               }
            },
            validarVenta(){
                let me=this;
                me.errorVenta=0;
                me.errorMostrarMsjVenta =[];
                var art;
                
                me.arrayDetalle.map(function(x){
                    if (x.cantidad>x.stock){
                        art=x.articulo + " con stock insuficiente";
                        me.errorMostrarMsjVenta.push(art);
                    }
                });

                if (me.idcliente==0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");
                if (!me.nombre_comprobante) me.errorMostrarMsjVenta.push("Seleccione el comprobante");
                if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese detalles");

                if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;

                this.errors = [];
            },
            mostrarDetalle(){
                let me=this;
                me.listado=0;

                me.idproveedor=0;
                me.tipo_comprobante='';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.total=0.0;
                me.idarticulo=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado=1;
                this.proforma=0;
            },
            verVenta(id){
                let me=this;
                me.listado=2;
                //Obtener los datos del ingreso
                var arrayVentaT=[];
                var url= '/venta/obtenerCabecera?id=' + id;
                axios.get(url).then((response)=>{
                    var respuesta= response.data;
                    arrayVentaT = respuesta.venta;
                    me.idventa=arrayVentaT[0]['id'];
                    me.cliente = arrayVentaT[0]['nombre'];
                    me.tipo_comprobante=arrayVentaT[0]['tipo_comprobante'];
                    me.num_comprobante=arrayVentaT[0]['num_comprobante'];
                    me.impuesto=arrayVentaT[0]['impuesto'];
                    me.total=arrayVentaT[0]['total'];
                    me.idcliente=arrayVentaT[0]['pid'];
                    if(arrayVentaT[0]['tipo_comprobante']=='PROFORMA'){me.proforma=1;};
                    var loadingpdf = pdf.createLoadingTask('http://3.23.211.103/venta/pdf/'+ arrayVentaT[0]['id']);
                    me.src=loadingpdf;
                })
                .catch((error)=>{
                    console.log(error);
                });
                //Obtener los datos de los detalles 
                var urld= '/venta/obtenerDetalles?id=' + id;
                axios.get(urld).then((response)=>{
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch((error)=>{
                    console.log(error);
                });
                 var url3='/factura/selectfactura';
                axios.get(url3).then((response)=>{
                    let respuesta =response.data;
                    me.idfactura=respuesta.factura;
                });
            },
            completarVenta(){
                let me = this;
                //comprobantes
                var arrayComprobanteT = [];
                var urlc= '/venta/obtenerFactura';
                axios.get(urlc).then((response)=>{
                    var respuesta= response.data;
                    arrayComprobanteT=respuesta.comprobante;
                    me.idcomprobante=arrayComprobanteT[0].id;
                    me.nombre_comprobante=arrayComprobanteT[0]['nombre'];
                    me.serie_comprobante=arrayComprobanteT[0]['num_autorizacion'];
                    const paramFactura={
                        'idcliente':me.idcliente,
                        'idcomprobante':me.idcomprobante,
                        'num_comprobante':me.idfactura+1,
                        'serie_comprobante':me.serie_comprobante,
                        'total':me.total
                    };
                   axios.post('/factura/registrar',paramFactura).then((response)=>{
                       const paramVenta={
                        'id':me.idventa,
                        'idfactura':response.data.id,
                        'tipo_comprobante':me.nombre_comprobante,
                        'num_comprobante':me.idfactura+1};
                         axios.put('/venta/actualizar',paramVenta).then((response)=>{
                             me.proforma=0;
                             me.reset();
                         })
                        }).catch(function (error) {console.log(error);});
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
            desactivarVenta(id){
                const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                        })

                    swal.fire({
                        title: 'Esta seguro de anular esta venta?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar!!',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                        }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then((response)=>{
                        me.listarVenta(1,'','num_comprobante');
                        swal.fire(
                        'Anulado!',
                        'La venta ha sido anulada con éxito.',
                        'success'
                        )
                    }).catch((error)=>{
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },   
            onClickComprobante(val2){
                let me=this;
                console.log(val2);
            },
            reset(){
                let me=this;
                me.listado=1;
                me.listarVenta(1,'','num_comprobante');
                me.idcliente=0;
                me.tipo_comprobante='';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.nombre_comprobante='';
                me.total=0.0;
                me.idarticulo=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.stock=0;
                me.codigo='';
                me.descuento=0;
                me.arrayDetalle=[];
                me.arrayCliente=[];
                me.nomcli='';
                me.doccli='';
                me.validationErrors='';
                me.errorVenta=0;
                me.errorMostrarMsjVenta=[];
            } 
        },
        mounted() {
            this.listarVenta(1,this.buscar,this.criterio);
           
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
    #vueselect{
        margin: 10px;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
    #total{
        background:#00257A;
        color: aliceblue;
        font-size: 30px;
        text-align: center;
    }

</style>
