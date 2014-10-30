<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Provincia
 *
 * @author German Grin
 */
class Provincia {
    private $descripcion;
    private $id_provincia;
    
    function __construct($id_provincia) {
        $provinciaDB = DB::getProvinciaById($id_provincia);
        
        if ($provinciaDB) {
            $this->descripcion = $provinciaDB->descripcion;
            $this->id_provincia = $provinciaDB->id_provincia;
        } else {
            // ?????
        }
    }
    
    public function getId() {
        return $this->id_provincia;
    }
    
    public function getDescripcion() {
		return $this->descripcion;
		
    }
}

?>
