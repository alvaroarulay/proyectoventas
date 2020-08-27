<template>
     <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text-o"></i> Bienvenid@</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <section class="invoice">
              <div class="row mb-4">
                <div class="col-6">
                  <h2 class="page-header"><i class="fa fa-globe"></i> {{nom_empresa}}</h2>
                </div>
                <div class="col-6">
                  <h5 class="text-right">Fecha: {{new Date().toLocaleDateString()}}</h5>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-6">Empresa: 
                  <address><strong>{{nom_empresa}}</strong><br>{{direccion}}<br>Teléfono: {{telefono}}<br>Email: {{email}}</address>
                </div>
                <div class="col-6">Trabajador(a)
                  <address><strong>{{nombre}}</strong><br>{{direc_user}}<br>Teléfono: {{tel_user}}<br>Email: {{email_user}}</address>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </main>
</template>
<script>
export default {
    data(){
        return{
             nom_empresa:'',
             nit:'',
             representante:'',
             direccion:'',
             telefono:'',
             email:'',
             actividad:'',
             image:'',
             usuario: '',
             nombre:'',
             documento:'',
             num_doc:'',
             direc_user:'',
             tel_user:'',
             email_user:''
        }
    },
    methods:{
        listarEmpresa(){
            axios.get('/empresa/selectempresa').then((response)=>{
                let arrayEmpresa = response.data;
                this.nom_empresa = arrayEmpresa.nombre_empresa;
                this.nit = arrayEmpresa.nit;
                this.representante = arrayEmpresa.representante_legal;
                this.direccion = arrayEmpresa.direccion;
                this.telefono = arrayEmpresa.telefono;
                this.email = arrayEmpresa.email;
                this.actividad = arrayEmpresa.actividad;
                this.image = arrayEmpresa.image_name;
            });
            axios.get('/user/usuario').then((response)=>{
                let respuesta = response.data;
                this.usuario = respuesta.usuario;
                this.nombre = respuesta.nombre;
                this.documento = respuesta.documento;
                this.num_doc = respuesta.num_doc;
                this.direc_user = respuesta.direccion;
                this.tel_user = respuesta.telefono;
                this.email_user = respuesta.email;
                
            });
        }
    },
    mounted(){
        this.listarEmpresa();
    }
}
</script>