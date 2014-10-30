<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Proveedor
 *
 * @author German Grin
 */
class Proveedor {
    private $id_proveedor;
    private $persona;
    private $activo;
    
    function __construct() {
        $parametros = func_get_args();
        if (gettype($parametros[0])=='integer') {
             $this->__construct_by_id($parametros[0]);
        } else if (gettype($parametros[0])=='object' && get_class($parametros[0])=='Persona'){
            $this->__construct_by_param($parametros[0]);
        } else {
            throw new Exception("No hay un constructor definido para el tipo de par&aacute;metro recibido ".  gettype($parametros[0]));
        }
        
    }
    
    private function __construct_by_param(Persona $persona) {
        $this->persona=$persona;
        $this->activo = 1;
    }
    
    private function __construct_by_id($id_proveedor) {
        $proveedorDB = DB::getProveedorById($id_proveedor);
        if ($proveedorDB) {
            $this->id_proveedor = $id_proveedor;
            $this->persona = new Persona($proveedorDB->id_persona);
            $this->activo = $proveedorDB->activo;
        }
    }
    
    public function getId() {
        return $this->id_proveedor;
    }
    
    public function getPersona() {
        return $this->persona;
    }
    
    public function getActivo() {
        return $this->activo;
    }
    
    public function setActivo($activo) {
        $this->activo = $activo;
    }
    
    public function setPersona($persona) {
        $this->persona = $persona;
    }
    public function save() {
        DB::saveProveedor($this);
    }
            
}

?>
