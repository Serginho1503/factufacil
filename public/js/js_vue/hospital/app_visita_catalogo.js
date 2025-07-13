var controlador_catalogo
 = new Vue({
   el: '#controlador_catalogo',
   data: {
      
      new_catalogo: {
         descripcion: '',
         cod_padre:'',
         id:''
      },
      new_catalogo_dos: {
         descripcion: '',
         cod_padre:'',
         id:''
      },
      catalogo_all: [],
      catalogo_all_dos: [],
     
   },
   methods: {
         show_catalogo: function(variable){
            this.new_catalogo.cod_padre = variable;         
            this.$http.post('catalogo_show',this.new_catalogo).then(function(respuesta){
              if(variable == 1){
                this.catalogo_all = respuesta.body;
              }
              if(variable == 2){
                this.catalogo_all_dos = respuesta.body;
              }
            }, function(){
               swal("No Logre Recuperar las Plantillas..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
            }); 
         },
         buscar_catalogo: function(variable){
              if(variable == 1){
                var catalogo = this.new_catalogo;
              }
              if(variable == 2){
                var catalogo = this.new_catalogo_dos;
              }
            this.$http.post('catalogo_select', catalogo).then(function(respuesta){
               var catalogo_all = respuesta.body;
               this.new_catalogo.descripcion = catalogo_all[0].descripcion;
              if(variable == 1){
                CKEDITOR.instances.evolucion.setData(this.new_catalogo.descripcion);
              }
              if(variable == 2){
                CKEDITOR.instances.prescripcion.setData(this.new_catalogo.descripcion);
              }
            }, function(){
                swal("No Logre Buscar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
            }); 
         },
         limpiarDatos: function(){
            this.new_catalogo.id = '';
            this.new_catalogo.nombre = '';
            this.new_catalogo.descripcion = '';
            this.new_catalogo.estado = '';
            this.new_catalogo.cod_padre = '';
            this.new_catalogo.campo_buscar = '';
            this.new_catalogo.created_at = '';
            this.new_catalogo.updated_at = '';
         }
      },
      created: function(){
         this.show_catalogo(1);
         this.show_catalogo(2);
      }
   });


