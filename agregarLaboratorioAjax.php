
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<span>
<?php
function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }
	$descripcion = $_GET['laboratorio'];

	$lab = new Laboratorio($descripcion,1);
    $lab->save();
               	

	echo LaboratorioView::getComboLaboratorioHTML(DB::getMaxIdFromTabla('laboratorio'));

?>
</span>
