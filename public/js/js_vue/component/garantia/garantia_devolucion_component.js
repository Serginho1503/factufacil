Vue.component('select-serie', {
  props: ['value'],
  data() {
        return {
            series: [],
            productos: [],
            producto_seleccionado: ''
        }
    },
  template: `
        <div>        
          <div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document" >
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Seleccionar Serie</h4>
                </div>
                <div class="modal-body">
                  <div style="padding: 20px 20px;">
                    <label>Producto</label>
                    <select class="form-control" v-model="producto_seleccionado" v-on:change="cargar_serie_producto">
                      <option 
                        v-for="item in productos" 
                        v-bind:value="item.pro_id"
                        :selected="item.pro_id == producto_seleccionado"
                      >{{item.pro_nombre}}</option>
                    </select>
                  </div>
                  <div class="container box-body table-responsive" style="height: 200px;">
                    <table class="table table-bordered table-hover">
                      <thead >
                        <tr >
                          <th>Número de Serie</th>
                          <th>Almacen</th>
                          <th>Descripción</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="serie in series">
                          <td>
                          <a style="color: #094074;" href="#" title="Seleccionar" data-dismiss="modal"
                            v-on:click="seleccionar_serie(serie.id_serie)" 
                          ><i class="fa fa-plus-circle" ></i></a> 
                            {{ serie.numeroserie }} 
                          </td>
                          <td>
                            {{ serie.almacen_nombre }} 
                          </td>
                          <td>
                            {{ serie.descripcion }} 
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div> 
                </div>
                <div class="modal-footer">
                </div>
              </div>
            </div>
          </div>
        </div>
  `,
  methods: {
    cargar_serie_producto: function() {
      axios.get('garantia_devolucion_seriesdisponibles/' + this.producto_seleccionado
          ).then(response => {
            //console.log(response);
            this.series = response.data;
            //console.log(this.cliente_select);
          })
      .catch(function(error){
         this.series = [];
         console.log(error);
         swal("No se pudo cargar las series..!!", 'Contacte con Soporte Técnico', "error");
      });
    },
    cargar_productos: function() {
      axios.get( 'garantia_devolucion_productos'
          ).then(response => {
            console.log(response);
            this.productos = response.data;
          })
      .catch(function(error){
        console.log(error);
         swal("No se pudo cargar las series..!!", 'Contacte con Soporte Técnico', "error");
      });
    },
    seleccionar_serie: function(item) {
      this.$emit('seleccion-serie', item);
    }
  },  
  created: function(){
     this.producto_seleccionado = this.value
     this.cargar_serie_producto();
     this.cargar_productos();
  }
});


Vue.component('custom-input', {
  props: ['value'],
  template: `
    <input
      v-bind:value="value"
      v-on:input="$emit('input', $event.target.value)"
    >
  `
});

Vue.component('editar-nota', {
  props: ['value'],
  data() {
        return {
            'nota': '' 
        }
    },
  template: `
        <div>
          
          <div class="modal fade " id="myModalNota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document" >
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Observaciones</h4>
                </div>
                <div class="modal-body">
                  
                    <div class="col-md-12">

                      <textarea class="form-control" rows="3" placeholder="Ingrese los Detalles ..."
                        v-model="nota">
                      </textarea>

                    </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                  <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="guardar_nota()" >Guardar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
  `,
  watch: {
      // whenever question changes, this function will run
      value: function () {
        this.nota = this.value.observaciones;
      }  
  },
  methods: {
    guardar_nota: function() {    
      this.$emit('modificar_nota', this.nota);
    }  
  },  
  created: function(){
  }
});

var garantiamodel = {
      saludo: 'Hola como estas',
      mostrar_lista : true,
      mostrar_edicion : false,
      errores: [],
      clientes :[],
      cliente_select: 0,
      sucursales :[],
      sucursal_select: '',
      numerodevolucion: '',
      fechadevolucion: '',
      cliente_identificacion: '',
      cliente_nombre: '',
      cliente_telefono: '',
      cliente_direccion: '',
      cliente_correo: '',
      cliente_ciudad: '',
      productosgarantia: [],
      productosdevolucion: [],
      cliente_id: '',
      almacenes: [],
      showModal: false,
      serie_select: '',
      devolucion_id: '',
      desde: '',
      hasta: '',
      clienteselect_combo: '',
      clientes_combo :[],
      devoluciones: [],
      detalle_seleccionado: {},
      searchText:''
    },
controller_garantia_devolucion
 = new Vue({
   el: '#app_garantia_devolucion',
   data: garantiamodel,
   watch: {
      // whenever question changes, this function will run
      cliente_nombre: function () {
        axios.get( 'garantia_devolucion_cliente_nombre/' + this.cliente_nombre
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                //console.log(response.data);
                document.getElementById("txt_clid").value = response.data.id_cliente;
                this.cliente_identificacion = response.data.ident_cliente;
                this.cliente_telefono = response.data.telefonos_cliente;
                this.cliente_correo = response.data.correo_cliente;
                this.cliente_direccion = response.data.direccion_cliente;
                this.cliente_ciudad = response.data.ciudad_cliente;
                this.cliente_id = response.data.id_cliente;
                this.cargarProducto();
              }
              else{
                document.getElementById("txt_clid").value = 0;
                this.cliente_identificacion = '';
                //this.cliente_nombre = '';               
                this.cliente_telefono = '';
                this.cliente_correo = '';
                this.cliente_direccion = '';
                this.cliente_ciudad = '';

                //swal("No existe cliente registrado con el número de identidad: " + this.cliente_identificacion, 'Ingrese identificación nuevamente.', "warning");
              }
              //console.log(numerodevolucion);
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo obtener el numero de devolucion..!!", 'Contacte con Soporte Técnico', "error");
        });
      }
    },   
   methods: {
      cargar_documentos: function() {

        axios.get( 'garantia_devolucion_documentos/' + this.desde + '/' + this.hasta + '/' + this.cliente_combo
            ).then(response => {
              console.log(response);
              this.devoluciones = response.data;
              //console.log(this.cliente_select);
            })
        .catch(function(error){
          console.log(error);
           swal("No se pudo cargar los documentos..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      cargar_clientes: function() {
        axios.get( 'garantia_devolucion_clientes'
            ).then(response => {
              console.log(response);
              this.clientes = response.data;
              this.clientes_combo = response.data;
              //console.log(this.cliente_select);
            })
        .catch(function(error){
          console.log(error);
           swal("No se pudo cargar los clientes..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      editar_devolucion: function(){
        this.mostrar_lista = false;
        this.cargar_sucursales();
        this.fecha_actual();
        this.mostrar_edicion = true;
      },
      cargar_sucursales: function() {
        axios.get( 'garantia_devolucion_sucursales'
            ).then(response => {
              console.log(response);
              this.sucursales = response.data;
              if (this.sucursales.length > 0){
                this.sucursal_select = this.sucursales[0].id_sucursal;               
                this.actualiza_datosucursal();
              }
              //console.log(this.sucursal_select);
            })
        .catch(function(error){
          console.log(error);
           swal("No se pudo cargar los clientes..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      actualiza_datosucursal: function(){
        this.actualiza_numerodevolucion();
        this.cargar_almacenes();
        console.log(this.cliente_nombre);
      },
      actualiza_numerodevolucion: function(){
        
        axios.get( 'garantia_devolucion_numero/' + this.sucursal_select
            ).then(response => {
              //console.log(response);
              this.numerodevolucion = response.data.padStart(9, '0');
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo obtener el numero de devolucion..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      cargar_almacenes: function(){
        
        axios.get( 'garantia_devolucion_almacenes/' + this.sucursal_select
            ).then(response => {
              this.almacenes = response.data;
              //console.log(this.almacenes);
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo obtener los almacenes..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      carga_cliente_identificacion: function(){
        
        axios.get( 'garantia_devolucion_cliente_identificacion/' + this.cliente_identificacion
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                //console.log(response.data);
                document.getElementById("txt_clid").value = response.data.id_cliente;
                this.cliente_nombre = response.data.nom_cliente;
                this.cliente_telefono = response.data.telefonos_cliente;
                this.cliente_correo = response.data.correo_cliente;
                this.cliente_direccion = response.data.direccion_cliente;
                this.cliente_ciudad = response.data.ciudad_cliente;
                this.cliente_id = response.data.id_cliente;
                this.cargarProducto();
              }
              else{
                document.getElementById("txt_clid").value = 0;
                this.cliente_nombre = '';               
                this.cliente_telefono = '';
                this.cliente_correo = '';
                this.cliente_direccion = '';
                this.cliente_ciudad = '';

                swal("No existe cliente registrado con el número de identidad: " + this.cliente_identificacion, 'Ingrese identificación nuevamente.', "warning");
              }
              //console.log(numerodevolucion);
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo obtener el numero de devolucion..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      carga_cliente_nombre: function(){
        
        axios.get( 'garantia_devolucion_cliente_nombre/' + this.cliente_nombre
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                //console.log(response.data);
                document.getElementById("txt_clid").value = response.data.id_cliente;
                this.cliente_identificacion = response.data.ident_cliente;
                this.cliente_telefono = response.data.telefonos_cliente;
                this.cliente_correo = response.data.correo_cliente;
                this.cliente_direccion = response.data.direccion_cliente;
                this.cliente_ciudad = response.data.ciudad_cliente;
                this.cliente_id = response.data.id_cliente;
                this.cargarProducto();
              }
              else{
                document.getElementById("txt_clid").value = 0;
                this.cliente_identificacion = '';
                //this.cliente_nombre = '';               
                this.cliente_telefono = '';
                this.cliente_correo = '';
                this.cliente_direccion = '';
                this.cliente_ciudad = '';

                //swal("No existe cliente registrado con el número de identidad: " + this.cliente_identificacion, 'Ingrese identificación nuevamente.', "warning");
              }
              //console.log(numerodevolucion);
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo obtener el numero de devolucion..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      cargarProducto: function(){
        axios.get( 'garantia_devolucion_cliente_productosgarantia/' + this.cliente_id
                    ).then(response => {
                      console.log(response);
                      if (response.data != null){
                        this.productosgarantia = response.data;
                        this.productosdevolucion = [];
                      }
                      else{

                        swal("No existe cliente registrado con el número de identidad: " + this.cliente_identificacion, 'Ingrese identificación nuevamente.', "warning");
                      }
                    })
                .catch(function(error){
                  console.log(error);
                  swal("No se pudo obtener el numero de devolucion..!!", 'Contacte con Soporte Técnico', "error");
                });
                
      },
      fecha_actual: function(){
        date = new Date().toISOString().substring(0, 10);
        this.fechadevolucion = date;
        //this.fechadevolucion = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate();
      },
      devolver_serie: function(datos){
        //console.log(datos.id_producto);
        producto = {
          'id_producto': datos.id_producto,
          'id_detalle': datos.id_detalle,
          'id_detalleventa': datos.id_detalleventa,
          'descripcion': datos.descripcion,
          'id_seriedevuelta': datos.id_serie,
          'seriedevuelta': datos.numeroserie,
          'observaciones': '',
          'id_almacen': '',
          'almacenombre': '',
          'id_serieentregada': '',
          'serieentregada': '',
          'fechaentrega': '',
          'diasgarantia': 0
        };
        this.productosdevolucion.push(producto);
      },
      editar_nota: function(detalle){       
        //this.showModalMascota = true;
        this.detalle_seleccionado = detalle;
        $("#myModalNota").modal("show");
      },
      actualiza_detallenota(nota){
        this.detalle_seleccionado.observaciones = nota;
      },
      habilitar: function(producto){
        var index = this.productosdevolucion.findIndex(item => item.id_seriedevuelta == producto.id_serie);
        return (index == -1);
      },
      deshacer_devolucion: function(producto){
        var index = this.productosdevolucion.findIndex(item => item.id_seriedevuelta == producto.id_serie);
        this.productosdevolucion.splice(index, 1);
      },
      actualiza_serie: function(producto, idserienuevo) {
        producto.id_serieentregada = idserienuevo;
        axios.get( 'garantia_devolucion_sel_serie_id/' + idserienuevo
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                producto.serieentregada = response.data.numeroserie;
                producto.diasgarantia = response.data.pro_garantia;
                if (producto.diasgarantia == '') { producto.diasgarantia = 0; }
                producto.fechaentrega = this.fechadevolucion;
              }
              else{
                document.getElementById("txt_clid").value = 0;
                this.cliente_identificacion = '';
                //this.cliente_nombre = '';               
                this.cliente_telefono = '';
                this.cliente_correo = '';
                this.cliente_direccion = '';
                this.cliente_ciudad = '';

                //swal("No existe cliente registrado con el número de identidad: " + this.cliente_identificacion, 'Ingrese identificación nuevamente.', "warning");
              }
              //console.log(numerodevolucion);
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo obtener el numero de devolucion..!!", 'Contacte con Soporte Técnico', "error");
        });

      },
      validar_devolucion: function(){
        validaok = true;
        this.productosdevolucion.forEach(function(producto){
          if (producto.id_almacen == '' || producto.id_almacen == 0) { validaok = false; }
        });
        if (!validaok){
          swal("Verifique el almacen de devolución del producto..!!", '', "warning");
        }  
        return validaok;
      },
      cerrar_devolucion: function(){
        window.location.replace("garantia");
      },
      guardar_devolucion: function(){
        if (!this.validar_devolucion()){
          return false;
        }

        objdevolucion = {
          'id': this.devolucion_id,
          'fecha': this.fechadevolucion,
          'sucursal': this.sucursal_select,
          'idcliente': this.cliente_id,
          'listaserie': this.productosdevolucion
        };

        axios.post( 'garantia_devolucion_guardar', objdevolucion
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                this.mostrar_lista = true;
                this.mostrar_edicion = false;
                window.location.replace("garantia");
              }
              else{
              }
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo guardar la devolucion..!!", 'Contacte con Soporte Técnico', "error");
        });
      }

  },  

  created: function(){
     this.cargar_clientes();
     //this.fecha_actual();
  }

});