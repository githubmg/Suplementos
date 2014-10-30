
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<span>
<?php
function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }
	$descripcion = $_GET['descripcion'];
	$observaciones = $_GET['obs'];
	$id_laboratorio = $_GET['id_laboratorio'];
	$tamanio = $_GET['tamanio'];
	$sabor = $_GET['sabor'];
	$producto = new Producto($descripcion, $observaciones, $tamanio, $sabor,$id_laboratorio);

	$producto->save();	

	echo ProductoView::getComboProductosHTML(DB::getMaxIdFromTabla('producto'));

?>
</span>
