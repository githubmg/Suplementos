<?php

require_once 'Localidad.class.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Persona
 *
 * @author German Grin
 */
    class Domicilio {
        private $id_domicilio;

        private $localidad;
        private $descripcion;

        function __construct() {

            $parametros = func_get_args();
            $cantidadParametros = func_num_args();

            if ($cantidadParametros == 1 and gettype($parametros[0]=='integer')) {
                $id_domicilio = $parametros[0];
                $this->__construct_by_id($id_domicilio);
            } else {
                $descripcion = $parametros[0];
                $localidad = $parametros[1];

                $this->__construct_by_param($descripcion, $localidad);
            } 
        }

        private function __construct_by_param($descripcion, Localidad $localidad) {

            $this->descripcion = $descripcion;

            $this->localidad = $localidad;
            
        }

        private function __construct_by_id($id_domicilio) {

            $domicilioDB = DB::getDomicilioById($id_domicilio);
            if ($domicilioDB) {
                $this->id_domicilio = $domicilioDB->id_domicilio;
                $this->descripcion = $domicilioDB->descripcion;
                $this->localidad = new Localidad($domicilioDB->id_localidad);
            } else {
                throw new Exception('No se encontr&oacute; el domicilio con el id '.$id_domicilio);
            }
            
        }

        public function getId(){
                return $this->id_domicilio;
        }

        public function getLocalidad(){
                return $this->localidad;
        }
        public function getDescripcion(){
                return $this->descripcion;
        }

        public function save() {
            DB::saveDomicilio($this);
        }
        
        public function setId($id) {
            $this->id_domicilio = $id;
        }

        public function setLocalidad($localidad) {
            $this->localidad = $localidad;
        }
        public function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }
    }

?>
