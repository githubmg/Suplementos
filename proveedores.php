<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">

<?php
session_start();
ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );

    /*
        La funci&oacute;n __autoload se ejecuta autom&aacute;ticamente CADA VEZ que hago
        new de una clase que no est&aacute; en memoria
        PHP me va a pasar como par&aacute;metro el nombre de la clase que se quiere instanciar
    */
    function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }

    $id_permiso = 4;

    if(!isset($_SESSION['usuario'])) {
        header('Location: ./login.php');
    }

    $usuario = $_SESSION['Usuario'];
    if(!$usuario->validarPermiso($id_permiso)) {
        header('Location: ./sinpermiso.php');
    }
    
    echo PageView::getHeadTag();
?>

<body>

<?php
    echo PageView::getHeaderHTML();
?>
<div class="colmask leftmenu">
	<div class="colleft">
		<div class="col1">
		<?php
		
                $menuSeleccionado = 'proveedor';
                
		 if(isset($_GET['p'])){
			$indexMenu = $_GET['p'];
		} else {
			$indexMenu = 1;
		}

                if (isset($_GET['comboProveedores'])) {
                    // Tuve que armar un doble array porque la función DB::getIdsClientes devuelve
                    // un array de arrays con los ids. Entonces, hago lo mismo para poder tratarlos
                    // igual

                    $listadoIds=array(array('id_proveedor' => $_GET['comboProveedores']));
                    $cantidadPaginas = 1;

                } else {
                    $listadoIds = DB::getIdsProveedor($indexMenu);
                    $cantidadPaginas = DB::getCantidadDePaginas('proveedor');
                }
                
                echo MenuView::getMenuHTML($menuSeleccionado);
                echo ProveedorView::getListadoHTML($listadoIds,$indexMenu,$cantidadPaginas);
        ?>
		</div>
		<div class="col2">
                   <?php
                        echo ProveedorView::getBuscadorHTML();
                    ?>
		</div>
	</div>
</div>
<?php
    echo PageView::getFooterHTML();
?>
</body>
</html>
