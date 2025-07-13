
Vue.component('editar-porciento', {
  props: ['value'],
  data() {
        return {
            'precios' : []
        }
    },
  template: `
        <div>         
          <div class="modal fade " id="myModalPorciento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document" >
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Relación de Precio Compra-Venta</h4>
                </div>
                <div class="modal-body">
                  
                    <div class="col-md-12" style="padding-bottom: 10px;">

                      <div class="form-group col-md-12">
                        <div class="container box-body table-responsive" style="padding-bottom: 0px;">
                          <table class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr>
                                <th width="250px;">Tipo de Precio</th>
                                <th>Porciento</th>
                              </tr>
                            </thead>
                          </table>
                        </div>  
                        <div class="container box-body table-responsive" style="height: 200px; padding-top: 0px;">
                          <table class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr style="height: 1px;">
                                <th width="250px;"></th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="data in precios">
                                <td >
                                  {{data.desc_precios}}
                                </td>
                                <td >
                                  <input type="number" style="width: 70px;" v-model="data.porciento">
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>

                    </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                  <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="guardar_porciento()" >Guardar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
  `,
  watch: {
      // whenever question changes, this function will run
      value: function () {
        this.precios = this.value;
      }  
  },
  methods: {
    guardar_porciento: function() {    
        this.$emit('porciento_actualizado', this.precios);
    }
  },  
  created: function(){
  }
});

controller_precio_compraventa
 = new Vue({
   el: '#app_precio_compraventa',
   data: {
      precios: {}
   },
   methods: {
      cargar_precios: function() {
        axios.get( 'precio_compraventa_lst'
            ).then(response => {
              //console.log(response);
              this.precios = response.data;
              //console.log(this.cliente_select);
            })
        .catch(function(error){
          console.log(error);
           swal("No se pudo cargar los tipos de precios..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      editar_porciento: function(){       
        this.cargar_precios()
        $("#myModalPorciento").modal("show");
      },
      actualiza_porciento(precios){
        objcategoria = {
          'listaprecios': precios
        };

        axios.post( 'precio_compraventa_actualizar', objcategoria
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                this.$emit('categoria_actualizada', this.categoria);
              }
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo guardar los porcientos..!!", 'Contacte con Soporte Técnico', "error");
        });
      }
  },  

  created: function(){
  }

});