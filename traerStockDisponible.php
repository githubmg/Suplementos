
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<span><?php
function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }

$id=$_GET['id_producto'];
echo DB::getStockProducto(intval($id));
?></span>
