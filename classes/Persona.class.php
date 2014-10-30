<?php

include_once 'Domicilio.class.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Persona
 *
 * @author German Grin
 */

class Persona {
    private $id_persona ;
    private $nombre ;
    private $email ;
    private $telefono ;
    private $domicilio ;
    private $observaciones ;
    private $activo ;
    
    function __construct() {
        
        $parametros = func_get_args();
        $cantidadParametros = func_num_args($parametros);

        if ($cantidadParametros == 1 and gettype($parametros[0]=='integer')) {
            $id_persona = $parametros[0];
            $this->__construct_by_id($id_persona);

        } else {

            $nombre = $parametros[0];
            $email = $parametros[1];
            $telefono = $parametros[2];
            $domicilio = $parametros[3];
            $observaciones = $parametros[4];

            $this->__construct_by_param($nombre, $email, $telefono, $domicilio, $observaciones);
            
        }

    }
    
    public function getId() {
        return $this->id_persona;
    }
    
    public function setId($id) {
        $this->id_persona = $id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function getDomicilio() {
        return $this->domicilio;
    }
    
    public function getObservaciones() {
        return $this->observaciones;
    }

    public function getActivo() {
        return $this->activo;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setDomicilio(Domicilio $domicilio) {
        $this->domicilio = $domicilio;
    }

    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
    }

    public function setEmail($email) {
        $this->email  = $email;
    }

    private function __construct_by_id($id_persona) {

        $personaDB = DB::getPersonaById($id_persona);

        if ($personaDB) {
            $this->id_persona = $personaDB->id_persona;
            $this->nombre = $personaDB->nombre;
            $this->email = $personaDB->email;
            $this->telefono = $personaDB->telefono;
            $this->observaciones = $personaDB->Observaciones;
            $this->domicilio = new Domicilio($personaDB->id_domicilio);
            $this->activo = 1;

        } else {
            throw new Exception('No se encontr&oacute; la persona con el id '.$id_persona);
        }
    }

    private function __construct_by_param($nombre, $email, $telefono, Domicilio $domicilio, $observaciones) {

            $this->id_persona = null;
            $this->nombre = $nombre;
            $this->email = $email;
            $this->telefono = $telefono;
            $this->domicilio = $domicilio;
            $this->observaciones = $observaciones;
            $this->activo = 1;
        
    }

    public function save() {
        DB::savePersona($this);
    }

}

?>
