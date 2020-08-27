<template>
<tr>
    <td><input type="text" class="form-control" v-if="editMode" v-model="marca.nombre"/><p v-else>{{ marca.nombre }}</p></td>
    <td><input type="text" class="form-control" v-if="editMode" v-model="marca.descripcion"/><p v-else>{{ marca.descripcion }}</p></td>
    <td><input type="text" class="form-control" v-if="editMode" v-model="marca.inicial"/><p v-else>{{ marca.inicial }}</p><validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors></td>
    <td>
        <div v-if="marca.condicion">
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
             <template v-if="marca.condicion">
                <button type="button" class="btn btn-danger btn-sm" @click="desactivarMarca(marca.id)" title="¿Desactivar?"><i class="fa fa-trash"></i></button>
            </template>
            <template v-else>
                <button type="button" class="btn btn-info btn-sm" @click="activarMarca(marca.id)" title="¿Activar?"><i class="fa fa-edit"></i></button>
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
                errorMarca : 0,
                errorMostrarMsjMarca : [],
                validationErrors: ''
            }
        },
        props:['marca'],
        methods : {
            onClickEdit(){
                this.editMode = true;
            },
            onClickUpdate(){
                let me = this;
                axios.put('/marcas/actualizar',{
                    'nombre': me.marca.nombre,
                    'descripcion': me.marca.descripcion,
                    'inicial' : me.marca.inicial,
                    'id': me.marca.id
                }).then((response)=> {
                    me.editMode = false;
                    swal.fire({
                            title: response.data.success,
                            showConfirmButton: false,
                            showCancelButton: false,
                            icon: "success",
                            timer:2000
                            });
                    const marca=response.data;
                    me.$emit('update',marca);
                }).catch((error)=>{this.validationErrors = (error.response.status == 422)? error.response.data.errors : '';});
            },
            onClickCancelar(){
                this.editMode = false;
                this.validationErrors='';
            }, 
            desactivarMarca(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de desactivar esta Marca?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                    let me = this;
                    axios.put('/marcas/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.$emit('update',me.marca);
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
            activarMarca(id){
                 const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })
               swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de activar esta Marca?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar!!',
                    cancelButtonText: 'Cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;

                        axios.put('/marcas/activar',{
                            'id': id
                        }).then(function (response) {
                            me.$emit('update',me.marca);
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
            validarMarca(){
                this.errorMarca=0;
                this.errorMostrarMsjMarca =[];
                if (!this.nombre) this.errorMostrarMsjMarca.push("El nombre de la marca no puede estar vacío.");
                if (!this.inicial) this.errorMostrarMsjMarca.push("La inicial de la marca no puede estar vacío.");
                if (this.errorMostrarMsjMarca.length) this.errorMarca = 1;
                return this.errorMarca;
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
