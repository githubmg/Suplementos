<?php
	class ItemVentaDB {
		private $id_producto;
		private $cantidad;
		private $monto_total;
		private $subempresa;
		private $fecha;
		private $costo;		
		private $id;
		
		function __construct() {
						$cantidadParametros = func_num_args();
						$parametros = func_get_args();
						if ($cantidadParametros==1) {
							$id_item_venta = $parametros[0];
							$this->__construct_by_id($id_item_venta);
						} 
					}
					
			 private function __construct_by_id($id) {
                $ventaDB = DB::getItemVentaDBById($id);

                $this->id_producto = $ventaDB->id_producto;
                $this->cantidad = $ventaDB->cantidad;
                $this->monto_total = $ventaDB->monto_total;
                $this->fecha = $ventaDB->fecha;
                $this->costo = $ventaDB->costo;
				$this->subempresa = $ventaDB->subempresa;
				$this->id = $id;
                
            }	
			// private function __construct_by_param(Producto $producto, $cantidad, $monto_total,$id) {
			
                // $this->setProducto($producto);
                // $this->setCantidad($cantidad);
                // $this->setMontoTotal($monto_total);
				// $this->setId($id);


			// }				
			// private function __construct_by_param(Producto $producto, $cantidad, $monto_total,  $costo, $venta) {
                // $this->setProducto($producto);
                // $this->setCantidad($cantidad);
                // $this->setMontoTotal($monto_total);
                // $this->costo($costo);
                // $this->venta($venta);
        
			// }	
			
			public function calcularCostoItem(){
				if(!$this->costo){
					$this->costo=0;
				}
				//FRAN: Agregamos la condición de que las compras que saldan el stock sean anteriores a la venta.
				
				$comprasDB = DB::getItemsComprasNoAsignadasOrdenadasPorFechaByProducto($this->id_producto, $this->fecha);
				if($comprasDB){
				
				//Evalua si existen suficientes unidades no asignadas del producto para asignar costo a la venta
					$cantidadDisponible = 0;
					foreach($comprasDB as $miCompra){
						
						$cantidadDisponible += intval($miCompra['unidades_no_asignadas']);
						
						if ($cantidadDisponible >= $this-> cantidad) {
							break;  
						}
					}
					
					
					if (intval($cantidadDisponible) >= intval($this->cantidad)){
					$cantidad =  intval($this->cantidad);	
						foreach($comprasDB as $miCompra){
							if ($miCompra['unidades_no_asignadas'] >= $cantidad){
							//La compra es suficiente para terminar de calcular el costo
							
								$this->costo += $cantidad * ($miCompra['precio_unitario']);
								DB::asignarUnidACompra(intval($miCompra['id_item_compra']),$cantidad);
								break;
							}else{
							//La compra NO es suficiente para terminar de calcular el costo
								
								$this->costo += intval($miCompra['unidades_no_asignadas']) * (floatval($miCompra['precio_unitario']));
								$cantidad -= intval($miCompra['unidades_no_asignadas']);
								DB::asignarUnidACompra(intval($miCompra['id_item_compra']),intval($miCompra['unidades_no_asignadas']));
							}
						}
						
					}else{
					//FRAN: Asigno el costo mas reciente, 0 si no existe compra anterior a la venta
					$this->costo = floatval(DB::getCostoMasReciente($this->id_producto,$this->fecha)) * intval($this->cantidad);		
						
					}
				}
			}
			public function reasignarCostoItem(){
					$this->calcularCostoItem();
					DB::updateItemVentaDB($this);
				
			}
		
			
           
			public function getCosto(){
                return $this->costo;
            }
			
			public function getId(){
                return $this->id;
            }

	}		