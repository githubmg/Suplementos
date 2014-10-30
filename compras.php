<?php session_start(); ?>




<?php


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

    $id_permiso = 2;
    
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<?php
    echo PageView::getHeaderHTML();
?>

<div class="colmask leftmenu">
	<div class="colleft">
		<div class="col1">
		<?php
		
			$menuSeleccionado = 'compra';
			if(isset($_GET['p'])){
				$indexMenu = $_GET['p'];
			} else {
				$indexMenu = 1;
				$_GET['p'] = 1;
			}

			echo MenuView::getMenuHTML($menuSeleccionado);
			
			$id_proveedor = ((isset($_GET['comboProveedores'])) ? $_GET['comboProveedores'] : '');
				
			$fecha_desde = StringController::parsearFecha(((isset($_GET['fecha_desde'])) ? $_GET['fecha_desde'] : ''));
			$fecha_hasta = StringController::parsearFecha(((isset($_GET['fecha_hasta'])) ? $_GET['fecha_hasta'] : ''));
			$idCliente = ((isset($_GET['comboClientes'])) ? $_GET['comboClientes'] : '');
            $idProducto = ((isset($_GET['comboProductos'])) ? $_GET['comboProductos'] : '');
            $idCompra = ((isset($_GET['id_compra'])) ? $_GET['id_compra'] : '');
			$subempresa = ((isset($_GET['subempresa'])) ? $_GET['subempresa'] : '');

			$idsItemsCompras = DB::getIdsItemsComprasByParam($fecha_desde, $fecha_hasta, $id_proveedor,$subempresa,$idCompra, $idProducto,$indexMenu);
			$cantidad = DB::getCantidadDePaginasICByParam($fecha_desde, $fecha_hasta,$subempresa,$id_proveedor,$idCompra, $idProducto);
			echo CompraView::getListadoHTML($idsItemsCompras,$cantidad, $_GET);
               

                
                
        ?>
		</div>
		<div class="col2">
                <?php
                    echo CompraView::getBuscadorComprasHTML();
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
