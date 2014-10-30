<?php
	
    class StringController {
			
      	public static function parsearFecha($fechaAParsear) {
            if ($fechaAParsear == "") {
                return "";
            }
            $separacionMas = explode(" ", $fechaAParsear);
            $dia = $separacionMas[0];

            if (strstr($fechaAParsear,'Enero',true)){
                $mes = 1;
            }
            if (strstr($fechaAParsear,'Febrero',true)){
                $mes = 2;
            }
            if (strstr($fechaAParsear,'Marzo',true)){
                $mes = 3;
            }
            if (strstr($fechaAParsear,'Abril',true)){
                $mes = 4;
            }
            if (strstr($fechaAParsear,'Mayo',true)){
                $mes = 5;
            }
            if (strstr($fechaAParsear,'Junio',true)){
                $mes = 6;
            }
            if (strstr($fechaAParsear,'Julio',true)){
                $mes = 7;
            }
            if (strstr($fechaAParsear,'Agosto',true)){
                $mes = 8;
            }
            if (strstr($fechaAParsear,'Septiembre',true)){
                $mes = 9;
            }
            if (strstr($fechaAParsear,'Octubre',true)){
                $mes = 10;
            }
            if (strstr($fechaAParsear,'Noviembre',true)){
                $mes = 11;
            }
            if (strstr($fechaAParsear,'Diciembre',true)){
                $mes = 12;
            }

            $anio =  substr($fechaAParsear, -4);

            return $anio.'/'.$mes.'/'.$dia;

    }

    public static function formatearFecha($fecha) {
        return date_format(date_create($fecha),'d-m-Y');
    }

    public static function reemplazarEspacios($texto) {
        return str_replace(" ", "&nbsp;", $texto);
    }

    public static function sanitizarParaHTML($texto) {

        $search  = array('&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Ntilde;','&Uacute;','&Uuml;','&iexcl;','&iquest;','&aacute;',
                               '&eacute;','&iacute;', '&oacute;', '&ntilde;', '&uacute;', '&uuml;','&ordf;','&ordm;','&amp;','&ldquo;','&rdquo;');
        $replace = array('Á', 'É', 'Í', 'Ó', 'Ñ','Ú','Ü','¡','¿','á','é','í','ó','ñ','ú','ü','º','ª','&','""','""');
        $texto = str_replace($search, $replace, $texto);
        return $texto;
    }
}
	
?>
