<template>
<main class="app-content">
    <div class="app-title">
        <div>
         <h1><i class="fa fa-align-justify"></i> Clientes</h1>
        <br>
        <nuevocliente @new="addCliente"></nuevocliente>
        </div>
    </div>
  <section>
    <div class="form-group row">
        <div class="col-md-6">
            <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                    <option value="nombre">Nombre</option>
                    <option value="num_documento">Documento</option>
                    <option value="email">Email</option>
                    <option value="telefono">Teléfono</option>
                </select>
                <input type="text" v-model="buscar" @keyup.enter="listarPersona(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                <button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </div>
    </div>
    <div class="table-responsive-sm tile table-responsive">
        <table class="table table-bordered table-striped table-sm tile-body" >
            <tr>
                <th>Nombre</th>
                <th>Tipo Documento</th>
                <th>Número</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Opciones</th>
            </tr>
            <tablaclientes v-for="(persona, index) in arrayPersona" :key="persona.id" :persona='persona' @update="updateCliente(index, ...arguments)"></tablaclientes>
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
  </section>
</main>
</template>

<script>
    export default {
         data (){
            return {
                arrayPersona : [],
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
                editMode : false
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
        methods:{
            listarPersona (page,buscar,criterio){
                let me=this;
                var url= '/cliente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPersona = respuesta.personas.data;
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
                me.listarPersona(page,buscar,criterio);
            },
            addCliente(persona){
                this.listarPersona(1,'','nombre');
            },
            updateCliente(index, persona){
                this.listarPersona(1,'','nombre'); 
            }
        },
          mounted() {
            this.listarPersona(1,this.buscar,this.criterio);
        }
    }
</script>
