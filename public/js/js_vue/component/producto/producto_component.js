var controller_producto_index
 = new Vue({
   el: '#app_producto',
   data: {

    saludo: 'Hola como estas',
    carga_masiva: false,
    carga_index: true,
    cargando: false,
    file: '',
    almacen: '',
    acciones: {
      '0' : 'Actualizar',
      '1' : 'Reemplazar'
    },
    accion: '',
    ruta:'',
    errores: [],
    almacenes :[]
    

   },
   methods: {
         index_producto: function(){
           this.carga_masiva = false;
           this.carga_index = true;
           document.getElementById("select_almacen").disabled = true;
           document.getElementById("select_accion").disabled = true;
           document.getElementById("btn_guardar").disabled = true;

         },
         abrir_ventana: function() {
           this.carga_masiva = true;
           this.carga_index = false;
            this.cargando = false;

            axios.get( 'excel_producto_almacen'
            ).then(response => {
              this.almacenes = response.data;
            })
        .catch(function(error){
           swal("No se pude Culminar..!!", 'Comunicate con Soporte Técnico', "error");
        });
         },
      validar_datos: function() {
        if(this.almacen == ''){
          swal("no se Puede Enviar..!!", "Seleccione un Almacen para poder Continuar", "info");
          return false;
        }
        //if(this.accion == ''){
          //swal("no se Puede Enviar..!!", "Seleccione una Acción para poder Continuar", "info");
          //return false;
        //}
        return true;
      },
      guardar_datos: function() {

                if(this.validar_datos() == false){
                  return;
                }
                        this.cargando = true;
                        this.carga_masiva = false;
                 
                    datos_a_guardar = {
                      'almacen': this.almacen,
                      'ruta': this.ruta
                      //'accion': this.accion

                    };

            axios.post( 'excel_producto_guardar', datos_a_guardar
            ).then(response => {
              console.log(response.data);
          if (response.data == 'T') {
            swal("Buen Trabajo!", "Datos Cargado Correctamente!", "success");
              this.carga_masiva = false;
              this.carga_index = true;
              this.limpiarDatos();
              this.cargando = false;

          }
          if (response.data == 'X') {
            swal("No se Puede Culminar..!!", "Archivo sin Contenido, a Partir de la fila 2", "warning");
            this.carga_masiva = true;
          }
          if(response.data != 'F' && response.data != 'T'){
              swal("Alerta..!!", "No se ha Logrado subir!, revisa los Errores, Corrigelos e Intenta Nuevamente", "info");
              this.carga_masiva = true;
              this.cargando = false;
              this.errores = response.data;
            }
        })
        .catch(function(error){
          console.log(error);
           swal("No se Puede Culminar..!!", 'Comunicate con Soporte Técnico', "error");
        });

         
      },      

      cargado_todo: function(){
        console.log(this.ruta);
           //document.getElementById("select_accion").disabled = false;
           document.getElementById("btn_guardar").disabled = false;
      },
      submitFile: function(){
            let formData = new FormData();
            formData.append('file', this.file);
            axios.post( 'excel_producto',
               formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }
            ).then(response => {
             // console.log(response.data);
            if (response.data.si == 'T') {
            this.ruta = response.data.ruta;
            document.getElementById("select_almacen").disabled = false;
            swal("Buen Trabajo!", "Archivo Cargado Correctamente!", "success");
            //console.log(this.ruta);
            return;
            }
            if (response.data == 'F') {
              swal("No se puede Culminar..!!", "Por Favor Intenta Nuevamente * Ingresa un Archivo Tipo xls/csv", "error");
            }

          })
          .catch(function(error){
            console.log(error);
             swal("No se puede Culminar..!!", 'Comunicate con Soporte Técnico', "error");
          });
      },
      handleFileUpload: function(){
        this.limpiarDatos();//para limpiar datos
        this.file = this.$refs.file.files[0];
      },
      limpiarDatos: function(){
        this.file = null;
        this.ruta = '';
        this.almacen = '';
        this.accion = '';
        //this.$refs.file.files[0] = null;
        this.errores = [];

      }

        


      },
      created: function(){
         this.index_producto();
      }
   });