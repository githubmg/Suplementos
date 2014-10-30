<?php

require_once 'Producto.class.php';
require_once 'Proveedor.class.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** 
 * Description of Compra
 * 
 * @author German Grin
 */
class Compra {
    
    private $id_compra;
    private $proveedor;
    private $fecha;
    private $activo;
	
	
	private $unidades_no_asignadas;
    
    function __construct() {
        
        $parametros = func_get_args();
        $cantidadParametros = func_num_args();
        
        if ($cantidadParametros == 1 && gettype($parametros[0])=='integer') {
            $this->__construct_by_id($parametros[0]);
        } else if ($cantidadParametros==2){
            $this->__construct_by_param($parametros[0], $parametros[1]);
        } else {
            throw new Exception("No hay un constructor definido para el tipo de par&aacute;metro recibido ".  gettype($parametros[0]));
        }
    }
    
    function __construct_by_param(Proveedor $proveedor, $fecha) {
        $this->id_compra = NULL;
        $this->proveedor = $proveedor;
        $this->fecha = $fecha;
        $this->activo = 1;
    }
    
    function __construct_by_id($id_compra) {
        $compraDB = DB::getCompraById($id_compra);
        
        if ($compraDB) {
            $this->id_compra = $compraDB->id_compra;
            $this->proveedor = new Proveedor(intval($compraDB->id_proveedor));
            $this->fecha = $compraDB->fecha;
            $this->activo = $compraDB->activo;
        }
    }


    public function getId(){
        return $this->id_compra;
    }
    public function getProveedor(){
        return $this->proveedor;
    }
    public function getFecha(){
        return $this->fecha;
    }
    public function getActivo(){
        return $this->activo;
    }
    public function getTotal() {
        return DB::getTotalCompraById($this->id_compra);
    }
    public function save() {
        DB::saveCompra($this);
        $this->id_compra = DB::getMaxIdFromTabla("compra");
    }
    public function setProveedor(Proveedor $proveedor) {
        $this->proveedor = $proveedor;
    }
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }
    public function setActivo($activo) {
        $this->activo = $activo;
    }
}
?>
