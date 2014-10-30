<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cliente
 *
 * @author German Grin
 */
class Cliente {
    private $id_cliente;
    private $persona;
    private $subempresa;
    private $activo;
    
    
    function __construct() {
        $parametros = func_get_args();
        
        if (gettype($parametros[0])=='integer') {
            $this->__construct_by_id($parametros[0]);
        } else if (gettype($parametros[0])=='string') {
            $this->__construct_by_id(intval($parametros[0]));  
        } else if (gettype($parametros[0])=='object' && get_class($parametros[0])=='Persona'){
            $this->__construct_by_param($parametros[0],$parametros[1]);
        } else {
            throw new Exception("No hay un constructor definido para el tipo de par&aacute;metro recibido ".  gettype($parametros[0]));
        }
        
    }
     
    function __construct_by_param(Persona $persona,$subempresa) {
        $this->persona=$persona;
        $this->subempresa=$subempresa;
        $this->activo = 1;
    }
    
    function __construct_by_id($id_cliente) {
        $clienteDB = DB::getClienteById($id_cliente);
        
        if ($clienteDB) {
            $this->id_cliente = $id_cliente;
            $this->persona = new Persona($clienteDB->id_persona);
            $this->subempresa = $clienteDB->subempresa;
            $this->activo = $clienteDB->activo;
        }
    }
    // function getAllClientes() {
        // $clienteDB = DB::getClienteById($id_cliente);
        
        // if ($clienteDB) {
            // $this->id_cliente = $id_cliente;
            // $this->persona = new Persona($clienteDB->id_persona);
        // }
    // }
    public function getId() {
        return $this->id_cliente;
    }
    
    public function getPersona() {
		if ($this->persona){
        return $this->persona;
		}else{
		return new Persona(1);}
	}
    public function getActivo() {
        return $this->activo;
    }
	public function getSubempresa() {
        return $this->subempresa;
    }
	public function setSubempresa($subEmpresa) {
        $this->subempresa = $subEmpresa;
    }
	    
    public function setActivo($activo) {
        $this->activo = $activo;
    }
    
    public function setPersona($persona) {
        $this->persona = $persona;
    }
    public function save() {
        DB::saveCliente($this);
    }
            
  }

?>
