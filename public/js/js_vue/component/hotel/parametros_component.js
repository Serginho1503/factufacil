var controller_hotel_index
 = new Vue({
   el: '#app',
   data: {

      new_parametro: {
        id_parametro: 0,
        cod_padre: '',
        cod_hijo: '',
        nombre: '',
        descripcion: '',
        valor:'',
        estado:'',
        tipo: ''
      },
      parametros: [],
      cargando: true,

      alerts: {
        uno: '',
        dos: '',
        tres: '',
        cuatro: '',
        cinco: '',
        seis: '',
        siete: ''
      },
      cod_padres: []

   },
   methods: {
         show_parametros: function(){
            this.cargando = true;
            this.$http.get('hot_conf_show').then(function(respuesta){
               this.parametros = respuesta.body;
               this.cargando = false;
               
            }, function(){
               swal("No se pudo recuperar los Parametros", "Por Favor Intenta Nuevamente o Comunicate con EQsoft...!", "error");
               this.cargando = false;
            }); 
         },
         create_parametro: function(){
               if(this.validarCampos() == false){
                return;
               }
               
               this.$http.post('hot_parametro_create', this.new_parametro).then(function(respuesta){
                console.log(respuesta);
               swal("Buen Trabajo!", "parametro Creado Correctamente!", "success");
               $('#myModal').modal('hide');
               console.log(this.new_parametro);
               this.limpiarDatos();
               this.show_parametros();

            }, function(){
               swal("No se pude Culminar..!!", "Por Favor Intenta Nuevamente o Comunicate con EQsoft...!", "error");
            });
         },
         update_parametro: function(){
              if(this.validarCampos() == false){
                return;
               }
             this.$http.post('hot_parametro_update', this.new_parametro).then(function(){
              swal("Buen Trabajo..!", "parametro Actualizado Correctamente!", "success");
              $('#myModal').modal('hide');
              this.show_especialidad();
            }, function(){
               swal("No se pude Culminar..!!", "Por Favor Intenta Nuevamente o Comunicate con EQsoft...!", "error");
            });
            
         },
         delete_parametro: function(dele_cata){
            swal({
                 title: "Estas Seguro de Eliminar este parametro?",
                 text: "Al Momento de Eliminar, Algunos Formulario no podrán funcionar Correctamente!",
                 icon: "warning",
                 buttons: true,
                 dangerMode: true,
               })
               .then((willDelete) => {
                 if (willDelete) {
                        this.$http.post('parametro_delete', dele_cata).then(function(){
                           this.show_parametros();
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
         buscar_parametro: function(){
            this.cargando_parametros = true;
            this.$http.post('parametro_search', this.new_parametro).then(function(respuesta){
               this.parametro_all = respuesta.body;
               this.cargando_parametros = false;
            }, function(){
                swal("No Logre Buscar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
               this.cargando_parametros = false;
            }); 
         },
         modal_update: function(data) {
            this.new_parametro = data;
            $('#myModal').modal('show');
         }
         ,
         modal_ingreso: function() {
            this.limpiarDatos();
            this.new_parametro.id_parametro = 0;
            $('#myModal').modal('show');
         },
         limpiarDatos: function(){
            this.new_parametro.id_parametro = '';
            this.new_parametro.nombre = '';
            this.new_parametro.descripcion = '';
            this.new_parametro.estado = '';
            this.new_parametro.cod_padre = '';
            this.new_parametro.campo_buscar = '';
            this.new_parametro.created_at = '';
            this.new_parametro.updated_at = '';
         },
        validarCampos: function(){
          if(this.new_parametro.cod_padre == ''){
             this.alerts.uno="Campo Código Padre Incompleto,Por Favor Ingrese un Código Padre..";
              $('#uno').focus();
             return false;
          }
          if(this.new_parametro.nombre == ''){
             this.alerts.tres="Campo Nombre Incompleto, Por Favor Ingrese un Nombre..";
            $('#tres').focus();
             return false;
          }
          if(this.new_parametro.descripcion == ''){
             this.alerts.cuatro="Campo Descripción Incompleto, Por Favor Ingrese un Descripción..";
            $('#cuatro').focus();
             return false;
          }
          if(this.new_parametro.estado == ''){
            this.alerts.siete="Campo Estado Incompleto,Por Favor Ingrese un Estado..";
             $('#siete').focus();
             return false;
          }
        },
        limpiar_notificacion: function(variable) {
            switch (variable) {

                case 1:
                    this.alerts.uno = null;
                    break;
                case 2:
                    this.alerts.dos = null;
                    break;
                case 3:
                    this.alerts.tres = null;
                    break;
                case 4:
                    this.alerts.cuatro = null;
                    break;
                case 5:
                    this.alerts.cinco = null;
                    break;
                case 6:
                    this.alerts.seis = null;
                    break;
                case 7:
                    this.alerts.seis = null;
            }
        },
        opcion_form: function (argument) {
          if (argument == 0) {
            this.create_parametro();
          }
          if (argument != 0) {
            this.update_parametro();
          }
        },
        show_cod_padres: function () {
          
            this.cargando = true;
            this.$http.get('hot_parametro_cod_padre').then(function(respuesta){
               this.cod_padres = respuesta.body;
               this.cargando = false;
               
            }, function(){
               swal("No se pudo recuperar los Código Padres", "Por Favor Intenta Nuevamente o Comunicate con EQsoft...!", "error");
               this.cargando = false;
            }); 
        },
        cargar_cod_hijos: function () {//para cargar los hijos de este padre
            this.cargando = true;
            this.$http.post('hot_parametro_search_cod_padre', this.new_parametro).then(function(respuesta){
               this.parametros = respuesta.body;
               this.cargando = false;
            }, function(){
                swal("No Logre Buscar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
               this.cargando_parametros = false;
            }); 
        }
        


      },
      created: function(){
         this.show_parametros();
         this.show_cod_padres();
         console.log(this.new_parametro);
      }
   });