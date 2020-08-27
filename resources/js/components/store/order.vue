<template>
    <main class="app-content">
        <div class="container text-center">
            <div class="page-header">
                <h1 class="titulo"><i class="fa fa-shopping-cart"></i> Detalle del Pedido</h1>
            </div>
            <hr>
            <div class="page table-cart" >
                <div class="table-responsive">
                    <h3>Datos del Usuario</h3>
                    <table class="table table-striped table-hover table-bordered">
                        <tr><td>Nombre: </td><td v-text="usuario"></td> </tr>
                    </table>
                </div>
                <div class="table-responsive">
                    <h3>Datos del Pedido</h3>
                    <table class="table table-striped table-hover table-bordered">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                        <tr v-for="item in arrayCart" :key="item['id']">
                            <td>{{item['nombre']}}</td>
                            <td>B$. {{item['precio']}}</td>
                            <td>{{item['cantidad']}}</td>
                            <td>B$. {{item['precio'] * item['cantidad']}}</td>
                        </tr>
                    </table>
                    <hr>
                    <h3><label class="card mb-3 text-white bg-success">Total: B$. {{ total }}</label></h3>
                    <hr>
                    <button class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Regresar</button>
                    <button class="btn btn-warning">Procesar Pedido <i class="fa fa-chevron-circle-right"></i> </button>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
export default {
    data(){
        return{
            idusuario:0,
            usuario:'',
            arrayCart:[],
            total:0
        }
    },
    methods:{
        listarPedido(){
            axios.get('/order-detail').then((response)=>{
                this.arrayCart=response.data.cart;
                this.usuario=response.data.usuario;
                this.total = response.data.total;
            })
        }  
    },
    mounted(){
        this.listarPedido();
    }    
}
</script>