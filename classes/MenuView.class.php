<?php
	
	class MenuView {
		

		public static function getMenuHTML( $seleccion ){
                        /* if (controlo $seleccion) then aplico fondo oscuro al seleccionado */
                        $claseVenta ="";
                        $claseCompra ="";
                        $claseCliente ="";
                        $claseProveedor ="";
                        $claseProducto ="";
                        $claseReporte="";
                        $claseUsuarios="";
						$claseComisiones="";
                        $claseSeleccionado = ' class="seleccionado" ';
                        
                        switch ($seleccion) {      
                            case 'venta':
                                $claseVenta = $claseSeleccionado;
                                break;
                            case 'compra':
                                $claseCompra = $claseSeleccionado;
                                break;
                            case 'cliente':
                                $claseCliente =$claseSeleccionado;
                                break;
                            case 'proveedor':
                                $claseProveedor =$claseSeleccionado;
                                break;
                            case 'producto':
                                $claseProducto =$claseSeleccionado;
                                break;
                            case 'reporte':
                                $claseReporte=$claseSeleccionado;
                                break;
                            case 'usuario':
                                $claseUsuarios = $claseSeleccionado;
							case 'comision':
                                $claseComisiones = $claseSeleccionado;
                        }
                        
                    
			$html = '<nav>
                                    <ul>
                                        <li><a href="index.php" '.$claseVenta.'><img src="images/iconos/venta.png"><br/>Ventas</a></li>
                                        <li><a href="compras.php" '.$claseCompra.'><img src="images/iconos/compra.png"><br/>Compras</a>
                                        <li><a href="clientes.php" '.$claseCliente.'><img src="images/iconos/cliente.png"><br/>Cliente</a>
                                        <li><a href="proveedores.php" '.$claseProveedor.'><img src="images/iconos/proveedor.png"><br/>Proveedor</a>
                                        <li><a href="productos.php" '.$claseProducto.'><img src="images/iconos/producto.png"><br/>Producto</a>
                                        <li><a href="#" '.$claseReporte.'><img src="images/iconos/reporte.png" class="hoverli"><br/>Reportes</a>
                                                <ul>
                                                        <li><a href="reporteGanancias.php">Ganancias</a></li>
                                                        <li><a href="reporteDeudores.php">Deudores</a></li>
                                                </ul>
                                        </li>
                                        <li><a href="usuarios.php" '.$claseUsuarios.'><img height=32 width=32 src="images/iconos/usuario.png"><br/>Usuarios</a>
										<li><a href="comisiones.php" '.$claseComisiones.'><img height=32 width=32 src="images/iconos/comision.png"><br/>Movimientos</a>
                                        <li>';

                                        if(isset($_SESSION['usuario'])) {
                                            $html .= '<a style="position:relative; " href="./login.php?logout"><img height=32 width=32 src="./images/iconos/salir.png" alt="Salir"><br/>&nbsp;</a>';
                                        }
                                        $html.='</li>
										
                                    </ul>
                                </nav>';
			return $html;
		}
		
		
	}
	
?>

