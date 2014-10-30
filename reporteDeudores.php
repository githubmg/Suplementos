<?php session_start(); ?>
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1 );

function __autoload( $className ){
    require_once 'classes/'.$className.'.class.php';
}

$id_permiso = 6;

if(!isset($_SESSION['usuario'])) {
    header('Location: ./login.php');
}

$usuario = $_SESSION['Usuario'];
if(!$usuario->validarPermiso($id_permiso)) {
    header('Location: ./sinpermiso.php');
}

echo PageView::getHeadTag();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<body>

<?php
    echo PageView::getHeaderHTML();
?>

<div class="colmask leftmenu">
	<div class="colleft">
		<div class="col1">
		<?php
			$menuSeleccionado = 'reporte';
			 if(isset($_GET['p'])){
				$indexMenu = $_GET['p'];
			} else {
				$indexMenu = 1;
				$_GET['p'] = $indexMenu;
			}
                        
                        /*
			$fecha_desde = StringController::parsearFecha(((isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : ''));
			$fecha_hasta = StringController::parsearFecha(((isset($_GET['fecha_hasta'])) ? $_GET['fecha_hasta'] : ''));
			$idProducto = ((isset($_GET['comboProveedores'])) ? $_GET['comboProveedores'] : '');
			$subempresa = ((isset($_GET['subempresa'])) ? $_GET['subempresa'] : '');
                        */

			echo MenuView::getMenuHTML($menuSeleccionado);
			echo ReporteDeudores::getListadoHTML();

		?>
		</div>
		<div class="col2">
                <?php
                    //echo VentaView::getBuscadorHTML();
                    //echo ReporteGanancias::getBuscadorHTML();
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
