<?php
session_start();
ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );
ini_set('max_execution_time', 300); 

    /*
        La funci&oacute;n __autoload se ejecuta autom&aacute;ticamente CADA VEZ que hago
        new de una clase que no est&aacute; en memoria
        PHP me va a pasar como par&aacute;metro el nombre de la clase que se quiere instanciar
    */
    function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';      
    }

    // $id_permiso = 8;

    // if(!isset($_SESSION['usuario'])) {
        // header('Location: ./login.php');
    // }

    // $usuario = $_SESSION['Usuario'];
    // if(!$usuario->validarPermiso($id_permiso)) {
        // header('Location: ./sinpermiso.php');
    // }
    echo PageView::getHeadTag();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<body class="bodyComisiones">

<?php			
    echo PageView::getHeaderHTML();
?>

<div class="colmask leftmenu">
	<div class="colleft">
		<div class="col1">
		<?php
			
			$menuSeleccionado = 'comision';
            if(isset($_GET['p'])) {
				$indexMenu = $_GET['p'];
			} else {
				$indexMenu = 1;
				$_GET['p'] = $indexMenu;
			}
				
			// $fecha_desde = ((isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : '');
			// $fecha_hasta = ((isset($_GET['fecha_hasta'])) ? $_GET['fecha_hasta'] : '');
			// $idCliente = ((isset($_GET['comboClientes'])) ? $_GET['comboClientes'] : '');
			// $subempresa = ((isset($_GET['subempresa'])) ? $_GET['subempresa'] : '');
			// $dated = StringController::parsearFecha($fecha_desde);
			// $dateh = StringController::parsearFecha($fecha_hasta);
				
			$idsComisiones = DB::getIdsComisionesByParam('','','', $indexMenu);
			$cantidad = DB::getCantidadDePaginasComisionByParam('', '', '');
			echo MenuView::getMenuHTML($menuSeleccionado);
			echo ComisionView::getListadoHTML($idsComisiones,$cantidad, $_GET);
			
		?>
		</div>
		<div class="col2">
                <?php
                   // echo VentaView::getBuscadorHTML();
                ?>
                  <!-- Column 2 end -->
		</div>
	</div>
</div>
<?php
	//echo PageView::getActualizarStockHTML();
    echo PageView::getFooterHTML();
?>

</body>
</html>
