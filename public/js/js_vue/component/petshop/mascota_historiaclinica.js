

Vue.component('editar-mascota-historia', {
  props: ['value'],
  data() {
        return {
            historia: {
              id: '',
              id_mascota: '',
              id_sucursal: '',
              fecha: '',
              observaciones: ''
            },
            sucursales: [],
            existe_sucursal: false,
            mostrar_sucursal: false
        }
    },
  template: `
        <div>
          
          <div class="modal fade " id="myModalEstado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document" >
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Registro de Historia Clínica</h4>
                </div>
                <div class="modal-body">
                  
                    <div class="col-md-12">

                      <div class="col-md-6" style="padding-bottom: 10px;" v-if="mostrar_sucursal == true">
                        <label>Sucursal</label>
                        <select class="form-control" v-model="historia.id_sucursal" >
                          <option 
                            v-for="item in sucursales" 
                            v-bind:value="item.id_sucursal"
                            :selected="item.id_sucursal == historia.id_sucursal"
                          >{{item.nom_sucursal}}</option>
                        </select>
                      </div>


                      <div class="col-md-4" style="padding-bottom: 10px;">
                          <label>Fecha</label>

                          <div class="input-group ">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="date" class="form-control pull-right"  
                              v-model="historia.fecha"
                              :disabled="historia.id != 0"
                            >
                          </div>                             

                      </div>    


                      <div class="col-md-12"  style="padding-bottom: 10px;">
                        <label>Observaciones</label>

                        <textarea class="form-control" rows="3" placeholder="Ingrese las observaciones ..."
                          v-model="historia.observaciones">
                        </textarea>

                      </div>

                    </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
                  <button type="button" class="btn btn-primary" data-dismiss="modal" 
                    v-on:click="guardar_historia()" 
                    v-if="habilita_guardado()"
                  >Guardar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
  `,
  watch: {
      // whenever question changes, this function will run
      value: function () {
        this.historia = this.value;
        if ((this.historia.id_sucursal == '') && (this.existe_sucursal == true)){
          var objsuc = this.sucursales[0]
          this.historia.id_sucursal = objsuc.id_sucursal
        }
      }  
  },
  methods: {
    cargar_sucursales: function() {
      this.existe_sucursal = false
      this.mostrar_sucursal = false
      axios.get( 'get_sucursales_usuario' 
          ).then(response => {
            //console.log(response);
            if (response.data != null){
              this.sucursales = response.data
              this.existe_sucursal = this.sucursales.length > 0
              this.mostrar_sucursal = this.sucursales.length > 1
            }
            //console.log(this.series);
          })
      .catch(function(error){
        console.log(error);
         swal("No se pudo cargar las sucursales..!!", 'Contacte con Soporte Técnico', "error");
      });
    },
    guardar_historia: function() {    
      this.$emit('modificar_historia', this.historia);
    },
    habilita_guardado: function(){     
      return ((this.historia.observaciones != '') && 
              (this.historia.fecha != '') && 
              (this.existe_sucursal == true));
    }

  },  
  created: function(){
    this.cargar_sucursales()
  }
});

var mascotahistoriamodel = {
      mascota_seleccionada: '',
      mascota: {
        id_mascota: '',
        nombre: '',
        codigo: '',
        raza: '',
        sexo: '',
        fec_nac: '',
        nom_cliente: '',
        ident_cliente: ''
      },
      historia_seleccionada: '',
      historias: [],
      fecha_actual: '',
      mostrar_historia: false
    },
controller_mascota_historia
 = new Vue({
   el: '#app_mascota_historia',
   data: mascotahistoriamodel,
   computed: {
      sexo: function () {
        if (this.mascota.sexo == 'M')
          return 'Macho'
        else
          return 'Hembra'
      }
   },
   watch: {
        // whenever question changes, this function will run
        mascota_seleccionada: function () {
          //alert("mascota_seleccionada " + mascota_seleccionada)
          this.cargar_mascota()
          this.cargar_historia()
        }  
   },
   methods: {
      cargar_mascota: function() {
        if (this.mascota_seleccionada){
          axios.get( 'pet_cargar_mascota/' + this.mascota_seleccionada 
              ).then(response => {
                //console.log(response);
                this.mascota = response.data
                //console.log(this.series);
              })
          .catch(function(error){
            console.log(error);
             swal("No se pudo cargar la mascota..!!", 'Contacte con Soporte Técnico', "error");
          });
        }
      },
      cargar_historia: function() {
        if (this.mascota_seleccionada){
          axios.get( 'pet_cargar_historia/' + this.mascota_seleccionada 
              ).then(response => {
                //console.log(response);
                this.historias = response.data
                this.mostrar_historia = true

                //console.log(this.series);
              })
          .catch(function(error){
            console.log(error);
             swal("No se pudo cargar las historias..!!", 'Contacte con Soporte Técnico', "error");
          });
        }
      },
      adicionar_historia: function(){
        var historia = {
              id: 0,
              id_mascota: this.mascota_seleccionada,
              id_sucursal: '',
              fecha: this.fecha_actual,
              observaciones: ''
        }

        this.historia_seleccionada = historia;
        $("#myModalEstado").modal("show");
        
      },
      get_historia_id: function(data){
        return data.id
      },
      modificar_historia: function(detalle){       
        //this.showModalMascota = true;
        this.historia_seleccionada = detalle;
        $("#myModalEstado").modal("show");
      },
      actualiza_historia: function(historia){
        var esnuevo = ((historia.id == '') || (historia.id == 0));
        datohistoria = {
          historia : historia
        }
        axios.post( 'pet_guardar_mascota_historiaclinica', datohistoria
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                if (esnuevo == false){
                  this.historia_seleccionada.observaciones = historia.observaciones;
                }
                else{
                  this.cargar_historia();
                }
              }
              else{
              }
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo actualizar la historia..!!", 'Contacte con Soporte Técnico', "error");
        });

      },
      eliminar_historia: function(historia){
        if (confirm("Desea eliminar el registro seleccionado de historia clínica?")){
          datohistoria = {
            id : historia.id
          }
          axios.post( 'pet_eliminar_mascota_historiaclinica', datohistoria
              ).then(response => {
                //console.log(response);
                if (response.data != null){
                  var index = this.historias.findIndex(item => item.id == historia.id);
                  this.historias.splice(index, 1);
                }
                else{
                }
              })
          .catch(function(error){
            console.log(error);
            swal("No se pudo eliminar la historia..!!", 'Contacte con Soporte Técnico', "error");
          });
        }    
      }

  },  

  created: function(){
    date = new Date().toISOString().substring(0, 10);
    this.fecha_actual = date;
     //this.cargar_clientes();
     //this.fecha_actual();
  }

});