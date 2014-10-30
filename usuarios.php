<?php session_start(); ?>
<?php

ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );
function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';      
    }

$id_permiso = 7;

if(!isset($_SESSION['usuario'])) {
    header('Location: ./login.php');
}

$usuario = $_SESSION['Usuario'];
if(!$usuario->validarPermiso($id_permiso)) {
    header('Location: ./sinpermiso.php');
}

echo PageView::getHeadTag();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<body>

<?php
    echo PageView::getHeaderHTML();
?>

    <div class="colmask leftmenu">
        <div class="colleft">
            <div class="col1">
                <?php
                    $menuSeleccionado = 'usuario';
                    if(isset($_GET['p'])){
                        $indexMenu = $_GET['p'];
                    } else {
                        $indexMenu = 1;
                        $_GET['p'] = $indexMenu;
                    }
                    
                    $parametros = $_GET;

                    echo MenuView::getMenuHTML($menuSeleccionado);

                    echo UsuarioView::getListadoHTML($parametros);

                ?>
            </div>
            <div class="col2">
                <?php
                    
                ?>
                <!-- Column 2 end -->
            </div>
        </div>
    </div>
<?php
    echo PageView::getFooterHTML();
?>

</body>
</html>