<?php
/* ------------------------------------------------
ARCHIVO: auth_library.php
DESCRIPCION: Librería de validación y actualización de sesión del sistema RPG
FECHA DE CREACIÓN: 24/07/2013
OTIC
------------------------------------------------ */
class Auth_library {
   /*
    * La siguiente función retorna TRUE si la sesión está activa
    * y además actualiza los datos del usuario de ser requerido
    * Si es FALSE y el parámetro $redi es TRUE, redirige a la pantalla de inicio 
    * de sesión
    */
   public function sess_validate($redi = FALSE, $sess_upda = FALSE) {
      $CI =& get_instance();      
      if ($CI->session->userdata('sess_id')) {
         //Preguntar si la variable de sesión 'usua' existe o si se requiere
         //que los datos de la misma sean actualizados
         if ((!$CI->session->userdata('usua')) || ($sess_upda == TRUE)) {
            $this->__sess_update();
         }
         return TRUE; 
      } else {         
         if (!$redi) {
            return FALSE;
         } else {
            print "<script> window.location.href = '".  base_url()."auth'; </script>";
         }
      }
   }        
   
   /* La siguiente función retorna TRUE si el usuario se encuentra autorizado para
    * acceder a un controlador o método.
    * Si es FALSE y el parámetro $redi es TRUE, redirige a la pantalla de inicio de
    * la aplicación
    */
   public function sess_authorize($perm, $redi = FALSE) {
      $CI =& get_instance();      
      $usuario = $CI->session->userdata('usua');      
    //  $nivel = $usuario->des_tip_usu;            
    //  if (in_array($nivel, $perm)) {
         RETURN TRUE;
    //  } else {         
    //     if (!$redi) {
    //        return FALSE;
    //     } else {         
             print "<script> window.location.href = '".  base_url()."auth'; </script>";
    //     }
    //  }           
      
   }
   
   /*
    * El siguiente procedimiento actualiza los datos del usuario en la sesión 
    * cuando sea requerido. Debe tomarse en cuenta que el modelo usuario_model 
    * debe estar instanciado automáticamente en el archivo autoload.php
    */
   private function __sess_update(){
      $CI =& get_instance();
      $CI->session->set_userdata('usua', $CI->usuario_model->usua_get($CI->session->userdata("sess_id")));
   }     
   
   /*
    * Desde este procedimiento se establecerán los mensajes que se mostrarán durante
    * la recarga de una página.
    */
   public function mssg_set($key) {      
      $CI =& get_instance();
      $CI->session->set_userdata('mssg', $key);
   }
   
   /*
    * Desde este procedimiento se manejaran los mensajes que se muestran durante
    * la recarga de una página.
    */
   public function mssg_get() {
      $mssg = array (
          0 => "error_prueba",
          700 => "Este usuario no existe",
          701 => "La contraseña nueva y la de confirmación no coinciden",
          702 => "La contraseña ha sido cambiada satisfactoriamente.",
          703 => "La contraseña actual es incorrecta.",
          
      );
      $CI =& get_instance();      
      if ($CI->session->userdata('mssg') != NULL) {
         $key = $CI->session->userdata('mssg');
         if ($key != NULL) {
            $mensaje = is_numeric($key) ? $mssg[$key] : $key;
            print "
               <script>
                  alert('$mensaje');
               </script>
            ";
         }
      }
      $CI->session->set_userdata('mssg', NULL);
   }
   
}

?>