<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReporteGanancias
 *
 * @author ggrin
 */
class ReporteDeudores {

    public static  function getTitulos() {
        $html = "
            <thead class='headerTabla'>
                <tr>
                    <th scope='col' class='' > Cliente </th>
                    <th scope='col' class=''> Provincia </th>
                    <th scope='col' class=''> Localidad </th>
                    <th scope='col' class=''> Domicilio </th>
                    <th scope='col' class=''> Tel&eacute;fono </th>
                    <th scope='col' class=''> Email </th>
                    <th scope='col' class=''> Total Adeudado </th>
                </tr>
            </thead>
    ";
    return $html;
    }

    public static function getTableFooter(){
            /*$enabledPrimeroyAnterior = "";
            $enabledUltimoySiguiente = "";

            $indicePag = $parametros['p'];

            $href='index.php?';
            if(isset($parametros['fecha_desde'])) {
                $href=$href.'fecha_desde='.$parametros['fecha_desde'].'&';
            }

            if(isset($parametros['fecha_hasta'])) {
                $href=$href.'fecha_hasta='.$parametros['fecha_hasta'].'&';
            }
            return PageView::getTableFooter($indicePag,$cantidadDePaginas,12,'venta',$href,$parametros);*/
            }

    public static function getFilaDeudor($deudor, $i) {
        
        $cliente = new Cliente($deudor['id_cliente']);
        $nombre = $cliente->getPersona()->getNombre();
        $domicilio = $cliente->getPersona()->getDomicilio();

        if ($domicilio->getLocalidad()->getId() ){
           $provincia = $domicilio->getLocalidad()->getProvincia()->getDescripcion();
           $localidad = $domicilio->getLocalidad()->getDescripcion();
        } else {
            $provincia = "";
            $localidad = "";          
        }
        
        $clase = VentaView::traerClase($i);
        $html = "
            <tr class='".$clase."'>
                <td>".$nombre."</td>
                <td>".$provincia."</td>
                <td>".$localidad."</td>
                <td>".$domicilio->getDescripcion()."</td>
                <td>".$cliente->getPersona()->getTelefono()."</td>
                <td>".$cliente->getPersona()->getEmail()."</td>
                <td>".($deudor['abonado'] - $deudor['monto'])."</td>
            </tr>
        ";
        return $html;
    }


    public static function getListadoHTML() {
        $html = "<table id='deudores' class='tabla'>";
        $html .= ReporteDeudores::getTitulos();
        $deudores = DB::getDeudores();

        $i=0;
        foreach( $deudores as $deudor){
            $i++;
            $cliente = new Cliente($deudor['id_cliente']);
            $html .= ReporteDeudores::getFilaDeudor($deudor ,$i);
        }
        
        $html .= self::getTableFooter();
        $html .="</table>";
        return $html;
    }

    public static function getTotalesHTML($fecha_desde, $fecha_hasta, $producto, $subempresa) {
        if ($subempresa =='SM') {
            $montoTotal = DB::getTotalVentasSMByFechayProducto($fecha_desde, $fecha_hasta, $producto);
            $cantidad = DB::getTotalCantidadVentasSMByFechayProducto($fecha_desde, $fecha_hasta, $producto);
            $costoTotal = DB::getTotalCostoVentasSMByFechayProducto($fecha_desde, $fecha_hasta, $producto);
        }
        else {
            $montoTotal = DB::getTotalVentasByFechayProducto($fecha_desde, $fecha_hasta, $producto);
            $cantidad = DB::getTotalCantidadVentasByFechayProducto($fecha_desde, $fecha_hasta, $producto);
            $costoTotal = DB::getTotalCostoVentasByFechayProducto($fecha_desde, $fecha_hasta, $producto);
        }

        $ganancia = floatval($montoTotal->monto_total - $costoTotal->costo);

        $porcentaje = floatval($ganancia/$costoTotal->costo)*100;

        echo '  <table class="tabla">
                    <thead class="headerTabla">
                        <tr>
                           <th colspan=7>Resumen de Ganancias</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="even">Cantidad Vendida:'.$cantidad->cantidad.'</td>
                        <td class="even">Total Ventas:'.$montoTotal->monto_total.'</td>
                        <td class="even">Total Costo:'.$costoTotal->costo.'</td>
                        <td class="even">Total Ganancia:'.$ganancia.'</td>
                        <td class="even">% Ganancia:'.$porcentaje.'</td>
                    </tr>
                    </tbody>
                </table>';
    }
}
?>
