var controller_hotel_index
 = new Vue({
   el: '#app',
   data: {
      div_uno: true,
      div_dos: false,

      new_serie: {
        inicio: '',
        texto: '',
        cantidad: '',
        descripcion: '',
        txt_idcom:'',
        txt_iddet:'',
        txt_idprodet:'',
        imei:''

      }

   },
   methods: {
         actualizar_tabla: function(){
            this.$http.get('actualiza_imeiserie').then(function(respuesta){
              console.log(respuesta.body);
             $('.detserie').load(respuesta.body);
            }, function(){
               alert('No se han podido Actualizar las Tabla.');
            }); 

            this.cargar_primera_pantalla();
         },
         cargar_segunda_pantalla: function(){
            this.div_dos = true;
            this.div_uno = false;
         },
         cargar_primera_pantalla: function(){
            this.div_dos = false;
            this.div_uno = true;
         },
         guardar_masivos: function(){
          //variables quemadas que tengo que mandar
          this.new_serie.txt_idcom = $( "#txt_idcom" ).val();
          this.new_serie.txt_iddet = $( "#txt_iddet" ).val();
          this.new_serie.txt_idprodet = $( "#txt_idprodet" ).val();

               if(this.validarCampos() == false){
                return;
               }
               for (var i = this.new_serie.cantidad - 1; i >= 0; i--) {
                 this.new_serie.imei = this.new_serie.texto+this.new_serie.inicio;
                 var valor = 1;
                 this.new_serie.inicio = parseInt(this.new_serie.inicio) - (+parseInt(valor));
                     this.$http.post('guardar_imei', this.new_serie).then(function(respuesta){
                      if(respuesta.body == 1){
                     swal("No se Guardo!", "Ya existe un Regsitro igual Guardado Anteriormente!", "info");
                     return;
                      }
                     swal("Buen Trabajo!", "Regsitro Creado Correctamente!", "success");
                  }, function(){
                     swal("No se pude Culminar..!!", "Por Favor Intenta NUevamente o Comunicate con EQsoft...!", "error");
                  });
               }
               this.limpiarDatos();
               this.actualizar_tabla();

         },
          validarCampos: function(){
          if(this.new_serie.inicio == ''){
             swal("Campo Nombre Incompleto", "Por Favor Ingrese un Nombre..", "info");
             return false;
          }
          if(this.new_serie.cantidad == ''){
             swal("Campo Código Padre Incompleto", "Por Favor Ingrese un Código Padre..", "info");
             return false;
          }
          return true;
        },
         limpiarDatos: function(){
            this.new_serie.inicio = '';
            this.new_serie.cantidad = '';
            this.new_serie.descripcion = '';
            this.new_serie.texto = '';
            this.new_serie.imei = '';
            this.new_serie.txt_idcom = '';
            this.new_serie.txt_iddet = '';
            this.new_serie.txt_idprodet = '';
         }



        
      },
      created: function(){
         this.cargar_primera_pantalla();
      }
   });