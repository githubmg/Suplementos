<?php
	
    class VentaView {

        public static function getListadoHTML( $todasDePagina, $cantidadDePaginas, $parametros){
            $html = "<table id='ventas' class='tabla'>";
            $html .= VentaView::getTitulos($parametros,$cantidadDePaginas);
            $i=0;
            foreach( $todasDePagina as $mi_item_venta ){
                $i++; 
                $iv = new ItemVenta($mi_item_venta['id_item_venta']);
                $html .= VentaView::getHTML( $iv ,$i);

            }
            $html .= self::getTableFooter($parametros,$cantidadDePaginas);
            $html .="</table>";
            return $html;
        }

        public static function getTableFooter($parametros,$cantidadDePaginas){
            $enabledPrimeroyAnterior = "";
            $enabledUltimoySiguiente = "";

            $indicePag = $parametros['p'];

            $href='index.php?';
            if(isset($parametros['fecha_desde'])) {
                $href=$href.'fecha_desde='.urlencode($parametros['fecha_desde']).'&';
            }

            if(isset($parametros['fecha_hasta'])) {
                $href=$href.'fecha_hasta='.urlencode($parametros['fecha_hasta']).'&';
            }

            if(isset($parametros['subempresa'])) {
                $href=$href.'subempresa='.$parametros['subempresa'].'&';
            }

            if(isset($parametros['comboClientes'])) {
                $href=$href.'comboClientes='.$parametros['comboClientes'].'&';
            }

            if(isset($parametros['id_venta'])) {
                $href=$href.'id_venta='.$parametros['id_venta'].'&';
            }

            if(isset($parametros['comboProductos'])) {
                $href=$href.'comboProductos='.$parametros['comboProductos'].'&';
            }

            return PageView::getTableFooter($indicePag,$cantidadDePaginas,12,'venta',$href,$parametros);
            }

        public static function getTitulos($parametros,$cantidadDePaginas){

                //T&iacute;tulos de la clase
                $html = "
                <thead class='headerTabla'>";
                $html .= self::getTableFooter($parametros,$cantidadDePaginas);
                $html .= "
                <tr>
						<th scope='col' class='' > Nro. Venta </th>
						<th scope='col' class='' > Cliente </th>
						<th scope='col' class=''> Pagado Venta </th>
						<th scope='col' class=''> Fecha </th>
                        <th scope='col' class=''> Producto </th>
						<th scope='col' class=''> Cantidad </th>
                        <th scope='col' class=''> Stock Disponible </th>
                        <th scope='col' class=''> Monto &Iacute;tem</th>
						<th scope='col' class=''> Costo Asignado</th>
						<th scope='col' class=''> Subempresa</th>
                        <th scope='col' class='colAngosta'> Acciones </th>

                </tr>
                </thead>
                ";
                return $html;
                }

        public static function getHTML( ItemVenta $iv,$i ){
                //primero mapeo los valores
				
                $id_item_venta = $iv->getId();
                $id_venta = $iv->getVenta()->getId();
                $nombre = $iv->getVenta()->getCliente()->getPersona()-> getNombre();
               	$total_abonado = $iv->getVenta()->getTotalAbonado();
                $fecha = date_format(date_create($iv->getVenta()->getFecha()),'d-m-Y');
				
                $producto = $iv->getProducto()->getDescripcionCompleta();
                $cantidad = $iv->getCantidad();
                $stock_disponible = $iv->getProducto()->getStockDisponible();
                $monto_total = $iv->getMontoTotal();

                $costo = $iv->getCosto();
				$subempresa = $iv->getSubempresa();
				//despues los clavo en un String
                $clase = VentaView::traerClase($i);
                $html = "
                <tr class='$clase'>
                    <td>$id_venta</td>
                    <td>$nombre</td>
                    <td>$total_abonado</td>
                    <td>$fecha</td>
                    <td>$producto</td>
                    <td>$cantidad</td>
                    <td>$stock_disponible</td>
                    <td>$monto_total</td>
					<td>$costo</td>
					<td>$subempresa</td>
                    <td>
                        <a href='edicion.php?action=m&categoria=venta&id=".$id_item_venta."' target='_blank' onClick='window.open(this.href, this.target, \"width=500,height=400,top=200,left=400\");return false;' ><img src='images/tabla/editar.png' /></a>
                        <a href='edicion.php?action=b&categoria=venta&id=".$id_item_venta."' target='_blank' onClick='window.open(this.href, this.target, \"width=500,height=400,top=200,left=400\");return false;' ><img src='images/tabla/borrar.png' /></a>
                    </td>
                </tr>
                ";
                return $html;
        }
        public static function traerClase($i ){
        if ($i % 2 == 0) {
                return 'odd';
                } else {
                return 'even';
                }	
        }
		public static function getFiltrosVenta(){
	            $html = '<form id="buscador" method="GET" >
                        <table>
                            <tr>
                                <td><br /></td>
                            </tr>
                            <tr>
                                <td>Nro Venta:</td>

                            </tr>
							<tr>
								<td align="center"><input id="txtIdVenta" type="text" name="id_venta"/></td>
							</tr>
                            <tr>
                                <td>Fecha Desde:</td>
                            </tr>
							<tr>
								<td align="center"><input id="txtFecha" type="text" name="fecha_desde"/></td>
							</tr>
                            <tr>
                                <td>Fecha Hasta:</td>
							</tr>	
							<tr>
								<td align="center"><input id="txtFecha2" type="text" name="fecha_hasta"/></td>
                            </tr>
							<tr>
                                <td>Cliente:</td>
							</tr>
							<tr>	
								<td align="center">'.ClienteView::getComboClientesHTML().'</td>
                            </tr>
                                <td>Producto:</td>
							</tr>
							<tr>	
								<td align="center">'.ProductoView::getComboProductosHTML().'</td>
                            </tr>
							<tr>
								<td>Subempresa:</td>
							</tr>
							<tr>
							
								<td><input type="checkbox" name="subempresa" value="SM"></td>
							</tr>
							<tr>
								<br />
                                <td  align="center" ><input type="submit" class="botonNegro" value="Buscar"/></td>
                            </tr>
                        </table>
                    </form>';
            return $html;
        }
        
        public static function getAltaVentaHTML(){
  
            $html = PageView::getScriptTxtFecha()."<br />
				<script>	 
					 window.onbeforeunload = borrarItemsSesionAjax();
				</script>
                    <table>
                        <tr>
                            <td>Fecha:</td>
                            <td><input type='text' id='txtFechaVenta' name='fecha_venta'></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Cliente:</td>
                            <td>".ClienteView::getComboClientesHTML()."</td>
                                    <td> 
                                            <a href='#' id='lnkAgregarCliente' onclick='agregarCliente();' >Agregar Cliente </a>
                                    </td>		
                        </tr>
						<tr>
                                <td> </td>
                                <td colspan='2'>".ClienteView::getAltaClienteEmbebidoHTML()."

                                </td>
						</tr>
						 <tr>
                            <td>Monto abonado:</td>
                            <td><input type='text' maxlength=10 onChange='validarNumero(monto_abonado, \"El campo Monto Abonado debe ser numérico\")' name='monto_abonado' id='monto_abonado' value=''/></td>
							<td> 
							</td>
                        </tr>
						
						<tr>
                                <td> Agregar Items: </td>
                                <td colspan='2'>
								<table style='border: 1px solid #333333;'>   
									<tr>
										<td>Producto:</td>
										<td><div id = 'respuestaProducto' >".ProductoView::getComboProductosHTML()."</div></td>
										<td> 
											  <a href='#' id='lnkAgregarProducto' onclick='mostrarAgregarProducto();' >Agregar Producto </a>
										</td>	
									</tr>
									<tr>
											<td> </td>
											<td colspan='2'>".ProductoView::getAltaProductoEmbebidoHTML()."

											</td>

									<tr>
									<tr>
										<td>Stock Disponible:</td>
										<td><div id='respuesta'></div></td>
										<td> 
										</td>	
									</tr>
									<tr>
										<td >Monto:</td>
										<td><input maxlength=10 onChange='traerCosto()' width = '15px' type='text' name='monto' id='monto' value='0'/> </div></td>
										<td> 
										</td>	
									</tr>
									<tr>
										<td>Cantidad:</td>
										<td><input maxlength=10 onChange='traerCosto()' width = '15px' type='text' name='cantidad' id='cantidad' value='0'/></td>
										<td> 
										</td>	
									</tr>
                                                                        <tr>
										<td>Costo Estimado:</td>
										<td><div name='costo' id='costo' ></div></td>
										<td> 
										</td>	
									</tr>
									<tr>
										<td>Subempresa:</td>
										<td> <input type='checkbox' id='subempresaForm' name='subempresaForm' value='SM'>SM </td>
										<td> 
										</td>	
									</tr>
									<tr>
									<td colspan = '3'><a href='#' id='lnkAgregaItem' onclick='agregarItem();' > Agregar Item </a></td>
									</tr>
									
								
								</table>
                                </td>
						</tr>
						<tr>
							<td colspan = '3'>Items de la venta:</td>
						</tr>
						<tr>
							<td colspan = '3'>
								<div id='tablaItems'> 
								
								</div>
							</td>
						</tr>
                   </table>
                ";
            return $html;
        }
        
        public static function getModifVentaHTML($idItemventa){
		
            $item = new ItemVenta($idItemventa);
            $venta = $item ->getVenta();
			$fecha = $venta->getFecha();
			$sm = '';
			if ($item ->getSubempresa() == 'SM'){
				$sm = 'checked';	
			}
			
            $html = "<script type='text/javascript'> function actualizarPrecioTotal() {
                        preciototal = document.getElementById('precio_total');
                        cantidad = document.getElementById('cantidad').value;
                        precio = document.getElementById('precio').value;
                        precio_total = precio*cantidad;
                        if (isNaN(precio_total)) {
                        preciototal.innerHTML=0;
                        } else {
                            preciototal.innerHTML=precio_total;
                        }
                    }
					$(document).ready(function() {
						$('#txtFechaVenta').datepicker(
						{   dateFormat: 'd MM, yy',
							changeMonth: true,
							changeYear: true,
							numberOfMonths: 2,
							dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
							monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
								'Junio', 'Julio', 'Agosto', 'Septiembre',
								'Octubre', 'Noviembre', 'Diciembre'],
							monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr',
								'May', 'Jun', 'Jul', 'Ago',
								'Sep', 'Oct', 'Nov', 'Dic'],
					
					
								});
						var fecha = new Date();
						fecha.setFullYear(".date("Y",strtotime($fecha)).",".(date("m",strtotime($fecha))-1).",".date("d",strtotime($fecha)).");		
						$('#txtFechaVenta').datepicker('setDate',fecha);				
						});
					
					</script>
                    <table>
						
							<td>Producto:</td>
							<td><div id = 'respuestaProducto' >".ProductoView::getComboProductosHTML($item->getProducto()->getId())."</div></td>
							<td> 
								  <a href='#' id='lnkAgregarProducto' onclick='mostrarAgregarProducto();' >Agregar Producto </a>
							</td>	
						</tr>
						<tr>
								<td> </td>
								<td colspan='2'>".ProductoView::getAltaProductoEmbebidoHTML()."

								</td>

						<tr>
						<tr>
							<td>Stock Disponible:</td>
							<td><div id='respuesta'>".DB::getStockProducto($item->getProducto()->getId())."</div></td>
							<td> 
							</td>	
						</tr>
						<tr>
							<td >Monto:</td>
							<td><input maxlength=10 onChange='validarNumero(monto, \"El campo Monto debe ser numérico\")' width = '15px' type='text' name='monto' id='monto' value='".$item->getMontoTotal()."''/> </div></td>
							<td> 
							</td>	
						</tr>
						<tr>
							<td >Cantidad:</td>
							<td><input maxlength=10 onChange='validarNumero(cantidad, \"El campo Cantidad debe ser numérico\")' width = '15px' type='text' name='cantidad' id='cantidad' value='".$item->getCantidad()."'/></td>
											
							<td> 
							</td>	
						</tr>
						<tr>
							<td height = '50px'>Costo:</td>
							<td><input maxlength=10 onChange='validarNumero(costo, \"El campo Costo debe ser numérico\")' width = '15px' type='text' name='costo' id='costo' value='".$item->getCosto()."'/></td>
											
							<td> 
							</td>	
						</tr>
						<tr>
						<tr>
							<td height = '50px'>Subempresa:</td>
							<td><input type='checkbox' id='subempresaForm' name='subempresaForm' value='SM' $sm >SM </td>
											
							<td> 
							</td>	
						</tr>
						<tr>
						<tr>
						<td colspan='3'>
						<table style='border: 1px solid #333333;'>
							<tr>
							<td colspan='3' align='center'>
								<u> Modificar Venta </u>
							</td>					
							</tr>
							<tr>
								<td>Cliente:</td>
								<td>".ClienteView::getComboClientesHTML($venta->getCliente()->getId())."</td>
								<td> 
										<a href='#' id='lnkAgregarCliente' onclick='agregarCliente();' >Agregar Cliente </a>
								</td>		
							</tr>
							<tr>
								<td> </td>
								<td colspan='2'>".ClienteView::getAltaClienteEmbebidoHTML()."

								</td>

							</tr>
							<tr>
                            <td>Monto abonado:</td>
								<td><input type='text' maxlength=10 onChange='validarNumero(monto_abonado, \"El campo Monto Abonado debe ser numérico\")' name='monto_abonado' id='monto_abonado' value='".$venta->getTotalAbonado()."'/></td>
								<td></td>     
							</tr>
							<tr>
								<td>Fecha:</td>
								<td><input type='text' id='txtFechaVenta' name='fecha_venta'></td>
								<td></td>					
							</tr>
						</table>
						</td>
						<tr>
                    </table>
                    <script type='text/javascript'> actualizarPrecioTotal()</script>
                ";
            return $html;
        }

        public static function getBuscadorHTML() {
            $html = '<form id="buscador" method="GET" >
                        <table>
                            <tr>
                                <td>Buscar</td>
                            </tr>
                            <tr>
                                <td>Nro Venta:</td>
                            </tr>
							<tr>
								<td align="center"><input id="id_venta" type="text" name="id_venta"/></td>
							</tr>
                            <tr>
                                <td>Fecha Desde: </td>
							</tr>
							<tr>								
								<td align="center"><input id="txtFecha" type="text" name="fecha_desde"/></td>
                            </tr>
                            <tr>
                                <td>Fecha Hasta: </td>
							</tr>
							<tr>		
								<td align="center"><input id="txtFecha2" type="text" name="fecha_hasta"/></td>
                            </tr>
							<tr>
                                <td>Cliente: </td>
							</tr>
							<tr>	
								<td align="center">'.ClienteView::getComboClientesHTML().'</td>
                            </tr>
                            <tr>
                                <td>Producto: </td>
							</tr>
							<tr>	
								<td align="center">'.ProductoView::getComboProductosHTML().'</td>
                            </tr>
							<tr>	
								<td align="center"> <input type="checkbox" name="subempresa" value="SM">SM </td>
                            </tr>
                            <tr>
                                <td align = "center"><br /><input type="submit"  class="botonNegro" value="Buscar"/></td>
                            </tr>
                        </table>
                    </form>';
            return $html;
        }
        
        public static function getBajaVentaHTML($id){
            
            $html = VentaView::getModifVentaHTML($id);
            $html.= "<script type='text/javascript'>desactivar('monto_abonado'); desactivar('precio'); desactivar('cantidad');</script>";
            return $html;
            return $html;
        }
    }	
?>
