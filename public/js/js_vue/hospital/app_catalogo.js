var controlador_catalogo
 = new Vue({
   el: '#controlador_catalogo',
   data: {
      
      cargando_catalagos: true,
      new_catalogo: {

         nombre: 'Ingreso Nuevo',
         estado: 1,
         descripcion: '',
         cod_padre:'',
         campo_buscar: '',
         id:'',
         created_at:'',
         updated_at:''
      },
      catalago_all: [],
      alert: ''
     
   },
   methods: {
         show_catalogo: function(){
            this.cargando_catalagos = true;
            this.$http.get('catalogo_show').then(function(respuesta){
               this.catalago_all = respuesta.body;
               this.cargando_catalagos = false;
               
            }, function(){
               alert('No se han podido recuperar las Especialidades.');
               this.cargando_catalagos = false;
            }); 
         },
         create_catalogo: function(){
               if(this.validarCampos() == false){
                return;
               }
               
               this.$http.post('catalogo_create', this.new_catalogo).then(function(){
               swal("Buen Trabajo!", "Catalogo Creado Correctamente!", "success");
               $('#myModal').modal('hide');
               console.log(this.new_catalogo);
               this.limpiarDatos();
               this.show_catalogo();

            }, function(){
               swal("No se pude Culminar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
            });
         },
         update_catalago: function(data){
              this.new_catalogo = data;
              if(this.validarCampos() == false){
                return;
               }
             this.$http.post('catalogo_update', this.new_catalogo).then(function(){
              swal("Buen Trabajo..!", "Catalogo Actualizado Correctamente!", "success");
              $('#myModal').modal('hide');
              this.show_especialidad();
            }, function(){
               swal("No se pude Culminar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
            });
            
         },
         delete_catalogo: function(dele_cata){
            swal({
                 title: "Estas Seguro de Eliminar este Catalogo?",
                 text: "Al Momento de Eliminar, Algunos Formulario no podrán funcionar Correctamente!",
                 icon: "warning",
                 buttons: true,
                 dangerMode: true,
               })
               .then((willDelete) => {
                 if (willDelete) {
                        this.$http.post('catalogo_delete', dele_cata).then(function(){
                           this.show_catalogo();
                        }, function(){
                            swal("No se pude Culminar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
                        });
                   swal("Eliminado Correctamente..!", {
                     icon: "success",
                   });
                 } else {
                   swal("Eliminación Cancelada..!!");
                 }
               });
      
         },
         buscar_catalogo: function(){
            this.cargando_catalagos = true;
            this.$http.post('catalogo_search', this.new_catalogo).then(function(respuesta){
               this.catalago_all = respuesta.body;
               this.cargando_catalagos = false;
            }, function(){
                swal("No Logre Buscar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
               this.cargando_catalagos = false;
            }); 
         },
         cargarDatosUpdate: function(data) {
            this.new_catalogo = data;
            CKEDITOR.instances.descripcion.setData(this.new_catalogo.descripcion);
            $('#myModal').modal('show');
         }
         ,
         cargarDatosCreate: function() {
            this.limpiarDatos();
            this.new_catalogo.id = 0;
            $('#myModal').modal('show');
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
         },
        validarCampos: function(){
          if(this.new_catalogo.nombre == ''){
             swal("Campo Nombre Incompleto", "Por Favor Ingrese un Nombre..", "info");
             return false;
          }
          if(this.new_catalogo.cod_padre == ''){
             swal("Campo Código Padre Incompleto", "Por Favor Ingrese un Código Padre..", "info");
             return false;
          }
          if(this.new_catalogo.estado == ''){
             swal("Campo Estado Incompleto", "Por Favor Ingrese un Estado..", "info");
             return false;
          }
          this.new_catalogo.descripcion = CKEDITOR.instances.descripcion.getData();
          if(this.new_catalogo.descripcion == ''){
             swal("Campo Descripción Incompleto", "Por Favor Ingrese un Descripción..", "info");
             return false;
          }
        }
        
      },
      created: function(){
         this.show_catalogo();
      }
   });