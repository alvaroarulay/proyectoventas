<template>
    <main class="app-content">
        <div class="app-title">
            <div class="text-center">
                <h1><i class="fa fa-chart"></i> Ventas Totales</h1>
                <p>Vendedor x Categoria de Articulo</p>
            </div>
        </div>
        <div class="row tile">
            <div class="col-md-12">
                <select class="form-control" @change="onChange($event)">
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>    
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="embed-responsive embed-responsive-16by9 tile">
                    <canvas class="embed-responsive-item" id="ventas" ></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="embed-responsive embed-responsive-16by9 tile">
                    <canvas class="embed-responsive-item" id="vendedores" ></canvas>
                </div>
            </div>
        </div>
        <div class="table-responsive tile text-center">
            <h4 class="titulo">Top de Vendedores</h4>
            <table class="table table-bordered table-hover">
                <tr class="table-info">
                    <th>Nombre de Vendedor</th>
                    <th>Cantidad de Venta en Bs.</th>
                </tr>
                 <tr v-for="v in arrayVV" :key="v.nombre">
                     <td v-text="v.nombre"></td>
                     <td v-text="v.total"></td>
                 </tr>
            </table>
        </div>
    </main>
</template>
<script>
export default {
    data(){
        return{
            meses:1,
            categorias:1,
            arrayVV:[],
            arrayVirtuales:[],
            arrayPresenciales:[],

            varVentasDia:'',
            charVentasDia:'',
            varClientesDia:[],
            varTotalVentasDia:[],

            varVirtuales:'',
            charVirtuales:'',
            varTotalVentasVirtuales:[],
            varTotalVentasPresenciales:[],
            varDias:[],

        }
    },
    methods:{
        ventas(mes){
            let me = this;
            var url = '/venta/charVentas?mes=' + mes;
            console.log(url);
            axios.get(url).then((response)=>{
                me.arrayVV=response.data.vv;
                me.arrayVirtuales=response.data.vVirtual;
                me.arrayPresenciales=response.data.vPresencial;
                me.ventasxVendedor();
                me.virtualesPresenciales();
            }).catch((error)=>{console.log(error)});
        },
        ventasxVendedor(){
            let me = this;
             me.arrayVV.map((x)=>{
                me.varClientesDia.push(x.nombre);
                me.varTotalVentasDia.push(x.total);
            });
            me.varVentasDia=document.getElementById('vendedores').getContext('2d');
            me.charVentasDia = new Chart(me.varVentasDia,{
                type: 'pie',
                data:{labels: me.varClientesDia,datasets:[{label: 'Presenciales',data: me.varTotalVentasDia,backgroundColor:["#0074D9", "#FF4136", "#2ECC40", "#FF851B", "#7FDBFF", "#B10DC9", "#FFDC00", "#001f3f", "#39CCCC", "#01FF70", "#85144b", "#F012BE", "#3D9970", "#111111", "#AAAAAA"]
                    ,borderColor:'#fff',borderWidth: 1},]},
                options:{scales:{yAxes:[{ticks:{beginAtZero:true,callback:(value,index,values)=>{return 'Bs. ' + value;}}}]}}
            })
        },
        virtualesPresenciales(){
            let me = this;
             me.arrayVirtuales.map((x)=>{
                me.varDias.push(x.dia);
                me.varTotalVentasVirtuales.push(x.total);
            });
             me.arrayPresenciales.map((x)=>{
                me.varTotalVentasPresenciales.push(x.total);
            });
            me.varVirtuales=document.getElementById('ventas').getContext('2d');
            me.charVirtuales = new Chart(me.varVirtuales,{
                type: 'line',
                data:{labels: me.varDias,datasets:[{label: 'Presenciales',data: me.varTotalVentasPresenciales,backgroundColor:'rgba(0,174,249,0.5)',borderColor:'rgba(0,174,249,0.5)',borderWidth: 1},
                                                          {label: 'Virtuales',data: me.varTotalVentasVirtuales,backgroundColor:'rgba(254,156,40,0.5)',borderColor:'rgba(254,156,40,0.5)',borderWidth: 1},]},
                options:{scales:{yAxes:[{ticks:{beginAtZero:true,callback:(value,index,values)=>{return 'Bs. ' + value;}}}]}}
            })
        },
        onChange(event) {
            this.charVirtuales.destroy();
            this.charVentasDia.destroy();

            this.varVentasDia='';
            this.charVentasDia='';
            this.varClientesDia=[];
            this.varTotalVentasDia=[];

            this.varVirtuales='';
            this.charVirtuales='';
            this.varTotalVentasVirtuales=[];
            this.varTotalVentasPresenciales=[];
            this.varDias=[];
            this.ventas(event.target.value);
        }
    },
    mounted(){
        let fecha = new Date();
        this.ventas("0"+fecha.getMonth());
    }
}
</script>