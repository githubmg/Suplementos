<?PHP
ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );
class Exportarb  {
	public static function getTitulos($clase) {
			$titulos = array();
				switch ($clase) {
					case 'venta':
							array_push($titulos,'Nro Venta');
							array_push($titulos,'Cliente');
							array_push($titulos,'Producto');
							array_push($titulos,'Stock Disponible');
							array_push($titulos,'Cantidad');
							array_push($titulos,'Monto Item');
							array_push($titulos,'Costo Asignado');
							array_push($titulos,'Total Abonado');
							array_push($titulos,'Fecha');
							array_push($titulos,'Subempresa');
							break;
							
					case 'compra':					
							array_push($titulos,'Id_Compra');
							array_push($titulos,'Proveedor');
							array_push($titulos,'Producto');
							array_push($titulos,'Cantidad');
							array_push($titulos,'Costo Unitario');
							array_push($titulos,'MontoTotal');
							array_push($titulos,'TotalCompra');
							array_push($titulos,'Stock Disponible');
							array_push($titulos,'Fecha');
							array_push($titulos,'Subempresa');
							break;
					case 'cliente':					
							array_push($titulos,'Cliente');
							array_push($titulos,'Subempresa');
							array_push($titulos,'Provincia');
							array_push($titulos,'Localidad');
							array_push($titulos,'Domicilio');
							array_push($titulos,'Tel');			
							array_push($titulos,'Email');
							array_push($titulos,'Observaciones');
							break;
					case 'proveedor':					
							array_push($titulos,'Proveedor');
							array_push($titulos,'Provincia');
							array_push($titulos,'Localidad');
							array_push($titulos,'Domicilio');
							array_push($titulos,'Tel');			
							array_push($titulos,'Email');
							array_push($titulos,'Observaciones');
							break;		
					case 'producto':					
							array_push($titulos,'Producto');
							array_push($titulos,'Stock Total');						
							array_push($titulos,'Stock SM');
							array_push($titulos,'Observaciones');
							break;
					case 'comision':					
							array_push($titulos,'Nro. Movimiento');
							array_push($titulos,'Cliente');					
							array_push($titulos,'Fecha');
							array_push($titulos,'Concepto');
							array_push($titulos,'Nro. de Venta');					
							array_push($titulos,'Importe');
							array_push($titulos,'Observaciones');
							break;		
									 
				}
	return $titulos;
	}
	
	public static function getArrayValores($clase, $fecha_desde, $fecha_hasta,$idCliente, $idProveedor, $idProducto, $subempresa){
			$ids = array();
            $ids = DB::getIdsTabla($clase, $fecha_desde, $fecha_hasta,$idCliente, $idProveedor, $idProducto, $subempresa);
            $cuerpoReporte = array();
            switch ($clase){
                case 'venta':
                foreach( $ids as $mi_id_venta ){
                    $v = new Venta($mi_id_venta['id_venta']);
					$idsItemsVentas = array();
					$idsItemsVentas = DB::getIdsItemsVentasByVentaAndParam(intval($mi_id_venta['id_venta']),$idProducto,$subempresa);
					
					foreach ($idsItemsVentas as $mi_id_itemVenta) {
						$item = new ItemVenta(intval($mi_id_itemVenta['id_item_venta']));
						$lineaReporte = array();
						array_push($lineaReporte,$v->getId());
						array_push($lineaReporte,$v->getCliente()->getPersona()-> getNombre());
						array_push($lineaReporte,$item->getProducto()->getDescripcion());
						array_push($lineaReporte,$item->getProducto()->getStockDisponible());
						array_push($lineaReporte,$item->getCantidad());
						array_push($lineaReporte,$item->getMontoTotal());
						array_push($lineaReporte,$item->getCosto());
						array_push($lineaReporte,$v->getTotalAbonado());
						array_push($lineaReporte,  StringController::formatearFecha($v->getFecha()));
						array_push($lineaReporte,$item->getSubempresa());
						array_push($cuerpoReporte,$lineaReporte);
						}
                    }

                break;
                case 'compra':
                foreach( $ids as $mi_id_compra ){
					
                    $c = new Compra(intval($mi_id_compra['id_compra']));
					$idsItemsCompras = array();
					$idsItemsCompras = DB::getIdsItemsComprasByCompraAndParam(intval($mi_id_compra['id_compra']),$subempresa);
					foreach( $idsItemsCompras as $mi_id_itemCompra ){
						
						$item = new ItemCompra(intval($mi_id_itemCompra['id_item_compra']));
						$lineaReporte = array();
						array_push($lineaReporte,$c->getId());
						array_push($lineaReporte,$c->getProveedor()->getPersona()-> getNombre());
						array_push($lineaReporte,$item->getProducto()->getDescripcion());
						array_push($lineaReporte,$item->getCantidad());
						array_push($lineaReporte,$item->getPrecioUnitario());
						array_push($lineaReporte,$item->getMontoTotal());
						array_push($lineaReporte,$c->getTotal());
						array_push($lineaReporte,$item->getProducto()->getStockDisponible());
						array_push($lineaReporte,  StringController::formatearFecha($c->getFecha()));
						array_push($lineaReporte,$item->getSubempresa());
						array_push($cuerpoReporte,$lineaReporte);
						}
                    }
				//die();
                break;
                case 'cliente':

                foreach( $ids as $mi_id_cliente ){
                    $c = new Cliente(intval($mi_id_cliente['id_cliente']));
                    $lineaReporte = array();
                    array_push($lineaReporte,$c->getPersona()-> getNombre());
                    array_push($lineaReporte,$c->getSubempresa());
                    if($c->getPersona()->getDomicilio()->getLocalidad()->getId()){
                            array_push($lineaReporte,$c->getPersona()->getDomicilio()->getLocalidad()->getProvincia()->getDescripcion());
                            array_push($lineaReporte,$c->getPersona()->getDomicilio()->getLocalidad()->getDescripcion());
                    }else{
                            array_push($lineaReporte,"");
                            array_push($lineaReporte,"");
                    }
                    array_push($lineaReporte,$c->getPersona()->getDomicilio()->getDescripcion());
                    array_push($lineaReporte,$c->getPersona()->getTelefono());
                    array_push($lineaReporte,$c->getPersona()->getEmail());
                    array_push($lineaReporte,$c->getPersona()->getObservaciones());
                    array_push($cuerpoReporte,$lineaReporte);
                    }
                break;
                case 'proveedor':

                foreach( $ids as $mi_id_proveedor ){
                    $c = new Proveedor(intval($mi_id_proveedor['id_proveedor']));
                    $lineaReporte = array();
                    array_push($lineaReporte,$c->getPersona()-> getNombre());
                    if($c->getPersona()->getDomicilio()->getLocalidad()->getId()){
                            array_push($lineaReporte,$c->getPersona()->getDomicilio()->getLocalidad()->getProvincia()->getDescripcion());
                            array_push($lineaReporte,$c->getPersona()->getDomicilio()->getLocalidad()->getDescripcion());
                    }else{
                            array_push($lineaReporte,"");
                            array_push($lineaReporte,"");
                    }
                    array_push($lineaReporte,$c->getPersona()->getDomicilio()->getDescripcion());
                    array_push($lineaReporte,$c->getPersona()->getTelefono());
                    array_push($lineaReporte,$c->getPersona()->getEmail());
                    array_push($lineaReporte,$c->getPersona()->getObservaciones());
                    array_push($cuerpoReporte,$lineaReporte);
                    }
                break;
                case 'producto':

                foreach( $ids as $mi_id_producto ){
                    $c = new Producto(intval($mi_id_producto['id_producto']));
                    $lineaReporte = array();
                    array_push($lineaReporte,$c->getDescripcion());
                    array_push($lineaReporte,$c->getStockDisponible());
                    array_push($lineaReporte,$c->getObservaciones());
                    array_push($cuerpoReporte,$lineaReporte);
                    }
                break;
				case 'comision':

                foreach( $ids as $mi_id_comision ){
                    $c = new Comision(intval($mi_id_comision['id']));
                    $lineaReporte = array();
                    array_push($lineaReporte,$mi_id_comision['id']);
                    array_push($lineaReporte,$c->getCliente()->getPersona()-> getNombre());
                    array_push($lineaReporte, StringController::formatearFecha($c->getFecha()));
					array_push($lineaReporte,$c->getProducto());
                    array_push($lineaReporte,$c->getNroVenta());
					array_push($lineaReporte,$c->getImporte());
                    array_push($lineaReporte,$c->getObservaciones());
                    array_push($cuerpoReporte,$lineaReporte);
                    }
                break;


            }
	return 	$cuerpoReporte;
	}
}
?>
