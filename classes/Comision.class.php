<?php
	class Comision {
		private $id;
		private $cliente;
		private $fecha;
		private $producto;
		private $nro_venta;
		private $importe;
		private $observaciones;
		private $activo;
		
		function __construct() {
						$cantidadParametros = func_num_args();
						$parametros = func_get_args();
						if ($cantidadParametros==1) {
							$id = $parametros[0];
							$this->__construct_by_id($id);
						} 
						else if ($cantidadParametros==6) {
					
							$cliente = $parametros[0];
							$fecha = $parametros[1];
							$producto = $parametros[2];
							$nro_venta= $parametros[3];
							$importe= $parametros[4];
							$observaciones= $parametros[5];
							$this->__construct_by_param($cliente,$fecha,$producto,$nro_venta,$importe,$observaciones);
						} 
					}
					
			 private function __construct_by_id($id) {
                $comisionDB = DB::getComisionById($id);
				$this->id = $comisionDB->id;
                $this->cliente = new Cliente(intval($comisionDB->id_cliente));
                $this->fecha = $comisionDB->fecha;
                $this->producto = $comisionDB->producto;
				$this->nro_venta= $comisionDB->nro_venta;
				$this->importe = $comisionDB->importe;
				$this->observaciones = $comisionDB->observaciones;
				$this->activo = $comisionDB->activo;
                
            }	
			private function __construct_by_param(Cliente $cliente,$fecha,$producto,$nro_venta,$importe,$observaciones) {
			
                $this->setCliente($cliente);
                $this->setFecha($fecha);
                $this->setProducto($producto);
				$this->setNroVenta($nro_venta);
				$this->setImporte($importe);
				$this->setObservaciones($observaciones);
				$this->setActivo(1);


			}				
			public function add(){
				print_r($this);
				DB::addComision($this);
            }
			public function update(){
				DB::updateComision($this);
            }
			public function delete(){
				DB::deleteComision($this);
			}
			
			public function getProducto(){
                return $this->producto;
            }
            public function getCliente(){
                return $this->cliente;
            }
			public function getFecha(){
                return $this->fecha;
            }
			public function getNroVenta(){
                return $this->nro_venta;
            }
			public function getImporte(){
                return $this->importe;
            }
			public function getObservaciones(){
                return $this->observaciones;
            }
			public function getId(){
                return $this->id;
            }
			public function getActivo(){
                return $this->activo;
            }
			public function setProducto($producto){
		         $this->producto = $producto;
            }
			public function setCliente(Cliente $cliente){
                $this->cliente = $cliente;
            }
			public function setFecha($fecha){
                $this->fecha = $fecha;
            }
            public function setNroVenta($nro_venta){
                $this->nro_venta = $nro_venta;
            }
			public function setImporte($importe){
                $this->importe = $importe;
            }
			public function setActivo($activo){
                $this->activo = $activo;
            }
			public function setObservaciones($observaciones){
                $this->observaciones = $observaciones;
            }
			public function setId($id){
			
                $this->id = $id;
            }
	}		