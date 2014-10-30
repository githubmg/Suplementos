<?php
ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );
session_start();
    /*
        La funci�n __autoload se ejecuta autom�ticamente CADA VEZ que hago
        new de una clase que no est� en memoria
        PHP me va a pasar como par�metro el nombre de la clase que se quiere instanciar
    */
    function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';      
    }

    if(!isset($_SESSION['usuario'])) {
        header('Location: ./login.php');
    }
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">



<?php 
    echo PageView::getHeadTag();
?>




    <body>
        <script type="text/javascript">
            function verificarDatos() {
                
                    if(document.getElementById('action').value=='baja') {
                        document.getElementById('form1').submit();
                        retun
                    } else {
                        if(document.getElementById('pass1').value != document.getElementById('pass2').value) {
                            alert("Los campos Contraseña y Confirmar contraseña no son iguales");
                        } else {
                            if(document.getElementById('login').value!="" && document.getElementById('nombre').value != "" && document.getElementById('apellido').value != "") {
                                document.getElementById('form1').submit();
                            } else {
                                alert ("Por favor complete todos los campos");
                            }

                        }
                    }
                    
                }
            </script>
        <div>
            <form id="form1" method="POST" action="classes/UsuarioController.class.php">
            <div style="overflow: auto; height: 300px">

<?php
    if ($_GET['action']=='alta') {
        echo UsuarioView::getAltaUsuarioHTML();
    } else if ($_GET['action']=='modificacion') {
        echo UsuarioView::getModifUsuarioHTML($_GET['id']);
    } else if ($_GET['action']=='baja') {
        echo UsuarioView::getBajaUsuarioHTML($_GET['id']);
    } else {
        echo "Error: acción no válida<!--";
    }
    echo "<input type=hidden id='action' name= 'action' value='".$_GET['action']."' />";
    if(isset($_GET['errorUsuarioExiste'])) {
        echo "<script type='text/javascript'>alert('El usuario ya existe');
            document.getElementById('nombre').value='".$_GET['nombre']."';
            document.getElementById('apellido').value='".$_GET['apellido']."';
            document.getElementById('login').value='".$_GET['login']."';
            </script>";
    }
?>

            </div>
            <input type="button" name="Grabar" value="Aceptar" class="botonNegro" onClick="verificarDatos()"/>
            <input type="button" name="Cancelar" value="Cancelar" class="botonNegro" onClick="window.close()" />
            </form>
        </div>
    </body>
</html>