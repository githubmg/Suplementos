<?php
	
    class ComisionView {

        public static function getListadoHTML( $todasDePagina, $cantidadDePaginas, $parametros){
            $html = "<table id='comisiones' class='tabla'>";
            $html .= ComisionView::getTitulos($parametros,$cantidadDePaginas);
            $i=0;
            foreach( $todasDePagina as $mi_comision ){
                $i++; 
                $com = new Comision($mi_comision['id']);
                $html .= ComisionView::getHTML( $com ,$i);

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

            if(isset($parametros['comboClientes'])) {
                $href=$href.'comboClientes='.$parametros['comboClientes'].'&';
            }


            return PageView::getTableFooter($indicePag,$cantidadDePaginas,12,'comision',$href,$parametros);
            }

        public static function getTitulos($parametros,$cantidadDePaginas){

                //T&iacute;tulos de la clase
                $html = "
                <thead class='headerTabla'>";
                $html .= self::getTableFooter($parametros,$cantidadDePaginas);
                $html .= "
                <tr>
						<th scope='col' class='' > Nro. Movimiento </th>
						<th scope='col' class='' > Cliente </th>
						<th scope='col' class=''> Fecha </th>
                        <th scope='col' class=''> Concepto </th>
						<th scope='col' class=''> Nro. de Venta </th>
                        <th scope='col' class=''> Importe </th>
                        <th scope='col' class=''> Observaciones</th>
						<th scope='col' class='colAngosta'> Acciones </th>

                </tr>
                </thead>
                ";
                return $html;
                }

        public static function getHTML( Comision $com,$i ){
                //primero mapeo los valores
				
                $id = $com->getId();
                $producto = $com->getProducto();
                $nombreCliente = $com->getCliente()->getPersona()-> getNombre();
				$fecha = date_format(date_create($com->getFecha()),'d-m-Y');
				$nro_venta = $com->getNroVenta();
                $importe = $com->getImporte();
                $observaciones = $com->getObservaciones();
                $clase = VentaView::traerClase($i);
                $html = "
                <tr class='$clase'>
                    <td>$id</td>
                    <td>$nombreCliente</td>
                    <td>$fecha</td>
                    <td>$producto</td>
                    <td>$nro_venta</td>
                    <td>$importe</td>
                    <td>$observaciones</td>
                    <td>
                        <a href='edicion.php?action=m&categoria=comision&id=".$id."' target='_blank' onClick='window.open(this.href, this.target, \"width=500,height=400,top=200,left=400\");return false;' ><img src='images/tabla/editar.png' /></a>
                        <a href='edicion.php?action=b&categoria=comision&id=".$id."' target='_blank' onClick='window.open(this.href, this.target, \"width=500,height=400,top=200,left=400\");return false;' ><img src='images/tabla/borrar.png' /></a>
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
                                <br /></td>
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
        
        public static function getAltaComisionHTML(){
  
            $html = PageView::getScriptTxtFechaGeneric("txtFechaComision")."<br />
				<script>	 
					 window.onbeforeunload = borrarItemsSesionAjax();
				</script>
                    <table>
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
                            <td>Fecha:</td>
                            <td><input type='text' id='txtFechaComision' name='fecha_comision' /></td>
                            <td></td>
                        </tr>
						<tr>
                            <td>Concepto:</td>
                            <td><input type='text' id='txtProducto' name='producto' /></td>
							<td> 
							</td>
                        </tr>
						<tr>
                            <td>Nro. de venta:</td>
                            <td><input type='text' id='txtNroVenta' name='nroVenta' /></td>
							<td> 
							</td>
                        </tr>
						<tr>
                            <td>Importe:</td>
                            <td>$&nbsp;<input type='text' id='txtImporte' name='importe' /></td>
							<td> 
							</td>
                        </tr>
						<tr>
                            <td>Observaciones:</td>
                            <td><input type='text' id='txtObservaciones' name='observaciones' /></td>
							<td> 
							</td>
                        </tr>			
				</table>
                ";
            return $html;
        }
        
        public static function getModifComisionHTML($id){
		
            $comision = new Comision($id);
           	$fecha = $comision->getFecha();
			   $html = "
				<script>	 
					$(document).ready(function() {
						debugger;
						$('#txtFechaComision').datepicker(
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
						$('#txtFechaComision').datepicker('setDate',fecha);				
						});
				</script>
				<table>
                       	<tr>
								<td>Cliente:</td>
								<td>".ClienteView::getComboClientesHTML($comision->getCliente()->getId())."</td>
								<td> 
										<a href='#' id='lnkAgregarCliente' onclick='agregarCliente();' >Agregar Cliente </a>
								</td>		
							</tr>
						<tr>
                            <td>Fecha:</td>
                            <td><input type='text' id='txtFechaComision' name='fecha_comision' /></td>
                            <td></td>
                        </tr>
						<tr>
                            <td>Concepto:</td>
                            <td><input type='text' id='txtProducto' name='producto' value='".$comision->getProducto()."' /></td>
							<td> 
							</td>
                        </tr>
						<tr>
                            <td>Nro. de venta:</td>
                            <td><input type='text' id='txtNroVenta' name='nroVenta' value='".$comision->getNroVenta()."' /></td>
							<td> 
							</td>
                        </tr>
						<tr>
                            <td>Importe:</td>
                            <td>$&nbsp;<input type='text' id='txtImporte' name='importe' value='".$comision->getImporte()."' /></td>
							<td> 
							</td>
                        </tr>
							<tr>
                            <td>Observaciones:</td>
                            <td><input type='text' id='txtObservaciones' name='observaciones' value='".$comision->getObservaciones()."'/></td>
							<td> 
							</td>
                        </tr>							
				</table>
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
                                <td>Fecha Desde: </td>
							</tr>
							<tr>								
								<td><input id="txtFecha" type="text" name="fecha_desde"/></td>
                            </tr>
                            <tr>
                                <td>Fecha Hasta: </td>
							</tr>
							<tr>		
								<td><input id="txtFecha2" type="text" name="fecha_hasta"/></td>
                            </tr>
							<tr>
                                <td>Cliente: </td>
							</tr>
							<tr>	
								<td>'.ClienteView::getComboClientesHTML().'</td>
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
        
    }	
?>
