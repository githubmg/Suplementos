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
ini_set('max_execution_time', 300); 
class ReporteGanancias {


    public static  function getTitulos() {
        $html = "
            <thead class='headerTabla'>
                <tr>
                    <th scope='col' class=''> Nro. Venta </th>
                    <th scope='col' class=''> Fecha </th>
                    <th scope='col' class=''> Producto </th>
                    <th scope='col' class=''> Cantidad </th>
                    <th scope='col' class=''> Monto &Iacute;tem</th>
                    <th scope='col' class=''> Costo</th>
                </tr>
            </thead>
    ";
    return $html;
    }

    public static function getTableFooter($parametros,$cantidadDePaginas){
			
            $enabledPrimeroyAnterior = "";
            $enabledUltimoySiguiente = "";

            $indicePag = $parametros['p'];

            $href='index.php?';
            if(isset($parametros['fecha_desde'])) {
                $href=$href.'fecha_desde='.$parametros['fecha_desde'].'&';
            }
			
            if(isset($parametros['fecha_hasta'])) {
                $href=$href.'fecha_hasta='.$parametros['fecha_hasta'].'&';
            }
			if(isset($parametros['comboProductos'])) {
                $href=$href.'comboProductos='.$parametros['comboProductos'].'&';
            }
            return PageView::getTableFooter($indicePag,$cantidadDePaginas,12,'venta',$href,$parametros);
            }

    public static function getItemVentaHTML(ItemVenta $item, $i) {
        $id_item_venta = $item->getId();
        $id_venta = $item->getVenta()->getId();
        $total_abonado = $item->getVenta()->getTotalAbonado();
        $fecha = $item->getVenta()->getFecha();

        $producto = $item->getProducto()->getDescripcionCompleta();
        $cantidad = $item->getCantidad();
        $monto_total = $item->getMontoTotal();
        $costo = $item->getCosto();
        //despues los clavo en un String
        $clase = VentaView::traerClase($i);
        $html = "
            <tr class='$clase'>
                <td>$id_venta</td>
                <td>$fecha</td>
                <td>$producto</td>
                <td>$cantidad</td>
                <td>$monto_total</td>
                <td>$costo</td>
            </tr>
        ";
        return $html;
    }
    public static function getListadoHTML($todasDePagina,$cantidad, $parametros) {
        $html = "<table id='ventas' class='tabla'>";
        $html .= ReporteGanancias::getTitulos( );
        $i=0;
        foreach( $todasDePagina as $mi_item_venta ){
            $i++;
            $iv = new ItemVenta($mi_item_venta['id_item_venta']);
            $html .= ReporteGanancias::getItemVentaHTML( $iv ,$i);

        }
        $html .= self::getTableFooter($parametros,$cantidad);
        $html .="</table>";
        return $html;
    }

    public static function getBuscadorHTML() {
        $html = '<form id="buscador" method="GET" >
                    <table>
                        <tr>
                            <td>Buscar</td>
                        </tr>
                        <tr>
                            <td>Fecha Desde: </td>
                        </tr>
                        <tr>
                            <td><input id="txtFecha" type="text" name="fecha_desde"/></td>
                        </tr>
                        <tr>
                            <td>Fecha Hasta: </td>
                        </tr>
                        <tr>
                            <td><input id="txtFecha2" type="text" name="fecha_hasta"/></td>
                        </tr>
                        <tr>
                            <td>Producto: </td>
                        </tr>
                        <tr>
                            <td>'.ProductoView::getComboProductosHTML().'</td>
                        </tr>
                        <tr>
                            <td align="center"> <input type="checkbox" name="subempresa" value="SM">SM </td>
                        </tr>
                        <tr>
                            <td align = "center"><br /><input type="submit"  class="botonNegro" value="Buscar"/></td>
                        </tr>
                    </table>
                </form>';
        return $html;
    }
    
    public static function getTotalesHTML($fecha_desde, $fecha_hasta, $producto, $subempresa) {
		
        if ($subempresa =='SM') {
            $montoTotal = DB::getTotalVentasSMByFechayProductoConCosto($fecha_desde, $fecha_hasta, $producto);
            $cantidad = DB::getTotalCantidadVentasSMByFechayProducto($fecha_desde, $fecha_hasta, $producto);
            $costoTotal = DB::getTotalCostoVentasSMByFechayProducto($fecha_desde, $fecha_hasta, $producto);
		}
        else {
            $montoTotal = DB::getTotalVentasByFechayProductoConCosto($fecha_desde, $fecha_hasta, $producto);
            $cantidad = DB::getTotalCantidadVentasByFechayProducto($fecha_desde, $fecha_hasta, $producto);
            $costoTotal = DB::getTotalCostoVentasByFechayProducto($fecha_desde, $fecha_hasta, $producto);			
        }
		$comisiones = 0;
		if ($producto == '' && $subempresa =='' ){
			$comisionesDB = DB::getTotalComisionesByFecha($fecha_desde, $fecha_hasta);
			$comisiones = $comisionesDB->importe;
		}
		
		
        $ganancia = floatval($montoTotal->monto_total - $costoTotal->costo + $comisiones);
		if($costoTotal->costo != 0)
		{
        $porcentaje = floatval($ganancia/$costoTotal->costo)*100;
		} else {
		$porcentaje = 0;
		}
		
        $html =  '  <table class="tabla">
                    <thead class="headerTabla">
                        <tr>
                           <th colspan=7>Resumen de Ganancias</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="even">Cantidad Vendida:'.$cantidad->cantidad.'</td>
                        <td class="even">Total Ventas: $'.number_format((float)$montoTotal->monto_total, 2, ',', '').'</td>
                        <td class="even">Total Costo: $'.number_format((float)$costoTotal->costo, 2, ',', '').'</td>';                        
						if ($comisiones <> 0 ){
						$html.= '<td class="even">Movimientos: $'.number_format((float) $comisiones, 2, ',', '').'</td>';
						}				
                        $html.='<td class="even">Total Ganancia: $'.number_format((float)$ganancia, 2, ',', '').'</td>
						<td class="even">% Ganancia:'.number_format((float)$porcentaje, 2, ',', '').'% </td>
                    </tr>
                    </tbody>
                </table>';
		echo $html;		
    }

}
?>
