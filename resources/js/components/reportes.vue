<template>
<main class="app-content">
    <div class="app-title">
        <div>
          <h1><i class="fa fa-bar-chart"></i> Reportes Gráficos</h1> 
        </div>
    </div>
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Reporte de Ventas</h3>
            <div class="d-flex justify-content-center">
                <button class="btn btn-info botonReporte" @click="abrirModal('ventas','diario')">Ver Gráfico Diario</button>
                <button class="btn btn-info botonReporte" @click="abrirModal('ventas','mensual')">Ver Gráfico del Mes</button>
            </div>
            <div>
                <div class="tile-title-w-btn">
                <h4 class="title">Fecha Inicial</h4>
                </div>
                <div class="tile-body">
                <input class="form-control" id="demoDate" type="date">
                </div>
            </div>
             <div>
                <div class="tile-title-w-btn">
                <h4 class="title">Fecha Final</h4>
                </div>
                <div class="tile-body">
                <input class="form-control" id="demoDate" type="date">
                </div>
            </div>
            <div class="tile-footer">
                <div class="pull-rigth">
                    <button class="btn btn-primary" @click="abrirModal('ventas','fecha')">Ver Gráfico</button>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Reporte de Compras</h3>
             <div class="d-flex justify-content-center">
                <button class="btn btn-info botonReporte" @click="abrirModal('compras','diario')">Ver Gráfico Diario</button>
                <button class="btn btn-info botonReporte" @click="abrirModal('compras','mensual')">Ver Gráfico del Mes</button>
            </div>
            <div>
                <div class="tile-title-w-btn">
                <h4 class="title">Fecha Inicial</h4>
                </div>
                <div class="tile-body">
                <input class="form-control" id="demoDate" type="date" placeholder="Seleccione un Fecha">
                </div>
            </div>
             <div>
                <div class="tile-title-w-btn">
                <h4 class="title">Fecha Final</h4>
                </div>
                <div class="tile-body">
                <input class="form-control" id="demoDate" type="date" placeholder="Seleccione un Fecha">
                </div>
            </div>
            <div class="tile-footer">
                <div class="pull-rigth">
                    <button class="btn btn-primary" @click="abrirModal('compras','fecha')">Ver Gráfico</button>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- Fin ejemplo de tabla Listado -->
    <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; " aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="tipoChart==1">
                        <div class="tile">
                            <div class="embed-responsive embed-responsive-16by9">
                                <canvas class="embed-responsive-item" id="vdiario" ></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="tipoChart==2">
                        <div class="tile">
                            <div class="embed-responsive embed-responsive-16by9">
                                <canvas class="embed-responsive-item" id="vmes" ></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="tipoChart==3">
                        <div class="tile">
                            <div class="embed-responsive embed-responsive-16by9">
                                <canvas class="embed-responsive-item" id="cdiario" ></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="tipoChart==4">
                        <div class="tile">
                            <div class="embed-responsive embed-responsive-16by9">
                                <canvas class="embed-responsive-item" id="cmes" ></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
</main>
</template>

<script>
    export default {
        data (){
            return {
                modal:0,
                tituloModal:'',
                varVentasDia:null,
                charVentasDia:null,
                ventasDia:[],
                varTotalVentasDia:[],
                varClientesDia:[],
                varVentasMes:null,
                charVentasMes:null,
                ventasMes:[],
                varTotalVentasMes:[],
                varDiasMes:[],
                tipoChart:0,
            }
        },
        computed:{
            
        },
        methods : {
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';

                this.varVentasDia=null;
                this.charVentasDia=null;
                this.ventasDia=[];
                this.varTotalVentasDia=[];
                this.varClientesDia=[];

                this.varVentasMes=null;
                this.charVentasMes=null;
                this.ventasMes=[];
                this.varTotalVentasMes=[];
                this.varDiasMes=[];
            },
            abrirModal(modelo, accion){
                switch(modelo){
                    case "ventas":
                    {
                        switch(accion){
                            case 'diario':
                            {   
                                let me = this;
                                me.modal = 1;
                                me.tituloModal = 'Gráfico Diario';
                                me.tipoChart=1;
                                var url = '/venta/charVentas';
                                axios.get(url).then((response)=>{
                                    var respuesta = response.data;
                                    me.ventasDia = respuesta.ventasDia;
                                    me.ventasDia.map((x)=>{
                                        me.varTotalVentasDia.push(x.total);
                                        me.varClientesDia.push(x.nombre);
                                    });
                                    me.varVentasDia=document.getElementById('vdiario').getContext('2d');
                                    me.charVentasDia = new Chart(me.varVentasDia,{
                                        type: 'line',
                                        data:{
                                        labels: me.varClientesDia,
                                        datasets:[{
                                            label: 'Total Venta',
                                            data: me.varTotalVentasDia,
                                            backgroundColor:'rgba(0,174,249,0.5)',
                                            borderColor:'rgba(0,174,249,0.5)',
                                            borderWidth: 1
                                        }]
                                        },
                                        options:{
                                        scales:{
                                            yAxes:[{
                                            ticks:{
                                                beginAtZero:true,
                                                callback:(value,index,values)=>{
                                                    return 'Bs. ' + value;
                                                }
                                            }
                                            }]
                                        }
                                        }
                                    });
                                }).catch((error)=>{console.log(error)});
                                break;
                            }
                            case 'mensual':
                            {
                                let me = this;
                                me.modal=1;
                                me.tituloModal='Gráfico Mensual';
                                me.tipoChart=2;
                                var url = '/venta/charVentas';
                                axios.get(url).then((response)=>{
                                    var respuesta = response.data;
                                    me.ventasMes = respuesta.ventasMes;
                                   me.ventasMes.map((x)=>{
                                        me.varTotalVentasMes.push(x.total);
                                        me.varDiasMes.push(x.dia);
                                    });
                                    me.varVentasMes=document.getElementById('vmes').getContext('2d');
                                    me.charVentasMes = new Chart(me.varVentasMes,{
                                        type: 'bar',
                                        data:{
                                        labels: me.varDiasMes,
                                        datasets:[{
                                            label: 'Total Día',
                                            data: me.varTotalVentasMes,
                                            backgroundColor:'rgba(0,174,249,0.5)',
                                            borderColor:'rgba(0,174,249,0.5)',
                                            borderWidth: 1
                                        }]
                                        },
                                        options:{
                                        scales:{
                                            yAxes:[{
                                            ticks:{
                                                beginAtZero:true,
                                                callback:(value,index,values)=>{
                                                    return 'Bs. ' + value;
                                                }
                                            }
                                            }],
                                            xAxes:[{
                                            ticks:{
                                                beginAtZero:true,
                                                callback:(value,index,values)=>{
                                                    return 'Dia ' + value;
                                                }
                                            }
                                            }]
                                        }
                                        }
                                    });
                                }).catch((error)=>{console.log(error)});
                                break;
                            }
                            case 'fecha':
                            {
                                this.modal=1;
                                this.tituloModal='Gráfico por Fechas';
                                break;
                            }
                        }
                    };
                    case "compras":
                    {
                        switch(accion){
                            case 'diario':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Gráfico Diario';
                                break;
                            }
                            case 'mensual':
                            {
                                this.modal=1;
                                this.tituloModal='Gráfico Mensual';
                                break;
                            }
                            case 'fecha':
                            {
                                this.modal=1;
                                this.tituloModal='Gráfico por Fechas';
                                break;
                            }
                        }
                    };
                }
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
    .botonReporte{
        border-radius: 50px;
        margin: 5px;
    }
</style>
