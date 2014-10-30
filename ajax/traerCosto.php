<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" /><span>
    <?php
    

function __autoload( $className )
{
    require_once '../classes/'.$className.'.class.php';
}



$id_producto=$_GET['id_producto'];

$producto = new Producto($id_producto);
$cantidadPendiente = $_GET['cantidad'];

$compras = DB::getItemsComprasNoAsignadasOrdenadasPorFechaByProductoAjax($producto);

$costo = 0;

if ($compras) {

    foreach ($compras as $compra) {
        
        if ($cantidadPendiente == 0) {
            break;
        }
        
        $unidades = $compra['unidades_no_asignadas'];
        $precioUnitario = $compra['precio_unitario'];
        if ($cantidadPendiente >= $unidades) {
            $costo += $unidades*$precioUnitario;
            $cantidadPendiente -= $unidades;
        } else {
            $costo += $cantidadPendiente*$precioUnitario;
            $cantidadPendiente = 0;
        }      
    }
    
    echo $costo;
} else {
    echo "Ha ocurrido un error al estimar el costo";
}


?>
</span>