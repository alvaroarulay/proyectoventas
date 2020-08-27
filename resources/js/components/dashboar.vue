<template>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-archive"></i> Selabí</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
          <div class="widget-small primary"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
              <h4>Usuarios</h4>
              <strong><p v-text="usuarios"></p></strong>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small info"><i class="icon fa fa-money fa-3x"></i>
            <div class="info">
              <h4>Ventas</h4>
              <strong><p v-text="ventas"></p></strong>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small warning"><i class="icon fa fa-money fa-3x"></i>
            <div class="info">
              <h4>Compras</h4>
              <strong><p v-text="compras"></p></strong>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small danger"><i class="icon fa fa-archive fa-3x"></i>
            <div class="info">
              <h4>Productos</h4>
              <strong><p v-text="productos"></p></strong>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
            <div class="tile">
            <h4 class="tile-title">Productos Mayor Stock (Top 10)</h4>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="lineChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h4 class="tile-title">Productos más Caros (Top 10)</h4>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="chartCaros"></canvas>
            </div>
          </div>
        </div>
      </div>
</main>
</template>
<script>
export default {
        data (){
            return {
                usuarios:0,
                ventas:0,
                compras:0,
                productos:0,
                articulosStock:null,
                charArticulosStock:null,
                mayorStock:[],
                varStock:[],
                varArticulo:[],
                varCaros:null,
                charCaros:null,
                caros:[],
                varTotalCaros:[],
                varNombreCaros:[]
            }
        },
        methods : {
            listar (){
                let me=this;
                axios.get('/articulo/cantidadArticulo').then((response)=>{me.productos=response.data;}).catch((error)=>{console.log(error);});
                axios.get('/venta/cantidad').then((response)=>{me.ventas=response.data}).catch((error)=>{console.log(error)});
                axios.get('/ingreso/cantidad').then((response)=>{me.compras=response.data}).catch((error)=>{console.log(error)});
                axios.get('/user/cantidad').then((response)=>{me.usuarios=response.data}).catch((error)=>{console.log(error)});
            },
            getMayorStock(){
              let me = this;
              var url = '/dashboard';
              axios.get(url).then((response)=>{
                var respuesta = response.data;
                me.mayorStock = respuesta.mayorStock;
                me.loadMayorStock();
              }).catch((error)=>{console.log(error)});
            },
            getMasCaros(){
              let me = this;
              var url = '/dashboard';
              axios.get(url).then((response)=>{
                var respuesta = response.data;
                me.caros = respuesta.masCaros;
                me.loadCaros();
              }).catch((error)=>{console.log(error)});
            },
            loadMayorStock(){
              let me = this;
              me.mayorStock.map((x)=>{
                me.varStock.push(x.stock);
                me.varArticulo.push(x.nombre);
              });
              me.articulosStock=document.getElementById('lineChart').getContext('2d');
              me.charArticulosStock = new Chart(me.articulosStock,{
                type: 'bar',
                data:{
                  labels: me.varArticulo,
                  datasets:[{
                    label: 'Articulos con Mayor Stock',
                    data: me.varStock,
                    backgroundColor:'rgba(0,150,136,0.5)',
                    borderColor:'rgba(0,150,136,0.5)',
                    borderWidth: 1
                  }]
                },
                options:{
                  scales:{
                    yAxes:[{
                      ticks:{
                        beginAtZero:true
                      }
                    }]
                  }
                }
              });
            },
            loadCaros(){
              let me = this;
              me.caros.map((x)=>{
                me.varTotalCaros.push(x.precio);
                me.varNombreCaros.push(x.nombre);
              });
              me.varCaros=document.getElementById('chartCaros').getContext('2d');
              me.charCaros = new Chart(me.varCaros,{
                type: 'bar',
                data:{
                  labels: me.varNombreCaros,
                  datasets:[{
                    label: 'Articulos más Caros',
                    data: me.varTotalCaros,
                    backgroundColor:'rgba(220,53,69,0.5)',
                    borderColor:'rgba(220,53,69,0.5)',
                    borderWidth: 1
                  }]
                },
                options:{
                  scales:{
                    yAxes:[{
                      ticks:{
                        beginAtZero:true
                      }
                    }]
                  }
                }
              });
            },
        },
        mounted(){
          this.listar();
          this.getMayorStock();
          this.getMasCaros();
        }
    };
    
</script>  