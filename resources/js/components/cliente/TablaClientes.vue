<template>
<tr>
    <td><input type="text" class="form-control" v-if="editMode" v-model="persona.nombre"/><p v-else>{{ persona.nombre }}</p></td>
    <td><div class="form-control" v-if="editMode">
        <select v-model="seleccionado">
            <option v-for="elemento in elementos" :key="elemento.text" v-bind:value="elemento.value">{{ elemento.text }}</option>
        </select>
        </div>
        <p v-else >{{persona.tipo_documento}}</p>
        </td>
    <td><input type="text" class="form-control" v-if="editMode" v-model="persona.num_documento"/><p v-else>{{ persona.num_documento }}</p></td>
    <td><input type="text" class="form-control" v-if="editMode" v-model="persona.direccion"/><p v-else>{{ persona.direccion }}</p></td>
    <td><input type="text" class="form-control" v-if="editMode" v-model="persona.telefono"/><p v-else>{{ persona.telefono }}</p></td>
    <td><input type="text" class="form-control" v-if="editMode" v-model="persona.email"/><p v-else>{{ persona.email }}</p>
        <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
    </td>
    <td>
        <template v-if="editMode">
            <button class="btn btn-success btn-sm" v-on:click="onClickUpdate()" title="Guardar"><i class="fa fa-save"></i></button>
            <button class="btn btn-info btn-sm" v-on:click="onClickCancelar()" title="Cancelar"><i class="fa fa-close"></i></button>
        </template>
        <template v-else>
            <button class="btn btn-warning btn-sm" v-on:click="onClickEdit()" title="¿Editar?"><i class="fa fa-edit"></i></button>
        </template>
    </td>
</tr>
</template>
<script>
    export default {
        data (){
            return {
                editMode : false,
                elementos: [{
                        value: '0',
                        text: 'Seleccionar...'
                    },
                    {
                        value: '1',
                        text: 'NIT'
                    },
                    {
                        value: '2',
                        text: 'CARNET DE IDENTIDAD'
                    },
                    ],
                seleccionado: "",
                validationErrors: ''
            }
        },
        props:['persona'],
        methods : {
            onClickEdit(){
                this.editMode = true;
                //console.log(this.elementos[1].text);
            },
            onClickUpdate(){
                let me = this;
                
                axios.put('/cliente/actualizar',{
                    'nombre': me.persona.nombre,
                    'tipo_documento': me.persona.tipo_documento,
                    'num_documento' : me.persona.num_documento,
                    'direccion' : me.persona.direccion,
                    'telefono' : me.persona.telefono,
                    'email' : me.persona.email,
                    'id': me.persona.id
                }).then( (response)=> {
                    me.editMode = false;
                    const persona=response.data;
                    me.$emit('update',persona);
                    console.log(response.data);
                }).catch( (error)=> {
                     if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                        }
                });
            },
            onClickCancelar(){
                this.editMode = false;
                this.validationErrors='';
            }, 
            validarPersona(){
                this.errorPersona=0;
                this.errorMostrarMsjPersona =[];

                if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacío.");

                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
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
