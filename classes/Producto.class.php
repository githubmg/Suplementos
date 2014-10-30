<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Producto
 *
 * @author German Grin
 */
class Producto {

    private $id_producto;
    private $descripcion;
    private $observaciones;
    private $activo;
    private $tamanio;
    private $sabor;
    private $laboratorio;
    private $stock_disponible;
	private $stock_sm;
    
    function __construct() {
        $parametros = func_get_args();
        $cantidadParametros = func_num_args();

        if ($cantidadParametros == 1 and gettype($parametros[0]=='integer')) {
            $id_producto = $parametros[0];
            $this->__construct_by_id($id_producto);
        } else {
            $descripcion=$parametros[0];
            $observaciones=$parametros[1];
            $tamanio=$parametros[2];
            $sabor=$parametros[3];
            $id_laboratorio=$parametros[4]; 
            $this->__construct_by_param($descripcion, $observaciones,$tamanio,$sabor,$id_laboratorio);
        }
            
    }
    
    function __construct_by_param($descripcion, $observaciones,$tamanio,$sabor,$id_laboratorio) {
        $this->descripcion = $descripcion;
        $this->observaciones = $observaciones;
		$this->tamanio = $tamanio;
		$this->sabor = $sabor;
		$this->laboratorio = new Laboratorio($id_laboratorio);
        $this->activo = 1;
    }
    
    function __construct_by_id($id_producto) {
        $productoDB = DB::getProductoById($id_producto);
        
        if ($productoDB) {
            $this->id_producto = $productoDB->id_producto;
            $this->descripcion = $productoDB->descripcion;
            $this->observaciones = $productoDB->observaciones;
			$this->tamanio = $productoDB->tamanio;
			$this->sabor = $productoDB->sabor;
            $this->activo = $productoDB->sabor;
			$this->laboratorio = new Laboratorio($productoDB->id_laboratorio);
            $this-> stock_disponible= DB::getStockProducto($id_producto);
			$this-> stock_sm= DB::getStockProductoSM($id_producto);
                    
        }
    }
    
    public function getId() {
        return $this->id_producto;
    }
    
    public function getObservaciones() {
        return $this->observaciones;
    }
   
    public function getDescripcion() {
        return $this->descripcion;
    }
	public function getDescripcionCompleta() {
		$nombre = $this->descripcion;
		$tamanio = $this->tamanio;
		$sabor = $this->sabor;
		$laboratorio = $this->laboratorio;
        return $this->descripcion;
    }
    public function getActivo() {
        return $this->activo;
    }
    public function getStockDisponible(){
        return $this->stock_disponible;
    }
	public function getStockSM(){
        return $this->stock_sm;
    }
	public function getSabor(){
        return $this->sabor;
    }
	public function getTamanio(){
        return $this->tamanio;
    }
	
	public function getLaboratorio() {
        return $this->laboratorio;
    }
    
    public function setLaboratorio(Laboratorio $laboratorio) {
        $this->laboratorio = $laboratorio;
    
    }
    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
    }
	
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
	public function setSabor($sabor) {
        $this->sabor = $sabor;
    }
	public function setTamanio($tamanio) {
        $this->tamanio = $tamanio;
    }
	public function setActivo($activo) {
        $this->activo = $activo;
    }
    
    public function save() {
        // Hay que ver si esta l&oacute;gica de si existe o es nuevo no vale la pena mandarla a DB
        try {
		
            DB::saveProducto($this);
        } catch (Exception $exc) {
            echo "Hubo un error en saveProducto: ".$exc->getTraceAsString()." /* ************* */ 
                ".$exc->getMessage() ;
        }


        
        
    }

}

?>
