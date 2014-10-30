<?php
	class ItemVentaView {
		public static function GenerarTablaAlta($items){
                    $total = 0;
                    foreach($items as $item) {
                        if (is_numeric($item->getMontoTotal())) {
                            $total = $total + $item->getMontoTotal();
                        }
                        else {
                            $total = "Error: aseg&uacute;rese que todos los montos totales ingresados son numéricos";
                            break;
                        } 
                    }
		$html = "
                        <table>
                            <tr>
                                <td style='border: 2px solid; font-weight:bold; font-size:large'> Monto Total: ".$total."</td>
                            </tr>
                        </table>
                        <table >
					<tr>
						<td style='border: 1px solid #333333;'> Producto </td>
						<td style='border: 1px solid #333333;'> Cantidad </td>
						<td style='border: 1px solid #333333;'> Monto </td>
						<td style='border: 1px solid #333333;'> Subempresa </td>
						<td style='border: 1px solid #333333;'> Acci&oacute;n</td>
					</tr>
					";
					foreach($items as $item){
					$html.="
					<tr>
						<td style='border: 1px solid #333333;'>".$item->getProducto()->getDescripcionCompleta()." </td>
						<td style='border: 1px solid #333333;'>".$item->getCantidad()."</td>
						<td style='border: 1px solid #333333;'>".$item->getMontoTotal()." </td>
						<td style='border: 1px solid #333333;'>".$item->getSubempresa()." </td>
						<td style='border: 1px solid #333333;'><a href='#' id='lnkBorrar'".$item->getId()." onclick='borrarItem(".$item->getId().");' >Quitar</a></td>						
					</tr>";
					}
			$html.="</table>";
		return $html;
		
		}
	}		