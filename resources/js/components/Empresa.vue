<template>
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Configuraciones</h1>
          <p>Datos de la Empresa</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Datos de la Empresa</h3>
                <div class="tile-body">
                <form class="row" enctype="multipart/form-data">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <label class="sr-only" for="enombre">Nombre</label>
                            <div class="input-group">
                                <input class="form-control" id="enombre" type="text" v-model="nombre" v-if="editMode">
                                <p v-else >{{nombre}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Número de Identificación Tributaria</label>
                            <label class="sr-only" for="enit">NIT</label>
                            <div class="input-group">
                                <input class="form-control" id="enit" type="number" v-model="nit" v-if="editMode">
                                <p v-else >{{nit}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Teléfono</label>
                            <label class="sr-only" for="etel">Teléfono</label>
                            <div class="input-group">
                                <input class="form-control" id="etel" type="text" v-model="telefono" v-if="editMode">
                                <p v-else >{{telefono}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Casa Matriz</label>
                            <label class="sr-only" for="edireccion">Dirección</label>
                            <div class="input-group">
                                <input class="form-control" id="edireccion" type="text" v-model="direccion" v-if="editMode">
                                <p v-else >{{direccion}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Mantenimiento</label>
                            <label class="sr-only" for="mantenimiento">Mantenimiento</label>
                            <div class="input-group">
                                <input class="form-control" id="mantenimiento" type="text" v-model="mantenimiento" v-if="editMode">
                                <p v-else >{{mantenimiento}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Impuesto</label>
                            <label class="sr-only" for="iva">Impuesto</label>
                            <div class="input-group">
                                <input class="form-control" id="iva" type="text" v-model="iva" v-if="editMode">
                                <p v-else >{{iva}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                            <div class="form-group">
                                <label class="control-label">Representante Legal</label>
                                <label class="sr-only" for="rnombre">Nombre</label>
                                <div class="input-group">
                                    <input class="form-control" id="rnombre" type="text" v-model="representante_legal" v-if="editMode">
                                    <p v-else >{{representante_legal}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <label class="sr-only" for="email">email</label>
                                <div class="input-group">
                                    <input class="form-control" id="email" type="email"  v-model="email" v-if="editMode">
                                    <p v-else >{{email}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Gran Acttividad</label>
                                <label class="sr-only" for="actividad">actividad</label>
                                <div class="input-group">
                                    <textarea cols="30" rows="5" class="form-control" id="actividad" v-model="actividad" v-if="editMode"></textarea>
                                    <p v-else >{{actividad}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Logo</label>
                                <div v-if="editMode">
                                     <div class="input-group"  v-if="imglocal" > 
                                        <img :src="imglocal" class="img-responsive" height="90" width="90">
                                    </div>  
                                    <input type="file" v-on:change="onImageChange" class="form-control" >
                                </div>
                                <div class="input-group" v-else>
                                    <img :src="'img/' + image" class="img-responsive" height="90" width="90"/>
                                </div>
                            </div>
                            <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                            <div v-show="errorEmpresa" class="alert alert-dismissible alert-danger">
                                <div class="text-center">
                                    <strong><div v-for="error in errorMostrarMsjEmpresa" :key="error" v-text="error"></div></strong>
                                </div>
                            </div>
                    </div>
                    <div class="tile-footer row">
                        <div class="col-md-12 col-md-offset-12">
                        <button v-if="editMode" class="btn btn-success" @click="onClickUpdate()" title="Guardar Cambio">
                        <i class="fa fa-save"></i>Guardar</button>
                        <button v-else class="btn btn-warning" @click.prevent="onClickEdit()" title="Editar">
                        <i class="fa fa-edit"></i>Editar</button>
                        <button class="btn btn-danger" v-if="editMode" @click.prevent="cancelar()"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
      </div>
    </main>
</template>
<script>
export default {
    data(){
            return {
                id:0,
                nombre:'',
                nit:0,
                telefono:'',
                direccion:'',
                representante_legal:'',
                email:'',
                actividad:'',
                image: '',
                imglocal:'',
                errorEmpresa:0,
                errorMostrarMsjEmpresa:[],
                empresa:[],
                mantenimiento:0,
                iva:0,
                editMode : false,
                validationErrors:''
            }
        },
         mounted() {
           this.mostrarEmpresa();
        },
        methods: {
            mostrarEmpresa(){
                axios.get('/empresa').then((response)=>{
                    this.empresa = response.data;
                    this.id=this.empresa[0].id;
                    this.nombre=this.empresa[0].nombre_empresa;
                    this.nit=this.empresa[0].nit;
                    this.telefono=this.empresa[0].telefono;
                    this.direccion=this.empresa[0].direccion;
                    this.representante_legal=this.empresa[0].representante_legal;  
                    this.email=this.empresa[0].email;
                    this.actividad=this.empresa[0].actividad;
                    this.image=this.empresa[0].image_name;
                    this.mantenimiento=this.empresa[0].mantenimiento;
                    this.iva=this.empresa[0].iva;
                })
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
            onClickEdit(){
                this.editMode = true;
            },
            onClickUpdate(){
                if (this.validarEmpresa()){
                    return;
                }
                let me = this;
                if(this.imglocal){
                    axios.put('/empresa/actualizar',{
                    'id':me.id,
                    'nombre': me.nombre,
                    'nit': me.nit,
                    'telefono' : me.telefono,
                    'direccion' : me.direccion,
                    'representante_legal' : me.representante_legal,
                    'email' : me.email,
                    'actividad':me.actividad,
                    'image': me.imglocal,
                    'mantenimiento':me.mantenimiento,
                    'iva':me.iva
                }).then((response)=>{
                    me.editMode=false;
                   if (response.data.success) {
                       Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: response.data.success,
                            showConfirmButton: false,
                            timer: 1500
                            });
                   }
                }).catch((error)=>{
                     if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                        }
                });
                }
                else{
                    axios.put('/empresa/actualizar',{
                    'id':me.id,
                    'nombre': me.nombre,
                    'nit': me.nit,
                    'telefono' : me.telefono,
                    'direccion' : me.direccion,
                    'representante_legal' : me.representante_legal,
                    'email' : me.email,
                    'actividad':me.actividad,
                    'image': me.image,
                    'mantenimiento':me.mantenimiento,
                    'iva':me.iva
                    }).then((response)=>{
                    if (response.data.success) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: response.data.success,
                            showConfirmButton: false,
                            timer: 1500
                            });
                    }
                    me.editMode=false;
                    }).catch((error)=>{
                         if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                        }
                    });
                }
               
            },
            validarEmpresa(){
                this.errorEmpresa=0;
                this.errorMostrarMsjEmpresa =[];

                if (!this.nombre) this.errorMostrarMsjEmpresa.push("El nombre de la Empresa no puede estar vacío.");
                if (!this.nit) this.errorMostrarMsjEmpresa.push("El NIT de la Empresa no puede estar vacío.");
                if (!this.telefono) this.errorMostrarMsjEmpresa.push("El teléfono de la Empresa no puede estar vacío.");
                if (!this.direccion) this.errorMostrarMsjEmpresa.push("La Dirección de la Empresa no puede estar vacío.");
                if (!this.representante_legal) this.errorMostrarMsjEmpresa.push("El Representante de la Empresa no puede estar vacío.");
                if (!this.email) this.errorMostrarMsjEmpresa.push("El E-mail de la Empresa no puede estar vacío.");
                if (!this.actividad) this.errorMostrarMsjEmpresa.push("El Actividad de la Empresa no puede estar vacío.");
                if (!this.mantenimiento) this.errorMostrarMsjEmpresa.push("El Mantenimiento de la Empresa no puede estar vacío.");
                if (!this.iva) this.errorMostrarMsjEmpresa.push("El Iva de la Empresa no puede estar vacío.");
                if (this.errorMostrarMsjEmpresa.length) this.errorEmpresa = 1;

                return this.errorEmpresa;
            },
            cancelar(){
                let me = this;
                me.editMode=false;
                me.imglocal='';
                me.validationErrors='';
                this.mostrarEmpresa();
            }
        }
    }
</script>