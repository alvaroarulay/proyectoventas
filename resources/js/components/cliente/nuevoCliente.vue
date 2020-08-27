<template>
    <div>
         <button type="button" @click="abrirModal()" class="btn btn-secondary">
            <i class="fa fa-plus"></i>&nbsp;Nuevo Cliente
        </button>
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
                        <div v-show="errorPersona" class="alert alert-dismissible alert-danger">
                            <div class="text-center">
                                <strong><div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error"></div></strong>
                            </div>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                <div class="col-md-9">
                                    <select v-model="tipo_documento" class="form-control">
                                        <option value="CARNET DE IDENTIDAD">CARNET DE IDENTIDAD</option>
                                        <option value="NIT">NIT</option>
                                    </select>                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Número</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_documento" class="form-control" placeholder="Número de documento">                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </div>
</template>
<script>
export default {
        data (){
            return {
                persona_id: 0,
                nombre : '',
                tipo_documento : 'CARNET DE IDENTIDAD',
                num_documento : '',
                direccion : '',
                telefono : '',
                email : '',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPersona : 0,
                errorMostrarMsjPersona : [],
                validationErrors: ''
            }
        },
        methods : {
            registrarPersona(){
                if (this.validarPersona()){
                    return;
                }
                let me = this;
                axios.post('/cliente/registrar',{
                    'nombre': me.nombre,
                    'tipo_documento': me.tipo_documento,
                    'num_documento' : me.num_documento,
                    'direccion' : me.direccion,
                    'telefono' : me.telefono,
                    'email' : me.email
                }).then((response)=> {
                    const persona = response.data.persona;
                    me.$emit('new',persona);
                    me.cerrarModal();
                }).catch((error)=> {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                        }
                });
            },
            validarPersona (){
                this.errorPersona=0;
                this.errorMostrarMsjPersona =[];
                if (!this.nombre){this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacío.");};
                if (!this.num_documento){this.errorMostrarMsjPersona.push("El número de documento no puede estar vacío.");};
                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;
                return this.errorPersona;
                this.errors = [];
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.tipo_documento='CARNET DE IDENTIDAD';
                this.num_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.errorPersona=0;
                this.validationErrors=''
            },
            abrirModal(){
                this.modal = 1;
                this.tituloModal = 'Registrar Cliente';
                this.nombre= '';
                this.tipo_documento='CARNET DE IDENTIDAD';
                this.num_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
            }
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