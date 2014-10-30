
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<span>
<?php
function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }
		session_start();
		if (isset($_GET['id'])){
		//Si estoy borrando un elemento
				
				$items = $_SESSION['items'];
				$nuevosItems = array();
				foreach ($items as $item){
					$idItem = $item->getId();
					if ($idItem != $_GET['id']){
						array_push($nuevosItems,$item);
					}
				}
				$_SESSION['items'] = $nuevosItems;
			
		} 
		if(isset($_GET['id_producto'])){

		//Si estoy agregando un producto
			$producto= new Producto ($_GET['id_producto']);
			$monto = $_GET['monto'];
			$cantidad = $_GET['cantidad'];
			$subempresa = $_GET['subempresa'];
			if (isset($_SESSION['items'])){
				$items = $_SESSION['items'];
				$id = obtenerUltimoId($items) +1;
			}else {
				$items = array();
				$id =1;
			}
			$item = new ItemVenta($producto,$cantidad,$monto,$subempresa,$id);
			array_push($items,$item);
			$_SESSION['items'] = $items;
			
		}
		
echo ItemVentaView::GenerarTablaAlta($_SESSION['items']);
function obtenerUltimoId( $items ){
        $maxId = 0;
		foreach ($items as $item){
			$id = $item->getId();
			if ($id > $maxId){
				 $maxId = $id;
			}
		}
		return $maxId;
    }
?>
</span>
