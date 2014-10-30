<?php
	class Venta {

            private $id_venta;
            private $cliente;
            private $fecha;
            private $activo;
			private $costo;
			private $total_abonado;
			
            function __construct() {
                $cantidadParametros = func_num_args();
                $parametros = func_get_args();
                if ($cantidadParametros==1) {
                    $id_venta = $parametros[0];
                    $this->__construct_by_id($id_venta);
                } else {
                    $cliente = $parametros[0];
                    $fecha = $parametros[1];
                    $total_abonado = $parametros[2];                
                    $this->__construct_by_param($cliente,$fecha,$total_abonado);
                }
            }

            private function __construct_by_param(Cliente $cliente, $fecha, $total_abonado) {
                $this->setCliente($cliente);
                $this->setFecha($fecha);
                $this->setTotalAbonado($total_abonado);
				$this->activo = 1;
				//$this->costo = $this->calcularCosto();
			}

            private function __construct_by_id($id_venta) {
                $ventaDB = DB::getVentaById($id_venta);

                $this->id_venta = $ventaDB->id_venta;
                $this->cliente = new Cliente(intval($ventaDB->id_cliente));
                $this->fecha = $ventaDB->fecha;
                $this->total_abonado = $ventaDB->total_abonado;
				$this->costo = 0;
				$this->costo = DB::getCostoVenta(intval($ventaDB->id_cliente));

            }

            public function asignarCostoVenta( ){
                return 0;
            }

            public function getCliente(){
			if ($this->cliente){
				return $this->cliente;
			}else{
				return New Cliente(1);
			}
                
            }
       
            public function getFecha(){
                return $this->fecha;
            }
			
			public function getTotalAbonado(){
                return $this->total_abonado;
            }	
          
			public function getId() {
                return $this->id_venta;
            }
            
            public function getActivo() {
                return $this->activo;
            }
            
            public function save() {
                
               $this->id_venta = DB::saveVenta($this);
				
            }

            public function setCliente(Cliente $cliente){
                $this->cliente = $cliente;
            }
           
            public function setFecha($fecha){
                $this->fecha = $fecha;
            }
            
            public function setTotalAbonado($total_abonado){
                $this->total_abonado = $total_abonado;
            }
            public function setActivo($activo){
                $this->activo = $activo;
            }
			public function calcularCosto(){
				return DB::getCostoVenta($this->getId());
			}
						
			
	}
	
?>
