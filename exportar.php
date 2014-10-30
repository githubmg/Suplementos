<?PHP
ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );
session_start();
function __autoload( $className ){
	require_once 'classes/'.$className.'.class.php';      
}

if(!isset($_SESSION['usuario'])) {
        header('Location: ./login.php');
    }
	
$clase = $_GET['categoria'];
if (isset($_GET['fecha_desde'])){
	$fechad = StringController::parsearFecha($_GET['fecha_desde']);
}else{
	$fechad = "";
}
if (isset($_GET['fecha_hasta'])){
	$fechah = StringController::parsearFecha($_GET['fecha_hasta']);
}else{
	$fechah = "";
}
if (isset($_GET['id_cliente'])){
	$idCliente = $_GET['id_cliente'];
}else{
	$idCliente = "";
}
if (isset($_GET['comboProductos'])){
	$idProducto = $_GET['comboProductos'];
}else{
	$idProducto = "";
}
if (isset($_GET['subempresa'])){
	$subempresa = $_GET['subempresa'];
}else{
	$subempresa = "";
}
if (isset($_GET['id_proveedor'])){
	$idProveedor = $_GET['id_proveedor'];
}else{
	$idProveedor = "";
}


 // Obtengo los valores de las cabeceras
 $data = array();
 $titulos = Exportarb::getTitulos($clase);
 array_push($data,$titulos);
  $arrayValores = Exportarb::getArrayValores($clase, $fechad, $fechah, $idCliente, $idProveedor, $idProducto, $subempresa);

 
 // Obtengo los valores del cuerpo
 // Obtengo los valores del cuerpo
 
	foreach ($arrayValores as &$fila) {
		array_push($data,$fila);		
	}
 
 
 //filename for download
 $filename = "reporte_data_".$clase."_".date('Ymd').".xls"; 
 header("Content-Disposition: attachment; filename=\"$filename\"");
 header("Content-Type: application/vnd.ms-excel");
 $flag = true; 
 foreach($data as $row) {
	if(!$flag) {
	//display field/column names as first row 
	echo implode("\t", array_keys($row)) . "\r\n";
	$flag = true;
	} 
	array_walk($row, 'cleanData');
	$final = implode("\t", array_values($row)) . "\r\n";
        $final = mb_convert_encoding($final, 'UTF-16LE', 'UTF-8');
        echo $final;
	} 
	
function cleanData(&$str)	{
	$str = preg_replace("/\t/", "\\t", $str); 
	$str = preg_replace("/\r?\n/", "\\n", $str);
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
} 
	exit; 
	
?>
