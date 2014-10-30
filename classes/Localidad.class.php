<?php

require_once 'Provincia.class.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Localidad
 *
 * @author German Grin
 */
class Localidad {
    private $id_localidad;
    private $provincia;
    private $descripcion;
    
    function __construct($id_localidad) {
        $localidadDB = DB::getLocalidadById($id_localidad);
        if ($localidadDB) {
            $this->id_localidad = $id_localidad;
            $this->provincia = new Provincia($localidadDB->id_provincia);
            $this->descripcion = $localidadDB->descripcion;
        } else {
            // ?????
        }
    }
    
    function getProvincia() {
        return $this->provincia;
    }
    
    function getDescripcion() {
        return $this->descripcion;
    }
    
    function getId() {
        return $this->id_localidad;
    }
}

?>
