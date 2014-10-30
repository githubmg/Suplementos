<?php session_start(); ?>
<?php
ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );


if (isset($_POST['addCliente']) or isset($_POST['addProducto']) or isset($_POST['addProveedor']) or isset($_POST['addLab']) ){
    header("Location: ../edicion.php".getParametros());     
} else {
    header("Location: ../close.php");     
}
 
function __autoload( $className ){
    require_once $className.'.class.php';      
}

function getParametros(){
    $params = "?action=".$_GET['action']."&categoria=".$_GET['categoria'];
    if ( isset($_GET['id'])) {
        $params .= "&id=".$_GET['id'];
    }  
    return $params;
}

function alta($categoria) {
    switch ($categoria) {
        
        case 'venta':
            $id_cliente = $_POST['comboClientes'];
            $total_abonado = $_POST['monto_abonado'];
            $fecha = StringController::parsearFecha($_POST['fecha_venta']);
            $cliente = new Cliente($id_cliente);
			
            $venta = new Venta($cliente, $fecha, $total_abonado);
            $venta->save();
			
			if (isset($_SESSION['items'])){
				$items = $_SESSION['items'];
				foreach($items as $item){
					$item->setVenta($venta);
					$item->add();
				}
			}
            break;
        
        case 'compra':
            $id_proveedor = $_POST['comboProveedores'];	
            
            $fecha = StringController::parsearFecha($_POST['fecha_venta']); //en CompraView el campo está como fecha_venta, no es error
            $proveedor = new Proveedor(intval($id_proveedor));
            
            $compra = new Compra($proveedor, $fecha);
            $compra->save();
            session_start();
            if (isset($_SESSION['items'])){
                    $items = $_SESSION['items'];
                    foreach($items as $item){
                            $item->setCompra($compra);
                            $item->add();
                    }
            }
            
            break;
        
        case 'cliente':
            $nombre = $_POST["nombre"];
			if (isset($_POST["SM"])){
				$subempresa = $_POST["SM"];
			}else{
				$subempresa = "MM";
			}
			
            $txtDomicilio = $_POST["domicilio"];
            $telefono = $_POST["telefono"];
            $observaciones = $_POST["observaciones"];
            $email = $_POST["email"];
            //HAY QUE PREPARAR EL COMBO DE LOCALIDAD
            //$id_localidad = $_POST["localidad"];
            
            $localidad = new Localidad($_POST["comboLocalidades"]);
            
            $domicilio = new Domicilio($txtDomicilio, $localidad);
            $persona = new Persona($nombre, $email, $telefono, $domicilio, $observaciones);
            $cliente = new Cliente($persona,$subempresa);
            
            $cliente->save();
            
            break;
        
        case 'proveedor':
            $nombre = $_POST["nombre"];
            $txtDomicilio = $_POST["domicilio"];
            $telefono = $_POST["telefono"];
            $observaciones = $_POST["observaciones"];
            $email = $_POST["email"];
            //HAY QUE PREPARAR EL COMBO DE LOCALIDAD
            //$id_localidad = $_POST["localidad"];
            
            $localidad = new Localidad($_POST["comboLocalidades"]);
            
            $domicilio = new Domicilio($txtDomicilio, $localidad);
            $persona = new Persona($nombre, $email, $telefono, $domicilio, $observaciones);
            $proveedor = new Proveedor($persona);
            
            $proveedor->save();
            
            break;
        
        case 'producto':
            $descripcion = $_POST['descripcion'];
            $observaciones = $_POST['observaciones'];
			$id_laboratorio = $_POST['comboLaboratorios'];
			$tamanio = $_POST['tamanio'];
		
			$sabor = $_POST['sabor'];
			$producto = new Producto($descripcion, $observaciones, $tamanio, $sabor, intval($id_laboratorio));

            $producto->save();
            
            break;
			
		case 'comision':
			
			$producto = $_POST['producto'];
			$nro_venta = $_POST['nroVenta'];
			$importe = $_POST['importe'];
			$observaciones = $_POST['observaciones'];
			$id_cliente = $_POST['comboClientes'];
            $cliente = new Cliente($id_cliente);
			$fecha = StringController::parsearFecha($_POST['fecha_comision']);
			$comision = new Comision($cliente, $fecha, $producto, $nro_venta, $importe, $observaciones);
			$comision->setActivo(1);
            $comision->add();
            
            break;
			
		case 'laboratorio':
            $descripcion = $_POST['laboratorio'];
            $lab = new Laboratorio($descripcion,1);
            $lab->save();
               
            
            break;
        
    }
}

function modificacion($categoria, $id) {

        switch ($categoria) {
            case 'venta':

              
                $id_producto = $_POST['comboProductos'];
                $cantidad = $_POST['cantidad'];
                $monto_total = $_POST['monto'];
                $costo = $_POST['costo'];
                $id_cliente = $_POST['comboClientes'];
                $fecha = StringController::parsearFecha($_POST['fecha_venta']);
                $monto_abonado = $_POST['monto_abonado'];
				$subempresa = 'MM';
				if(isset($_POST['subempresaForm'])){
					if ($_POST['subempresaForm'] == 'SM'){
						$subempresa = 'SM';
					}
				}
                $cliente = new Cliente($id_cliente);
                $producto = new Producto($id_producto);
                $itemVenta = new ItemVenta($id);
                $venta =  $itemVenta->getVenta();
                $venta->setActivo(1);
                $venta->setCliente($cliente);
                $venta->setTotalAbonado($monto_abonado);
                $venta->setFecha($fecha);
                $itemVenta->setCantidad($cantidad);
                $itemVenta->setProducto($producto);
                $itemVenta->setMontoTotal($monto_total);
                $itemVenta->setCosto($costo);
				$itemVenta->setSubempresa($subempresa);
                $venta->save();
                $itemVenta->update();

                break;
            case 'compra':

                $item = new ItemCompra($id);
                $id_producto = $_POST['comboProductos'];
                $cantidad = $_POST['cantidad'];
                $monto_total = $_POST['monto'];
                $id_proveedor = $_POST['comboProveedores'];
                $fecha = StringController::parsearFecha($_POST['fecha_venta']);
		$precio_unitario = $_POST['precio'];
                $proveedor = new Proveedor(intval($id_proveedor));
                $producto = new Producto(intval($id_producto));
				$subempresa = 'MM';
				if(isset($_POST['subempresaForm'])){
					if ($_POST['subempresaForm'] == 'SM'){
						$subempresa = 'SM';
					}
				}
                $compra =  $item->getCompra();
                $compra->setActivo(1);
                $compra->setProveedor($proveedor);
                
                $compra->setFecha($fecha);
                $item->setCantidad($cantidad);
                $item->setProducto($producto);
                $item->setMontoTotal($monto_total);
                $item->setPrecioUnitario($precio_unitario);
                $item->setSubempresa($subempresa);
                $compra->save();
                $item->update();
                

                break;
            case 'cliente':

                $nombre = $_POST["nombre"];
                $txtDomicilio = $_POST["domicilio"];
                $telefono = $_POST["telefono"];
                $observaciones = $_POST["observaciones"];
                $email = $_POST["email"];
				if (isset($_POST["SM"])){
					$subEmpresa = $_POST["SM"];
				}else{
					$subEmpresa = "MM";
				}
                //HAY QUE PREPARAR EL COMBO DE LOCALIDAD
                //$id_localidad = $_POST["localidad"];

                $localidad = new Localidad($_POST["comboLocalidades"]);

                $cliente = new Cliente($id);
                $persona = $cliente->getPersona();

                $domicilio = $persona->getDomicilio();
                $domicilio->setDescripcion($txtDomicilio);
                $domicilio->setLocalidad($localidad);

                $persona->setNombre($nombre);
                $persona->setEmail($email);
                $persona->setTelefono($telefono);
                $persona->setObservaciones($observaciones);

                $persona->setDomicilio($domicilio);
                $cliente->setPersona($persona);
                $cliente->setSubempresa($subEmpresa);

                $cliente->save();

                break;
            case 'proveedor':

                $nombre = $_POST["nombre"];
                $txtDomicilio = $_POST["domicilio"];
                $telefono = $_POST["telefono"];
                $observaciones = $_POST["observaciones"];
                $email = $_POST["email"];
                //HAY QUE PREPARAR EL COMBO DE LOCALIDAD
                //$id_localidad = $_POST["localidad"];

                $localidad = new Localidad($_POST["comboLocalidades"]);

                $proveedor = new Proveedor(intval($id));
                $persona = $proveedor->getPersona();

                $domicilio = $persona->getDomicilio();
                $domicilio->setDescripcion($txtDomicilio);
                $domicilio->setLocalidad($localidad);

                $persona->setNombre($nombre);
                $persona->setEmail($email);
                $persona->setTelefono($telefono);
                $persona->setObservaciones($observaciones);

                $persona->setDomicilio($domicilio);
                $proveedor->setPersona($persona);

                $proveedor->save();

                break;
            case 'producto':

                $descripcion = $_POST['descripcion'];
                $observaciones = $_POST['observaciones'];
                $tamanio = $_POST['tamanio'];
                $sabor = $_POST['sabor'];
                $id_laboratorio = $_POST['comboLaboratorios'];
                $producto = new Producto($id);
                $producto->setObservaciones($observaciones);
                $producto->setDescripcion($descripcion);
                $producto->setSabor($sabor);
                $producto->setTamanio($tamanio);
                $producto->setLaboratorio(new Laboratorio($id_laboratorio));
                $producto->setActivo(1);
                $producto->save();
                break;
			case 'comision':
				$id = $_POST['id'];
				$producto = $_POST['producto'];
				$nro_venta = $_POST['nroVenta'];
				$importe = $_POST['importe'];
				$observaciones = $_POST['observaciones'];
				$id_cliente = $_POST['comboClientes'];
				$cliente = new Cliente($id_cliente);
				$comision = new Comision($id);
				$comision->setProducto($producto);
				$comision->setNroVenta($nro_venta);
				$comision->setImporte($importe);
				$comision->setObservaciones($observaciones);
				$comision->setCliente($cliente);
				$comision->update();
        }
        
        
}

function baja($categoria, $id) {

        switch ($categoria) {
            case 'venta':
                $item = new ItemVenta($id);
                $item->delete();
				break;
            
            case 'compra':
                $item = new ItemCompra($id);
                $item->delete();
				break;
            case 'cliente':
                $cliente = new Cliente($id);
                print_r($cliente);
                $cliente->setActivo(0);
          

                $cliente->save();

                break;
            case 'proveedor':
                $proveedor = new Proveedor(intval($id));
                $proveedor->setActivo(0);
                $proveedor->save();
                break;
            case 'producto':
                $producto = new Producto($id);
                $producto->setActivo(0);
                $producto->save();
                break;
			case 'comision':
                $comision = new Comision($id);
                $comision->setActivo(0);
                $comision->update();
                break;	
        }
}

echo "<html><head>";
if (isset ($_POST['addCliente'])){
     alta('cliente');
 } else if (isset ($_POST['addProducto'])) {
     alta('producto');
 } else if (isset ($_POST['addProveedor'])) {
     alta('proveedor');
} else if (isset ($_POST['addLab'])) {
     alta('laboratorio');	 
 } else {
    if (isset($_POST['action']) && isset($_POST['categoria'])){
        $accion = $_POST['action'];
        $categoria = $_POST['categoria'];
    } else {
        echo "Error: no hay acci&oacute;n o tipo de objeto";
    }

    if ($accion == 'm') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            modificacion($categoria, $id); 
        } else {
            echo "Error: no hay id de elemento";
        }
    } else if ($accion == 'a') {
        echo alta($categoria);
    } else if ($accion == 'b') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            baja($categoria, $id);
        } else {
            echo "Error: no hay id de elemento";
        }
    } else {
        echo "Error: acci&oacute;n no definida";
    }
    echo "</head></html>";
    }
?>