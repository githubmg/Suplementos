<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author german
 */
class Usuario {
    
    private $id_usuario;
    private $login;
    private $hash ;
    private $nombre; 
    private $apellido ;
    private $activo;
    private $roles;
    
    function __construct() {
        $cantidadDeParametros = func_num_args();
        $parametros =  func_get_args();
        if ($cantidadDeParametros == 1 and gettype($parametros[0])=='integer') {
            $id_usuario = $parametros[0];
            $this->__construct_by_id($id_usuario);
        } else {
            $login=$parametros[0];
            $hash=$parametros[1];
            $nombre=$parametros[2];
            $apellido=$parametros[3];
            $activo=$parametros[4];
            $this->__construct_by_param($login, $hash, $nombre, $apellido, $activo);
        }
    }
    
    function __construct_by_id($id) {
        $usuario = DB::getUsuarioById($id);
        $this->id_usuario = $usuario->id_usuario;
        $this->setLogin($usuario->login);
        $this->setApellido($usuario->apellido);
        $this->setNombre($usuario->nombre);
        $this->setHash($usuario->hash);
        $this->setActivo($usuario->activo);
        $this->roles = array();
        $roles = DB::getRolesByIdUsuario($id);
        foreach ($roles as $rol) {
            $this->otorgarRol($rol['id_rol']);
        }

    }
    
    function __construct_by_param($login, $hash, $nombre, $apellido, $activo) {
        $this->id_usuario = NULL;
        $this->setLogin($login);
        $this->setApellido($apellido);
        $this->setNombre($nombre);
        $this->setHash($hash);
        $this->setActivo($activo);
        
    }
    
    private function setLogin($login) {
        $this->login = $login;
        
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }
    
    public function setHash($hash) {
        $this->hash = $hash;
    }
    
    public function setActivo($activo) {
        $this->activo = $activo;
    }
    
    public function getLogin() {
        return $this->login;
        
    }
    
    public function getId() {
        return $this->id_usuario;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getApellido() {
        return $this->apellido;
    }
    
    public function getHash() {
        return $this->hash;
    }
    
    public function getActivo() {
        return $this->activo;
    }
    
    public function save() {
        DB::saveUsuario($this);
    }

    public function otorgarRol($id_rol) {
        array_push($this->roles, $id_rol);
    }

    public function quitarRol($id_rol) {

    }

    public function getRoles() {
        return $this->roles;
    }

    public function validarPermiso($id_permiso) {
        $id_usuario = $this->getId();
       
        $resultado = DB::validarPermisoByIdUsuario($id_usuario, $id_permiso);
        return $resultado;
    }
    
}

?>
