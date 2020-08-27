<template>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1 class="titulo"><i class="fa fa-th-list"></i> Catalogo de Productos</h1> 
            <br>
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
     <!--catalogo-->
    <template v-if="listado==1">
        <div class="container text-center">
            <h1 class="titulo">Catalogo de Productos</h1>
            <hr>
            <div id="productos">
                <div v-for="item in arrayArticulo" :key="item.id">
                    <div class="product white-panel">
                        <h3 v-text="item.nombre"></h3><hr>
                        <img :src="'img/articulos/' + item.image" class="img-responsive" id="image" />
                        <div class="product-info card-panel">
                            <p v-text="item.descripcion"></p>
                            <span class="card mb-3 text-white bg-success"><strong>Precio: B$.{{item.precio}}</strong></span>
                            <p>
                                <button class="btn btn-warning" @click="addCarrito(item.id)"><i class="fa fa-cart-plus"></i> La Quiero</button>
                                <button class="btn btn-primary" @click="leerMas(item.id)"><i class="fa fa-chevron-circle-right"></i> Leer más</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </template>
    <!--Fin catalogo-->
    <!-- Detalle-->
    <template v-else-if="listado==0">
        
    </template>
    <!-- Fin Detalle-->
    <!-- Ver carrito -->
    <template v-else-if="listado==2">
        
    </template>
</main>
</template>

<script>
    export default {
        data (){
            return {
                listado:1,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : '',               
                arrayArticulo: [],
                idarticulo: 0,
                articulo: '',
                precio: 0,
                cantidad:0,
                stock:0,
                nomcli:'',
                doccli:'',
                nombre_comprobante:'',
                proforma:0,
                idventa:0,
                src:'',
                numPages: undefined,
            }
        },
        components: {
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
            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento)
                }
                return resultado;
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
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    #vueselect{
        margin: 10px;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
    #total{
        background:#00257A;
        color: aliceblue;
        font-size: 30px;
        text-align: center;
    }

</style>
