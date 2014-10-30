<?php
	class ItemCompra	{
		private $compra;
		private $producto;
		private $cantidad;
		private $monto_total;
		private $unidades_no_asignadas;
		private $subempresa;		
		private $id;
                private $precio_unitario;
		
		function __construct() {
			$cantidadParametros = func_num_args();
			$parametros = func_get_args();
			if ($cantidadParametros==1) {
				$id_item_compra = $parametros[0];
				$this->__construct_by_id($id_item_compra);
			} 
			else if ($cantidadParametros==5) {
		
				$producto = $parametros[0];
				$cantidad = $parametros[1];
				$monto_total = $parametros[2];
				$subempresa = $parametros[3];
				$id = $parametros[4];
				$this->__construct_by_param($producto,$cantidad,$monto_total,$subempresa,$id,0);
			} 
                        else if ($cantidadParametros==6) {
		
				$producto = $parametros[0];
				$cantidad = $parametros[1];
				$monto_total = $parametros[2];
				$subempresa = $parametros[3];
				$id = $parametros[4];
				$precio_unitario = $parametros[5];
				$this->__construct_by_param($producto,$cantidad,$monto_total,$subempresa,$id, $precio_unitario);
			}
		}
					
		private function __construct_by_id($id) {
			$item = DB::getItemCompraById($id);

			$this->compra = new Compra(intval($item->id_compra));
			$this->producto = new Producto($item->id_producto);
			$this->cantidad = $item->cantidad;
			$this->monto_total = $item->monto_total;
			$this->subempresa = $item->subempresa;
			$this->unidades_no_asignadas = $item->unidades_no_asignadas;
                        $this->precio_unitario = $item->precio_unitario;
                        $this->setId($id);
			
		}	
		public function delete(){
				
				DB::deleteItemCompra($this);
				$compra = $this->compra;
				if (DB::getCantidadItemsCompra($compra)==0){
					$compra->setActivo(0);
					$compra->save();
				}
            }
		
		private function __construct_by_param(Producto $producto, $cantidad, $monto_total,$subempresa,$id, $precio_unitario) {
		
			$this->setProducto($producto);
			$this->setCantidad($cantidad);
			$this->setMontoTotal($monto_total);
			$this->setUnidadesNoAsignadas($cantidad); //Al crearse la compra ninguna de las undiades está asignada.
			$this->setSubempresa($subempresa);			
			$this->setPrecioUnitario($precio_unitario);
			$this->setId($id);
		}				
		
		public function getProducto(){
			return $this->producto;
		}
		public function getSubempresa(){
			return $this->subempresa;
		}
		public function getCantidad(){
			return $this->cantidad;
		}
		public function getMontoTotal(){
			return $this->monto_total;
		}
		public function getUnidadesNoAsignadas(){
			return $this->unidades_no_asignadas;
		}
		public function getCompra(){
			return $this->compra;
		}
		public function getId(){
			return $this->id;
		}
		public function getPrecioUnitario(){
				return $this->precio_unitario;
		}
                
		public function setCompra($compra){
			 $this->compra = $compra;
		}
		public function setSubempresa($subempresa){
			 $this->subempresa = $subempresa;
		}
		public function setProducto(Producto $producto){
			$this->producto = $producto;
		}
		public function setCantidad($cantidad){
			$this->cantidad = $cantidad;
		}
		public function setMontoTotal($montoTotal){
			$this->monto_total = $montoTotal;
		}
		public function setUnidadesNoAsignadas($unidades_no_asignadas){
			$this->unidades_no_asignadas = $unidades_no_asignadas;
		}
		public function setId($id){
			$this->id = $id;
		}
		public function setPrecioUnitario($precio_unitario){
			$this->precio_unitario = $precio_unitario;
		}
		public function add() {
			DB::addItemCompra($this);
		}
		public function update() {
			DB::updateItemCompra($this);
		}
                
	}		