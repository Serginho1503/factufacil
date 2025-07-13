
Vue.component('editar-categoria', {
  props: ['value'],
  data() {
        return {
            'categoria': {
              id: '',
              categoria: '',
              monto_minimo: 0,
              icono_path: ''
            },
            'precios' : [],
            selectedFile: null,
            iconoBase64: null
        }
    },
  template: `
        <div>         
          <div class="modal fade " id="myModalCategoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document" >
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Edición de Categoría</h4>
                </div>
                <div class="modal-body">
                  
                    <div class="col-md-12">

                      <div class="form-group col-md-6">
                        <div class="form-group col-md-12">
                          <label>Categoría</label>
                          <input type="text" class="form-control text-left" v-model="categoria.categoria">
                        </div>

                        <div class="form-group col-md-12">
                          <label>Monto Mínimo</label>
                          <input type="number" class="form-control text-left" v-model="categoria.monto_minimo">
                        </div>

                        <div class="form-group col-md-12">
                          <label>Icono</label>
                          <img id="imgcat" :src="imagePath()" class="user-image" width="60px" height="60px" />
                          <input 
                            type="file" 
                            @change="onFileSelected"
                            style="display: none"
                            ref="fileInput">
                          <button @click ="$refs.fileInput.click()">Cargar Archivo</button>
                        </div>



                      </div>

                      <div class="form-group col-md-6">
                        <div class="container box-body table-responsive" style="padding-bottom: 0px;">
                          <table class="table table-bordered table-hover table-responsive">
                            <thead>
                              <tr>
                                <th width="25px" class="text-center" style=" padding-left: 0px;">
                                  <input type="checkbox" id="checkall" v-on:click="check_all()" ></th>
                                <th>Tipo de Precio</th>
                              </tr>
                            </thead>
                          </table>
                        </div>  
                        <div class="container box-body table-responsive" style="height: 200px;">
                          <table class="table table-bordered table-hover table-responsive">
                            <tbody>
                              <tr v-for="data in precios">
                                <td width="25px" class="text-center" style=" padding-left: 0px;"><input type="checkbox" v-model="data.habilitado"></td>
                                <td>{{data.desc_precios}}</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>

                    </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                  <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="guardar_categoria()" >Guardar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
  `,
  watch: {
      // whenever question changes, this function will run
      value: function () {
        this.categoria = this.value;
        this.cargar_precios();
      }  
  },
  methods: {
    cargar_precios: function() {
      axios.get( 'cliente_categoriaventa_tipoprecios/' + this.categoria.id
          ).then(response => {
            //console.log(response);
            this.precios = response.data;
            this.precios.forEach(function(precio){
              if (precio.habilitado == 1){
                precio.habilitado = true
              }
              else{
                precio.habilitado = false
              }
            });
            //console.log(this.cliente_select);
          })
      .catch(function(error){
        console.log(error);
         swal("No se pudo cargar los tipos de precios..!!", 'Contacte con Soporte Técnico', "error");
      });
    },
    guardar_categoria: function() {    
        objcategoria = {
          'categoria': this.categoria,
          'listaprecios': this.precios,
        };

        axios.post( 'cliente_categoriaventa_guardar', objcategoria
            ).then(response => {
              //console.log(response);
              if (response.data != null){
                this.$emit('categoria_actualizada', this.categoria);
              }
            })
        .catch(function(error){
          console.log(error);
          swal("No se pudo guardar la categoria..!!", 'Contacte con Soporte Técnico', "error");
        });

    },
    check_all: function() {
      chk = document.getElementById("checkall").checked;
      this.precios.forEach(function(precio){
        precio.habilitado = chk
      });

    },
    imagePath: function() {
      url = window.location.href
      pos = url.search('cliente');
      url = url.substring(0,pos);
      tmppath = url + '/public/img/perfil.jpg'
      if (this.categoria.icono_path != null){
        if (this.categoria.icono_path != ''){
          tmppath = url + '/public/img/categoriaventa/' + this.categoria.icono_path
        }
      }
      return tmppath
    },
    onFileSelected: function(event) {
      //console.log(event)
      this.selectedFile = event.target.files[0]
      //console.log(selectedFile)

      let formData = new FormData();
      formData.append('iconoBase64', this.selectedFile);

      axios.post( 'cliente_categoriaventa_guardarimagen', formData, {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
          }).then(response => {
            this.categoria.icono_path = response.data

            let reader = new FileReader();
            if(this.selectedFile) {
              reader.onload = () => {
                imgBase64 = reader.result;
                this.iconoBase64 = imgBase64;
                this.iconoBase64 = this.iconoBase64.substring(22);
                //console.log(this.iconoBase64)
                //this.iconoBase64 = window.btoa(imgBase64);
                document.getElementById('imgcat')
                .setAttribute(
                    'src', imgBase64
                );

              }
              reader.readAsDataURL(this.selectedFile);
            }
          })
      .catch(function(error){
        console.log(error);
        swal("No se pudo guardar la imagen de la categoria..!!", 'Contacte con Soporte Técnico', "error");
      });

      /*
      document.getElementById('imgcat')
      .setAttribute(
          'src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg=='
      );
     */ 
    }
  },  
  created: function(){
  }
});

var categoriamodel = {
      mostrar_categorias: false,
      categoria_seleccionada: {},
      categorias: []
    },
controller_cliente_categoria
 = new Vue({
   el: '#app_cliente_categoria',
   data: categoriamodel,
   methods: {
      cargar_categoriaclientes: function() {
        axios.get( 'cliente_categoriasventa'
            ).then(response => {
              //console.log(response);
              this.categorias = response.data;
            })
        .catch(function(error){
          console.log(error);
           swal("No se pudo cargar los categorías..!!", 'Contacte con Soporte Técnico', "error");
        });
      },
      adicionar_categoria: function(){ 
        nuevacategoria = {
          id: 0,
          categoria: '',
          monto_minimo: 0,
          icono_path: ''
        }
        this.categoria_seleccionada = nuevacategoria;
        $("#myModalCategoria").modal("show");
      },
      editar_categoria: function(detalle){       
        this.categoria_seleccionada = detalle;
        $("#myModalCategoria").modal("show");
      },
      eliminar_categoria: function(detalle){       
        if (confirm("Desea eliminar la categoría " + detalle.categoria)){
          var id = detalle.id;
          axios.post( 'cliente_categoriaventa_eliminar', id
              ).then(response => {
                if (response.data != null){
                  if (response.data != 0)
                    this.cargar_categoriaclientes()
                  else
                    alert("No se pudo eliminar la categoría. Existe informción asociada")
                }
                else{
                }
              })
          .catch(function(error){
            console.log(error);
            swal("No se pudo guardar la categoria..!!", 'Contacte con Soporte Técnico', "error");
          });         
        }
      },
      actualiza_detallenota(nota){
        this.detalle_seleccionado.observaciones = nota;
      },
      activar_categorias: function(){
        this.mostrar_categorias = true;
      },
      cerrar_categorias: function(){
        window.location.replace("cliente");
      }
  },  

  created: function(){
     this.cargar_categoriaclientes();
  }

});