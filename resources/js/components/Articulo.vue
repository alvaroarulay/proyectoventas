<template>
<main class="app-content">
    <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Articulos</h1> 
          <br>
          <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
          <button type="button" @click="cargarPdf()" class="btn btn-info"><i class="fa fa-file-pdf"></i>&nbsp;Reporte</button>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                    <option value="nombre">Nombre</option>
                    <option value="descripcion">Descripción</option>
                </select>
                <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                <button type="submit" @click="listarArticulo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body table-responsive">
              <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                  <tr style="background:#6c757d; color:white;">
                    <th>Imagen</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Unidades</th>
                    <th>Marca</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                        <td><img :src="'img/articulos/' + articulo.image" class="img-responsive" height="90" width="90"/></td>
                        <td v-text="articulo.codigo"></td>
                        <td v-text="articulo.nombre"></td>
                        <td v-text="articulo.nom_categoria"></td>
                        <td v-text="articulo.nom_unidad"></td>
                        <td v-text="articulo.nom_marca"></td>
                        <td v-text="articulo.descripcion"></td>
                        <td >
                             <div v-if="articulo.stock < 5">
                                <span class="badge badge-danger">{{articulo.stock}}</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-success">{{articulo.stock}}</span>
                            </div>
                        </td>
                        <td v-text="articulo.precio"></td>
                        <td>
                            <div v-if="articulo.condicion">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-danger">Desactivado</span>
                            </div>
                            
                        </td>
                        <td>
                            <button type="button" @click="abrirModal('articulo','actualizar',articulo)" class="btn btn-warning btn-sm" title="¿Editar?">
                                <i class="fa fa-edit"></i>
                            </button> &nbsp;
                            <template v-if="articulo.condicion">
                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)" title="¿Desactivar?">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)" title="¿Activar?">
                                    <i class="fa fa-check"></i>
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
                            <div v-show="errorArticulo" class="alert alert-dismissible alert-danger">
                                <div class="text-center">
                                    <strong><div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error"></div></strong>
                                </div>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idcategoria">
                                            <option value="0">Seleccione....</option>
                                            <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Marcas</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idmarca">
                                            <option value="0">Seleccione....</option>
                                            <option v-for="marca in arrayMarca" :key="marca.id" :value="marca.id" v-text="marca.nombre"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Unidades</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idunidad">
                                            <option value="0">Seleccione....</option>
                                            <option v-for="unidad in arrayUnidad" :key="unidad.id" :value="unidad.id" v-text="unidad.nombre"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6"><input type="text" v-model="codigo" class="form-control" placeholder="Código de barras"> </div>
                                            <div class="col-md-6"><button type="button" class="btn btn-warning" @click="crearCodigo()">Generar Codigo</button></div>
                                        </div>
                                        <barcode :value="codigo" :options="{ format: 'EAN-13' }">
                                            Generando código de barras.    
                                        </barcode>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de artículo">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="form-control-label col-md-3">Imagen</label>
                                    <div class="form-group">   
                                        <div>
                                            <input type="file" v-on:change="onImageChange" class="form-control" >
                                        </div>
                                        <div class="input-group" v-if="imglocal" >
                                            <img :src="imglocal" class="img-responsive" height="90" width="90">
                                        </div>  
                                    </div>
                                 </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarArticulo()">Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    export default {
        data (){
            return {
                articulo_id: 0,
                idcategoria : 0,
                nombre_categoria : '',
                inicial_categoria:'',
                idmarca:0,
                nombre_marca:'',
                inicial_marca:'',
                idunidad:0,
                nombre_unidad:'',
                codigo : '',
                nombre : '',
                descripcion : '',
                arrayArticulo : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorArticulo : 0,
                errorMostrarMsjArticulo : [],
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
                arrayCategoria :[],
                arrayMarca:[],
                arrayUnidad:[],
                image: '',
                imglocal:'',
                validationErrors: ''
            }
        },
        components: {
        'barcode': VueBarcode
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
            listarArticulo (page,buscar,criterio){
                let me=this;
                var url= '/articulo?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cargarPdf(){
                window.open('http://ventas.local/articulo/listarPdf','_blank');
            },
            selectCategorias(){
                let me=this;
                axios.get('/categoria/selectCategoria').then((response)=>{
                    var respuesta= response.data;
                    me.arrayCategoria = respuesta.categorias;
                }).catch((error)=> {console.log(error);});
            },
            selectMarcas(){
                let me=this;
                axios.get('/marcas/selectMarca').then((response)=> {
                    var respuesta= response.data;
                    me.arrayMarca = respuesta.marcas;
                }).catch((error)=>{console.log(error);});
            },
            selectUnidades(){
                let me=this;
                axios.get('/unidades/selectUnidad').then((response)=>{
                    var respuesta= response.data;
                    me.arrayUnidad = respuesta.unidades;
                }).catch((error)=>{console.log(error);});
            },
            crearCodigo(){
                for(let i=0;i<this.arrayCategoria.length;i++){
                    if(this.idcategoria==this.arrayCategoria[i].id){
                        this.inicial_categoria=this.arrayCategoria[i].inicial;
                        this.nombre_categoria=this.arrayCategoria[i].nombre;
                    }
                }
                for(let j=0;j<this.arrayMarca.length;j++){
                    if(this.idmarca==this.arrayMarca[j].id){
                        this.inicial_marca=this.arrayMarca[j].inicial;
                    }
                }
                let c=1;
                for(let indice=0;indice<this.arrayArticulo.length;indice++){
                    if(this.idcategoria==this.arrayArticulo[indice].idcategoria && this.idmarca == this.arrayArticulo[indice].idmarca){
                        c++;
                    }
                }
                if(c<10){
                    this.codigo=this.inicial_categoria+this.inicial_marca+'000'+(c+1);
                }
                else{
                    if(c<100){
                    this.codigo=this.inicial_categoria+this.inicial_marca+'00'+(c+1);
                    }
                    else{
                        if(c<1000){
                            this.codigo=this.inicial_categoria+this.inicial_marca+'0'+(c+1);
                        }
                        else{this.codigo=this.inicial_categoria+this.inicial_marca+(c+1);}
                    }
                }
                this.c=0;
            },
            onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let me = this;
                reader.onload = (e) => {
                    me.imglocal = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarArticulo(page,buscar,criterio);
            },
            registrarArticulo(){
                if (this.validarArticulo()){
                    return;
                }
                let me = this;
                axios.post('/articulo/registrar',{
                    'idcategoria': this.idcategoria,
                    'idmarca':this.idmarca,
                    'idunidad':this.idunidad,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'imglocal':this.imglocal,
                    'descripcion': this.descripcion
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarArticulo(1,'','nombre');
                    swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            actualizarArticulo(){
               if (this.validarArticulo()){
                    return;
                }
                let me = this;
                axios.put('/articulo/actualizar',{
                    'idcategoria': this.idcategoria,
                    'idmarca':this.idmarca,
                    'idunidad':this.idunidad,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'imglocal':this.imglocal,
                    'descripcion': this.descripcion,
                    'id': this.articulo_id
                }).then((response)=>{
                    me.cerrarModal();
                    swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                    me.listarArticulo(1,'','nombre');
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            desactivarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de desactivar este articulo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/articulo/desactivar',{
                        'id': id
                        }).then(function (response) {
                        me.listarArticulo(1,'','nombre');
                        swal(
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
                        ) {
                            
                        }
                    })
            },
            activarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de activar este articulo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/articulo/activar',{
                        'id': id
                        }).then(function (response) {
                        me.listarArticulo(1,'','nombre');
                        swal(
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
                        ) {
                            
                        }
                    })
               
            },
            validarArticulo(){
                this.errorArticulo=0;
                this.errorMostrarMsjArticulo =[];

                if (this.idcategoria==0) this.errorMostrarMsjArticulo.push("Seleccione una categoría.");
                if (this.idmarca==0) this.errorMostrarMsjArticulo.push("Seleccione una Marca.");
                if (this.idunidad==0) this.errorMostrarMsjArticulo.push("Seleccione una Unidad.");
                if (!this.nombre) this.errorMostrarMsjArticulo.push("El nombre del artículo no puede estar vacío.");
                if (!this.codigo) this.errorMostrarMsjArticulo.push("No Genero un codigo de Articulo.");
                if (!this.imglocal) this.errorMostrarMsjArticulo.push("No subio una imagen del Articulo.");

                if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

                return this.errorArticulo;
            },
            cerrarModal(){
                this.modal=0;
                this.reset();
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "articulo":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Artículo';
                                this.idcategoria=0;
                                this.nombre_categoria='';
                                this.nombre_marca='';
                                this.nombre_unidad='';
                                this.codigo='';
                                this.nombre= '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Artículo';
                                this.tipoAccion=2;
                                this.articulo_id=data['id'];
                                this.idcategoria=data['idcategoria'];
                                this.idmarca=data['idmarca'];
                                this.idunidad=data['idunidad'];
                                this.codigo=data['codigo'];
                                this.nombre = data['nombre'];
                                this.descripcion= data['descripcion'];
                                break;
                            }
                        }
                    }
                }
                this.selectCategorias();
                this.selectMarcas();
                this.selectUnidades();
            },
            reset(){
                this.tituloModal='';
                this.idcategoria= 0;
                this.nombre_categoria = '';
                this.nombre_marca='';
                this.nombre_unidad='';
                this.codigo = '';
                this.nombre = '';
                this.descripcion = '';
                this.errorArticulo=0;
                this.imglocal='';
                this.image='';
                this.codigo='';
                this.idcategoria=0;
                this.idmarca=0;
                this.idunidad=0;
                this.arrayUnidad=[];
                this.arrayCategoria=[];
                this.arrayMarca=[];
                this.validationErrors='';
            }
        },
        mounted() {
            this.listarArticulo(1,this.buscar,this.criterio);
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
</style>
