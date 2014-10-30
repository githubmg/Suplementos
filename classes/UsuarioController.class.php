<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioController
 *
 * @author ggrin
 */

ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );

function __autoload( $className ){
    require_once $className.'.class.php';
}

class UsuarioController {

    private static $algoritmo = 'SHA256';

    public static function validarUsuario($usuario, $pass) {
        $id_usuario = DB::getIdUsuarioByLogin($usuario);
        if ($id_usuario==FALSE) {
            return false;
        }

        $usuario = new Usuario(intval($id_usuario));
        $hash = UsuarioController::calcularHash($pass);
        $userHash = $usuario->getHash();
        if($userHash==$hash) {
            return true;
        } else {
            return false;
        }
    }

    public static function calcularHash($pass) {
        $context = hash_init(UsuarioController::$algoritmo);
        hash_update($context, $pass);
        return hash_final($context);

    }
    
    public static function verificarUsuarioExiste($usuario) {
        
        $id_usuario = DB::getIdUsuarioByLogin($usuario);
        if($id_usuario) {
            return true;
        } else {
            return false;
        }
    }

    public static function getIdUsuarioByLogin($usuario) {
        
        $id_usuario = DB::getIdUsuarioByLogin($usuario);
        if($id_usuario) {
            return $id_usuario;
        } else {
            return false;
        }
    }
}

session_start();

if (!(isset($_POST['action']))) {
    header('Location: ../login.php');
} else if (isset($_POST['action'])) {
    $accion = $_POST['action'];
    switch($accion) {
        case 'login':
            if (!(isset($_POST['usuario']) || (isset($_POST['pass'])))) {
                header('Location: ../login.php?error');
            } else {
                $usuario = $_POST['usuario'];
                $pass = $_POST['pass'];

                if (UsuarioController::validarUsuario($usuario, $pass)) {
                    $id_usuario = UsuarioController::getIdUsuarioByLogin($usuario);
                    $_SESSION['usuario'] =$usuario;
                    $_SESSION['Usuario'] =new Usuario(intval($id_usuario));
                    
                    header('Location: ../index.php');
                } else {
                    header('Location: ../login.php?error');
                }
            }
            break;
        case 'alta':
            $login = $_POST['login'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $pass = $_POST['pass1'];
            $existe = UsuarioController::verificarUsuarioExiste($login);
            if ($existe == true) {
                header('Location: ../ABMUsuario.php?errorUsuarioExiste&nombre='.$nombre.'&apellido='.$apellido.'&action='.$accion.'&login='.$login);
            } else {
                $hash = UsuarioController::calcularHash($pass);
                $activo = 1;
                $usuario = new Usuario($login, $hash, $nombre, $apellido, $activo);
                $usuario->otorgarRol(1);
                $usuario->save();
                header("Location: ../close.php");  
            }
            break;
        case 'modificacion':

            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $id_usuario = $_POST['id_usuario'];
            $activo = $_POST['activo'];
            $usuario = new Usuario(intval($id_usuario));

            //echo "<br><br>";


            $usuario->setNombre($nombre);
            $usuario->setApellido($apellido);
            
            $usuario->setActivo($activo);
            
            if(isset($_POST['cambiarpass'])) {
                //echo "cambio pass";
                $pass = $_POST['pass1'];
                $hash = UsuarioController::calcularHash($pass);
                $usuario->setHash($hash);
            } else {
                //echo "No cambio pass";
            }

            $usuario->save();
            header("Location: ../close.php");
            
            break;
        case 'baja':
            $id_usuario = $_POST['id_usuario'];
            
            $usuario = new Usuario(intval($id_usuario));
            $usuario->setActivo(0);
            $usuario->save();
            header("Location: ../close.php");
            break;
    }

}
?>

