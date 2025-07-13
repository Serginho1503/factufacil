<?php

/* ------------------------------------------------
  ARCHIVO: common_library.php
  DESCRIPCION: Funciones comunes
  FECHA DE CREACIÓN: 24/07/2013
  OTIC
  ------------------------------------------------ */

class Common_library {
    /*
     * Calcular edad
     */

    function edad($fech_ini, $fech_fin = NULL) {
        if ($fech_fin != NULL) {
            $dia = date("j", strtotime($fech_fin));
            $mes = date("n", strtotime($fech_fin));
            $anno = date("Y", strtotime($fech_fin));
        } else {
            $dia = date("j");
            $mes = date("n");
            $anno = date("Y");
        }
        $dia_ini = substr($fech_ini, 0, 2);
        $mes_ini = substr($fech_ini, 3, 2);
        $anno_ini = substr($fech_ini, 6, 4);
        if ($mes_ini > $mes) {
            $calc_edad = $anno - $anno_ini - 1;
        } else {
            if ($mes == $mes_ini AND $dia_ini >= $dia) {
                $calc_edad = $anno - $anno_ini - 1;
            } else {
                $calc_edad = $anno - $anno_ini;
            }
        }
        return $calc_edad;
    }

    function formato_fecha($fecha, $separador = "-") {
        $dia = substr($fecha, 8, 2);
        $mes = substr($fecha, 5, 2);
        $anno = substr($fecha, 0, 4);
        return $dia . $separador . $mes . $separador . $anno;
    }

    function formato_moneda($valor) {
        return number_format($valor, 2, ",", ".");
    }

    function sanear_string($string) {

        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            "."), '', $string
        ); // Lo quite, " "


        return $string;
    }

    function limpiarUploads($carpeta) {
        // Eliminar archivos en el directorio
        $dir = "public/$carpeta/"; //uploads/
        $ficheroseliminados = 0;
        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if (is_file($dir . $file)) {
                if (unlink($dir . $file)) {
                    $ficheroseliminados++;
                }
            }
        }
    }

    function number_format_drop_zero_decimals($n, $n_decimals) {
        return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
    }

    function quitarDoblesSaltos($string) {
        $buscar = array(chr(13) . chr(10), "\r\n", "\n", "\r");
        $reemplazar = array("", "", "", "");
        $cadena = str_ireplace($buscar, $reemplazar, $string);
        return $cadena;
    }

    function getUltimoDiaMes($anio, $mes) {
        return date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
    }

    function rellenarCeros($campo, $identificador, $longitud = 15) {
        $long_camp = strlen(trim($campo));
        $p1 = trim($identificador);
        $p2 = "";

        for ($i = 0; $i < $longitud - $long_camp; $i++) {
            $p2 = $p2 . $p1;
        }
        $ip = trim($p2) . trim($campo);
        return $ip;
    }

//    function mimeTypeBlob($blob) {
//        // Firmas de archivos con mimetype asociado 
//        $mimes = array(
//            '474946383761' => 'image/gif', // GIF87a gif
//            '474946383961' => 'image/gif', // GIF89a gif
//            '89504E470D0A1A0A' => 'image/png', // PNG
//            'FFD8FFE0' => 'image/jpeg', // JFIF jpeg
//            'FFD8FFE1' => 'image/jpeg', // EXIF jpeg
//            'FFD8FFE8' => 'image/jpeg', // SPIFF jpeg 
//        );
//        $blob = base64_decode($blob);
//        // se obtienen los primeros 60 bytes shouldnt
//        // más que suficientes para determinar la firma del archivo
//        $firma = substr($blob, 0, 60);
//        try {
//            $desempaquetado = unpack('H*', $firma);
//            // Cadena de representación de valores hexadecimales
//            $firma = array_shift($desempaquetado);
//        } catch (ErrorException $ee) {
//
//            throw new MimeTypeBlobExcepcion ();
//        }
//        foreach ($mimes as $MagicNumber => $Mime) {
//            if (stripos((string) $firma, (string) $MagicNumber) === 0) {
//
//                return $Mime;
//            }
//        }
//        return 'application/octet-stream';
//    }
//    
//    function mostrarBlob ( $imagenBlob ) {
//        $mime = Archivos::obtenerMimeTypeBlob ( $imagenBlob );
//        return 'data:' . $mime . ';base64,' . $imagenBlob;
//    }

}

?>