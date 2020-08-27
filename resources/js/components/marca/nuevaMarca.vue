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
                        <div v-show="errorMarca" class="alert alert-dismissible alert-danger">
                            <div class="text-center">
                                <strong><div v-for="error in errorMostrarMsjMarca" :key="error" v-text="error"></div></strong>
                            </div>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la marca">                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Descripción">                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Inicial de Marca</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="inicial_marca" class="form-control" placeholder="Incial de Marca">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="registrarMarca()">Guardar</button>
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
                marca_id: 0,
                nombre : '',
                descripcion : '',
                inicial_marca : '',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMarca : 0,
                errorMostrarMsjMarca : [],
                validationErrors: ''
            }
        },
        methods : {
            registrarMarca(){
                if (this.validarMarca()){
                    return;
                }
                let me = this;
                axios.post('/marcas/registrar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'inicial' : this.inicial_marca
                }).then((response)=>{
                    me.cerrarModal();
                    const marca = swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });response.data;

                    me.$emit('new',marca);
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            validarMarca(){
                this.errorMarca=0;
                this.errorMostrarMsjMarca =[];
                if (!this.nombre) this.errorMostrarMsjMarca.push("El nombre de la marca no puede estar vacío.");
                if (!this.inicial_marca) this.errorMostrarMsjMarca.push("La inicial de la marca no puede estar vacío.");
                if (this.errorMostrarMsjMarca.length) this.errorMarca = 1;
                return this.errorMarca;
            },
            cerrarModal(){
                this.marca_id= 0;
                this.nombre='';
                this.descripcion='';
                this.inicial_marca='';
                this.modal=0;
                this.tituloModal='';
                this.tipoAccion=0;
                this.errorMarca=0;
                this.errorMostrarMsjMarca=[];
                this.validationErrors='';
            },
            abrirModal(){
                this.modal = 1;
                this.tituloModal = 'Registrar Marca';
                this.nombre='';
                this.descripcion='';
                this.inicial_marca='';
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