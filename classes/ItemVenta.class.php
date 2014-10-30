<?php
	class ItemVenta {
		private $venta;
		private $producto;
		private $cantidad;
		private $monto_total;
		private $costo;
		private $subempresa;
		private $id;
		
		function __construct() {
						$cantidadParametros = func_num_args();
						$parametros = func_get_args();
						if ($cantidadParametros==1) {
							$id_item_venta = $parametros[0];
							$this->__construct_by_id($id_item_venta);
						} 
						else if ($cantidadParametros==5) {
					
							$producto = $parametros[0];
							$cantidad = $parametros[1];
							$monto_total = $parametros[2];
							$subempresa = $parametros[3];
							$id = $parametros[4];
				
							$this->__construct_by_param($producto,$cantidad,$monto_total,$subempresa,$id);
						} else {
							// $producto = $parametros[0];
							// $cantidad = $parametros[1];
							// $monto_total = $parametros[2];
							// $costo = $parametros[3];
							// $venta = $parametros[4];
							// $this->__construct_by_param($producto,$cantidad,$monto_total,$costo, $venta);
						}
					}
					
			 private function __construct_by_id($id) {
                $ventaDB = DB::getItemVentaById($id);

                $this->venta = new Venta(intval($ventaDB->id_venta));
                $this->producto = new Producto($ventaDB->id_producto);
                $this->cantidad = $ventaDB->cantidad;
                $this->monto_total = $ventaDB->monto_total;
                $this->costo = $ventaDB->costo;
				$this->subempresa = $ventaDB->subempresa;
				$this->id = $id;
                
            }	
			private function __construct_by_param(Producto $producto, $cantidad, $monto_total,$subempresa,$id) {
			
                $this->setProducto($producto);
                $this->setCantidad($cantidad);
                $this->setMontoTotal($monto_total);
				$this->setSubempresa($subempresa);
				$this->setId($id);


			}				
			// private function __construct_by_param(Producto $producto, $cantidad, $monto_total,  $costo, $venta) {
                // $this->setProducto($producto);
                // $this->setCantidad($cantidad);
                // $this->setMontoTotal($monto_total);
                // $this->costo($costo);
                // $this->venta($venta);
        
			// }	
			public function add(){
				$this->calcularCostoItem();
				DB::addItemVenta($this);
            }
			public function update(){
				DB::updateItemVenta($this);
            }
			public function delete(){
				
				DB::deleteItemVenta($this);
				$venta = $this->venta;
				if (DB::getCantidadItemsVenta($venta)==0){
					$venta->setActivo(0);
					$venta->save();
				}
            }
			public function calcularCostoItem(){
				if(!$this->costo){
					$this->costo=0;
				}
				//FRAN: Agregamos la condición de que las compras que saldan el stock sean anteriores a la venta.
				$venta = $this->venta;
				$comprasDB = DB::getItemsComprasNoAsignadasOrdenadasPorFechaByProducto($this->producto->getId(), $venta->getFecha());
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
					$fecha = $venta -> getFecha();
					$this->costo = floatval(DB::getCostoMasReciente($this->producto->getId(),$fecha)) * intval($this->cantidad);		
						
					}
				}
			}
			public function reasignarCostoItem(){
					$this->calcularCostoItem();
					DB::updateItemVenta($this);
				
			}
		
			public function getProducto(){
                return $this->producto;
            }
            public function getCantidad(){
                return $this->cantidad;
            }
			public function getSubempresa(){
                return $this->subempresa;
            }
			public function getTotalAbonado(){
                return $this->total_abonado;
            }
			public function getMontoTotal(){
                return $this->monto_total;
            }
			public function getCosto(){
                return $this->costo;
            }
			public function getVenta(){
                return $this->venta;
            }
			public function getId(){
                return $this->id;
            }
			public function setVenta($venta){
		         $this->venta = $venta;
            }
			public function setTotalAbonado($total_abonado){
                $this->total_abonado = $total_abonado;
            }
			public function setProducto(Producto $producto){
                $this->producto = $producto;
            }
            public function setCantidad($cantidad){
                $this->cantidad = $cantidad;
            }
			 public function setSubempresa($subempresa){
                $this->subempresa = $subempresa;
            }
			public function setMontoTotal($montoTotal){
                $this->monto_total = $montoTotal;
            }
			public function setCosto($costo){
                $this->costo = $costo;
            }
			public function setId($id){
			
                $this->id = $id;
            }
	}		