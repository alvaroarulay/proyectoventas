<template>
<tr>
    <td><input type="text" class="form-control" v-if="editMode" v-model="unidad.nombre"/><p v-else>{{ unidad.nombre }}</p></td>
    <td>
        <input type="text" class="form-control" v-if="editMode" v-model="unidad.descripcion"/><p v-else>{{ unidad.descripcion }}</p>
        <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
    </td>
    <td>
        <div v-if="unidad.condicion">
            <span class="badge badge-success">Activo</span>
        </div>
        <div v-else>
            <span class="badge badge-danger">Desactivado</span>
        </div>
        
    </td>
    <td>
        <template v-if="editMode">
            <button class="btn btn-success btn-sm" v-on:click="onClickUpdate()" title="Guardar"><i class="fa fa-save"></i></button>
            <button class="btn btn-info btn-sm" v-on:click="onClickCancelar()" title="Cancelar"><i class="fa fa-close"></i></button>
        </template>
        <template v-else>
            <button class="btn btn-warning btn-sm" v-on:click="onClickEdit()" title="¿Editar?"><i class="fa fa-edit"></i></button>
             <template v-if="unidad.condicion">
                <button type="button" class="btn btn-danger btn-sm" @click="desactivarUnidad(unidad.id)" title="¿Desactivar?"><i class="fa fa-trash"></i></button>
            </template>
            <template v-else>
                <button type="button" class="btn btn-info btn-sm" @click="activarUnidad(unidad.id)" title="¿Activar?"><i class="fa fa-edit"></i></button>
            </template>
        </template>
    </td>
</tr>
</template>

<script>
    export default {
        data (){
            return {
                editMode : false,
                errorUnidad : 0,
                errorMostraMsjUnidad : [],
                validationErrors: ''
            }
        },
        props:['unidad'],
        methods : {
            onClickEdit(){
                this.editMode = true;
            },
            onClickUpdate(){
                let me = this;
                axios.put('/unidades/actualizar',{
                    'nombre': me.unidad.nombre,
                    'descripcion': me.unidad.descripcion,
                    'id': me.unidad.id
                }).then((response)=> {
                    me.editMode = false;
                    swal.fire({
                            title:response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                    const unidad=response.data;
                    me.$emit('update',unidad);
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            onClickCancelar(){
                this.editMode = false;
                this.validationErrors='';
            }, 
            desactivarUnidad(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de desactivar esta unidad?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                    let me = this;
                    axios.put('/unidades/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.$emit('update',me.unidad);
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
            activarUnidad(id){
                 const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
               swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de activar esta unidad?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;

                        axios.put('/unidades/activar',{
                            'id': id
                        }).then(function (response) {
                            me.$emit('update',me.unidad);
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
            validarunidad(){
                this.errorUnidad=0;
                this.errorMostraMsjUnidad =[];
                if (!this.nombre) this.errorMostraMsjUnidad.push("El nombre de la unidad no puede estar vacío.");
                if (this.errorMostraMsjUnidad.length) this.errorUnidad = 1;
                return this.errorUnidad;
            }
        },
      
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
