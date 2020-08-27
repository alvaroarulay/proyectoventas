<template>
    <div>
         <button type="button" @click="abrirModal()" class="btn btn-secondary">
            <i class="fa fa-plus"></i>&nbsp;Nuevo
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
                        <div  v-show="errorUnidad" class="alert alert-dismissible alert-danger">
                            <div class="text-center">
                                <strong><div v-for="error in errorMostrarMsjUnidad" :key="error" v-text="error"></div></strong>
                            </div>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la unidad">                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Descripción">                                        
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="registrarUnidad()">Guardar</button>
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
                unidad_id: 0,
                nombre : '',
                descripcion : '',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorUnidad : 0,
                errorMostrarMsjUnidad : [],
                validationErrors: ''
            }
        },
        methods : {
            registrarUnidad(){
                if (this.validarUnidad()){
                    return;
                }
                let me = this;
                axios.post('/unidades/registrar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                }).then((response)=>{
                    me.cerrarModal();
                    swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                    const unidad = response.data;
                    me.$emit('new',unidad);
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            validarUnidad(){
                this.errorUnidad=0;
                this.errorMostrarMsjUnidad =[];
                if (!this.nombre) this.errorMostrarMsjUnidad.push("El nombre de la Unidad no puede estar vacío.");
                if (this.errorMostrarMsjUnidad.length) this.errorUnidad = 1;
                return this.errorUnidad;
            },
            cerrarModal(){
                this.unidad_id= 0;
                this.nombre='';
                this.descripcion='';
                this.modal=0;
                this.tituloModal='';
                this.tipoAccion=0;
                this.errorUnidad=0;
                this.errorMostrarMsjUnidad=[];
                this.validationErrors='';
            },
            abrirModal(){
                this.modal = 1;
                this.tituloModal = 'Registrar Unidad';
                this.nombre='';
                this.descripcion='';
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
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>