<?php
	
	class ActualizacionView {
	
            public static function getFormAltaHTML( $categoria){
                switch ($categoria) {
                    case 'venta':
                        return VentaView::getAltaVentaHTML();
                        break;
                    case 'compra':
                        return CompraView::getAltaCompraHTML();
                        break;
                    case 'cliente':
                        return ClienteView::getAltaClienteHTML();
                        break;
                    case 'proveedor':
                        return ProveedorView::getAltaProveedorHTML();
                        break;
                    case 'producto':
                        return ProductoView::getAltaProductoHTML();
                        break;
					case 'comision':
                        return ComisionView::getAltaComisionHTML();
                        break;
                }
                

                $html = "<table id='ventas' class='tabla'>";
                $html .= VentaView::getTitulos( );
                $i=0;

                foreach( $todasDePagina as $mi_venta ){
                    $i++;
                    $v = new Venta($mi_venta['id_venta']);
                    $html .= VentaView::getHTML( $v ,$i);
                }
                
                $html .= VentaView::getTableFooter($indicePag,$cantidadDePaginas);
                $html .="</table>";
                return $html;
            }

                public static function getFormModifHTML( $categoria, $id){
                    switch ($categoria) {
                        case 'venta':
                            return VentaView::getModifVentaHTML($id);
                            break;
                        case 'compra':
                            return CompraView::getModifCompraHTML($id);
                            break;
                        case 'cliente':
                            return ClienteView::getModifClienteHTML($id);
                            break;
                        case 'proveedor':
                            return ProveedorView::getModifProveedorHTML($id);
                            break;
                        case 'producto':
                            return ProductoView::getModifProductoHTML($id);
                            break;
						case 'comision':
							return ComisionView::getModifComisionHTML($id);

                    }
		}
                
                public static function getFormBajaHTML( $categoria, $id){
					$html = "              Aviso: El registro sera eliminado <br/><br/><br/> ";
					return $html;
                    // switch ($categoria) {
                        // case 'venta':
                            // return VentaView::getBajaVentaHTML($id);
                            // break;
                        // case 'compra':
                            // return CompraView::getBajaCompraHTML($id);
                            // break;
                        // case 'cliente':
                            // return ClienteView::getBajaClienteHTML($id);
                            // break;
                        // case 'proveedor':
                            // return ProveedorView::getBajaProveedorHTML($id);
                            // break;
                        // case 'producto':
                            // return ProductoView::getBajaProductoHTML($id);
                            // break;

                    // }
		}
		
                   
                
                
        }	
?>
