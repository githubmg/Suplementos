<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Laboratorio
 *
 * @author German Grin
 */
class Laboratorio {

    private $id_laboratorio;
    private $descripcion;
	private $activo;
     
    function __construct() {
        $parametros = func_get_args();
        $cantidadParametros = func_num_args();
        
        if ($cantidadParametros == 1 and gettype($parametros[0]=='integer')) {
            $id_laboratorio = $parametros[0];
            $this->__construct_by_id($id_laboratorio);
        } else {
            $descripcion=$parametros[0];
            $this->__construct_by_param($descripcion);
        }
            
    }
    
    function __construct_by_param($descripcion) {
        $this->descripcion = $descripcion;
        $this->activo = 1;
    }
    
    function __construct_by_id($id_laboratorio) {
        $laboratorioDB = DB::getLaboratorioById($id_laboratorio);
        
        if ($laboratorioDB) {
            $this->id_laboratorio = $laboratorioDB->id_laboratorio;
            $this->descripcion = $laboratorioDB->descripcion;
                              
        }
    }
    
    public function getId() {
        return $this->id_laboratorio;
    }
    
     
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function getActivo() {
        return $this->activo;
    }
   
    
    public function save() {
        // Hay que ver si esta l&oacute;gica de si existe o es nuevo no vale la pena mandarla a DB
        try {
            DB::saveLaboratorio($this);
        } catch (Exception $exc) {
            echo "Hubo un error en saveLaboratorio: ".$exc->getTraceAsString()." /* ************* */ 
                ".$exc->getMessage() ;
        }


        
        
    }

}

?>
