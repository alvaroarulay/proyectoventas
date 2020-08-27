<template>
<main class="app-content">
    <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Comprobantes</h1> 
          <br>
          <button type="button" @click="abrirModal('comprobante','registrar')" class="btn btn-secondary"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                    <option value="nombre">Nombre</option>
                </select>
                <input type="text" v-model="buscar" @keyup.enter="listarComprobante(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                <button type="submit" @click="listarComprobante(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body table-responsive">
              <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                  <tr>
                      <th>Nombre</th>
                      <th>Autorización</th>
                      <th>Llave</th>
                      <th>Cantidad</th>
                      <th>Fecha Limite</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                    <tr v-for="comprobante in arrayComprobante" :key="comprobante.id">
                        
                        <td v-text="comprobante.nombre"></td>
                        <td v-text="comprobante.num_autorizacion"></td>
                        <td v-text="comprobante.llave"></td>
                        <td v-text="comprobante.cantidad"></td>
                        <td v-text="new Date(comprobante.fecha_lim_emision).toLocaleDateString()"></td>
                        <td>
                            <div v-if="comprobante.estado">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-danger">Desactivado</span>
                            </div>
                            
                        </td>
                        <td>
                            <button type="button" @click="abrirModal('comprobante','actualizar',comprobante)" class="btn btn-warning btn-sm" title="¿Editar?">
                                <i class="fa fa-pencil"></i>
                            </button> &nbsp;
                            <template v-if="comprobante.estado">
                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarComprobante(comprobante.id)" title="¿Desactivar?">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-info btn-sm" @click="activarComprobante(comprobante.id)" title="¿Activar?">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </template>
                        </td>
                    </tr> 
                </tbody>
              </table>
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
    </div> <!-- Fin ejemplo de tabla Listado -->
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
                        <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                        <div v-show="errorComprobante" class="alert alert-dismissible alert-danger">
                            <div class="text-center">
                                <strong><div v-for="error in errorMostrarMsjComprobante" :key="error" v-text="error"></div></strong>
                            </div>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <select v-model="nombre" class="form-control" @click="cambioComprobante()">
                                        <option value="FACTURA">FACTURA</option>
                                        <option value="PROFORMA" >PROFORMA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="number-input">Nº Autorizacion</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" v-model="autorizacion" v-if="tipoComprobante" ><p v-else>No Requiere Atuorización</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Llave de Dosificación</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="llave" class="form-control" placeholder="Ingrese Llave de Dosificación"  v-if="tipoComprobante"><p v-else>No Requiere LLave</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="number-input">Cantidad</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="cantidad" class="form-control" placeholder="Ingrese Cantidad de Facturas">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="date-input">Fecha Limite de Emision</label>
                                <div class="col-md-9">
                                    <input type="date" v-model="fechalim" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarComprobante()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarComprobante()">Actualizar</button>
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
    
    export default {
        data (){
            return {
                comprobante_id: 0,
                nombre : 'FACTURA',
                autorizacion : 0,
                llave : '',
                cantidad : 0,
                fechalim : '',
                arrayComprobante : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorComprobante : 0,
                errorMostrarMsjComprobante : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                tipoComprobante:false,
                validationErrors: ''
            }
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

            }
        },
        methods : {
         
            listarComprobante (page,buscar,criterio){
                let me=this;
                var url= '/comprobante?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayComprobante = respuesta.comprobante.data;
                    me.pagination= respuesta.pagination;
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
                me.listarComprobante(page,buscar,criterio);
            },
            registrarComprobante(){
                if (this.validarComprobante()){
                    return;
                }
                let me = this;
                axios.post('/comprobante/registrar',{
                    'nombre': me.nombre,
                    'autorizacion': me.autorizacion,
                    'llave': me.llave,
                    'cantidad':me.cantidad,
                    'fechalim': me.fechalim
                }).then((response)=>{
                    me.cerrarModal();
                    me.listarComprobante(1,'','nombre');
                    swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            actualizarComprobante(){
               if (this.validarComprobante()){
                    return;
                }
                let me = this;
                axios.put('/comprobante/actualizar',{
                    'nombre': me.nombre,
                    'autorizacion': me.autorizacion,
                    'llave': me.llave,
                    'cantidad':me.cantidad,
                    'fechalim': me.fechalim,
                    'id': me.comprobante_id
                }).then((response)=>{
                    me.cerrarModal();
                    me.listarComprobante(1,'','nombre');
                    swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';}); 
            },
            desactivarComprobante(id){
                const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
                    swal.fire({
                    title: 'Esta seguro de desactivar esta categoría?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                        let me = this;
                        axios.put('/comprobante/desactivar',{'id': id}).then(function (response) {
                        me.listarComprobante(1,'','nombre');
                        swal.fire(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {}
                    })
               
            },
            activarComprobante(id){
                const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
               swal.fire({
                    title: 'Esta seguro de activar esta categoría?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/comprobante/activar',{'id': id
                        }).then(function (response) {
                            me.listarComprobante(1,'','nombre');
                            swal.fire(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {}
                }) 
            },
            validarComprobante(){
                this.errorComprobante=0;
                this.errorMostrarMsjComprobante =[];

                if (!this.nombre) this.errorMostrarMsjComprobante.push("El nombre del comprobante no puede estar vacío.");
                if(this.nombre=='FACTURA'){
                    if (!this.autorizacion) this.errorMostrarMsjComprobante.push("El Nro. de Autorización no puede estar vacío.");
                    if (!this.llave) this.errorMostrarMsjComprobante.push("La Llave de Dosificación no puede estar vacía.");
                }
                if (!this.cantidad) this.errorMostrarMsjComprobante.push("La Cantidad No puede estar vacío.");
                if (!this.fechalim) this.errorMostrarMsjComprobante.push("La fecha Limite es Obligatoria.");

                if (this.errorMostrarMsjComprobante.length) this.errorComprobante = 1;

                return this.errorComprobante;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
                this.validationErrors='';
                this.errorComprobante=0;
                this.errorMostrarMsjComprobante='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "comprobante":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Comprobante';
                                this.nombre= '';
                                this.autorizacion = 0;
                                this.llave='';
                                this.cantidad=0;
                                this.fechalim='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Comprobante';
                                this.tipoAccion=2;
                                this.comprobante_id=data['id'];
                                this.nombre = data['nombre'];
                                this.autorizacion= data['num_autorizacion'];
                                this.llave= data['llave'];
                                this.cantidad=data['cantidad'];
                                this.fechalim= this.formatDate(data['fecha_lim_emision']);
                                if(this.nombre=='PROFORMA'){
                                    this.tipoComprobante=false;
                                }else{this.tipoComprobante=true};
                                break;
                            }
                        }
                    }
                }
            },
            cambioComprobante(){
                if(this.nombre=='FACTURA'){
                    this.tipoComprobante=true;
                }else{
                    this.tipoComprobante=false;
                }
            },
            formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2) 
                    month = '0' + month;
                if (day.length < 2) 
                    day = '0' + day;

                return [year, month, day].join('-');
            }
        },
        mounted() {
            this.listarComprobante(1,this.buscar,this.criterio);
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
</style>
