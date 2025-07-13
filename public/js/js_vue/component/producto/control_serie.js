

Vue.component('editar-estado-serie', {
  props: ['value'],
  data() {
        return {
            estado: '',
            estadoserie : [],
            nota: ''
        }
    },
  template: `
        <div>
          
          <div class="modal fade " id="myModalEstado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document" >
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Estado del Producto</h4>
                </div>
                <div class="modal-body">
                  
                    <div class="col-md-12">

                      <div> <label class="col-md-3" style="padding-left: 0px;">Producto: </label> <label class="col-md-9"> {{ value.pro_nombre }} </label> </div>
                      <div> <label class="col-md-3" style="padding-left: 0px;">#Serie: </label> <label class="col-md-9"> {{ value.numeroserie }} </label></div>
                      <div> <label class="col-md-3" style="padding-left: 0px;">Estado Actual: </label> <label class="col-md-9">{{ value.estado }} </label></div>

                      <div>
                        <div class="col-md-3" style="padding-left: 0px;"> 
                          <label>Nuevo Estado</label> 
                        </div>
                        <div class="col-md-4">
                          <select class="form-control" v-model="estado" >
                            <option 
                              v-for="item in estadoserie" 
                              v-bind:value="item.id"
                              :selected="item.id == estado"
                              v-if="estado_disponible(item)"
                            >{{item.estado}}</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-12">

                        <textarea class="form-control" rows="3" placeholder="Ingrese las observaciones ..."
                          v-model="nota">
                        </textarea>

                      </div>

                    </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                  <button type="button" class="btn btn-primary" data-dismiss="modal" 
                    v-on:click="guardar_estado()" 
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
        this.estado = this.value.id_estado;
        this.cargar_estados();
      }  
  },
  methods: {
    guardar_estado: function() {    
      nuevoestadoserie = {
        'id_serie' : this.value.id_serie,
        'id_almacen' : this.value.id_almacen,
        'id_estado' : this.estado,
        'observaciones' : this.nota
      }
      this.$emit('modificar_estado', nuevoestadoserie);
    },
    cargar_estados: function() {
      axios.get( 'lst_estadoserie'
          ).then(response => {
            console.log(response);
            this.estadoserie = response.data;
          })
      .catch(function(error){
        console.log(error);
         swal("No se pudo cargar los estados de serie..!!", 'Contacte con Soporte Técnico', "error");
      });
    },
    estado_disponible: function(estado){     
      var arreglo = ['5','6','7'];
      var pos = arreglo.indexOf(estado.id);
      return ((pos >= 0) || (this.value.id_estado == estado.id));
    },
    habilita_guardado: function(){     
      return (this.value.id_estado != this.estado);
    }

  },  
  created: function(){
  }
});

var controlseriemodel = {
      producto_nombre: '',
      producto_seleccionado: '',
      serie_nombre: '',
      series: [],
      detalle_seleccionado: {},
      fecha_actual: ''
    },
controller_control_serie
 = new Vue({
   el: '#app_control_serie',
   data: controlseriemodel,
   watch: {
      // whenever question changes, this function will run
      producto_nombre: function () {
        tmpnom = this.producto_nombre;
        pos = tmpnom.search(' - ');
        tmpnom = tmpnom.substring(pos+3);
        axios.get( 'get_producto_nombre/' + tmpnom
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                //console.log(response.data);
                this.producto_seleccionado = response.data.pro_id;
                this.cargar_series();
              }
              else{
                this.producto_seleccionado = '';
                this.series = [];

                //swal("No existe cliente registrado con el número de identidad: " + this.cliente_identificacion, 'Ingrese identificación nuevamente.', "warning");
              }
              //console.log(numerodevolucion);
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo obtener el producto..!!", 'Contacte con Soporte Técnico', "error");
        });
      }

    },   
   methods: {
      cargar_series: function() {
        if (this.producto_seleccionado){
          tmpserie = '';
          //console.log(this.serie_nombre);
          if (typeof this.serie_nombre !== 'undefined') { tmpserie = this.serie_nombre;}
          axios.get( 'get_producto_series/' + this.producto_seleccionado + '/' + tmpserie
              ).then(response => {
                //console.log(response);
                this.series = response.data;
                //console.log(this.series);
              })
          .catch(function(error){
            console.log(error);
             swal("No se pudo cargar las series..!!", 'Contacte con Soporte Técnico', "error");
          });
        }
      },
      cargar_series_pornombre: function(){
        this.cargar_series();
      },
      modificar_estado: function(detalle){       
        //this.showModalMascota = true;
        this.detalle_seleccionado = detalle;
        $("#myModalEstado").modal("show");
      },
      actualiza_serie: function(estado){
        axios.post( 'producto_serie_actualizarestado', estado
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                this.detalle_seleccionado.id_estado = estado.id_estado;
                this.detalle_seleccionado.estado = response.data;
              }
              else{
              }
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo actualizar estado..!!", 'Contacte con Soporte Técnico', "error");
        });

      },
      habilitar: function(estado){
        var arreglo = ['2', '4'];
        var pos = arreglo.indexOf(estado.id_estado);
        return (pos == -1);
      }

  },  

  created: function(){
    date = new Date().toISOString().substring(0, 10);
    this.fecha_actual = date;
     //this.cargar_clientes();
     //this.fecha_actual();
  }

});