<?php
	
    class ProductoView {
        public static function getComboProductosHTML(){
			
            $listaProductos = DB::getIdsProducto(NULL);
            $html ='
					<div class="ui-widget";">
                        <select id="comboboxpro" name="comboProductos">
                        <option value="">Seleccione...</option>';
            
            if (func_num_args()==1) {
                $id = func_get_arg(0);
                foreach( $listaProductos as $mi_producto ){
                    $p = new Producto($mi_producto['id_producto']);
                    if ($id == $p->getId()) {
                        $html.='<option selected="selected" value="'.$p->getId().'">'.$p->getDescripcion().' '.$p->getTamanio().' '.$p->getSabor().'</option>';
                    } else {
						$html.='<option value="'.$p->getId().'">'.$p->getDescripcion().' '.$p->getTamanio().' '.$p->getSabor().'</option>';
                    }
                }
            } else {
                foreach( $listaProductos as $mi_producto ){
                    $p = new Producto($mi_producto['id_producto']);
                    $html.='<option value="'.$p->getId().'">'.$p->getDescripcion().' '.$p->getTamanio().' '.$p->getSabor().'</option>';
                }
            }
            

            $html.='
                        </select>
                    </div> 
				
                ';
            return $html;

        }
        
        public static function getListadoHTML( $todosDePagina, $indicePag, $cantidadDePaginas){
            $html = "<table id='productos' class='tabla'>";
            $html .= ProductoView::getTitulos();
            $i=0;
            foreach( $todosDePagina as $producto ){

                $i++;
                $p = new Producto($producto['id_producto']);
                $html .= ProductoView::getHTML( $p ,$i);

            } 
            $html .= PageView::getTableFooter($indicePag,$cantidadDePaginas,8,'producto','productos.php?',null);
            $html .="</table>";

            return $html;
        }

        public static function getTitulos(){
            //T&iacute;tulos de la clase
            $html = "
            <thead class='headerTabla'>
                <tr>
                    <th scope='col' > Nombre de Producto </th>
                    <th scope='col' class='hidden'> Id_Producto </th>
					<th scope='col' class='colAngosta'> Tamanio</th>
					<th scope='col' class='colAngosta'> Sabor</th>
					<th scope='col' class='colAngosta'> Stock SM</th>
                    <th scope='col' class='colAngosta'> Stock Total</th>
					<th scope='col' class='colAngosta'> Laboratorio </th>
					<th scope='col' class='colAngosta'> Observaciones</th>
                    <th scope='col' class='colAngosta'> Acciones </th>
                </tr>
            </thead>
            ";
            return $html;
        }

        public static function getTableFooter($indicePag,$cantidadDePaginas){
        $enabledPrimeroyAnterior = "";
        $enabledUltimoySiguiente = "";

        if ($indicePag==1) {
            $enabledPrimeroyAnterior = "class='active'";
        }
        if ($indicePag==$cantidadDePaginas) {
            $enabledUltimoySiguiente = "class='active'";
        }

        $html = "
            <tfoot class='footerTabla'>
                <tr>
                <td colspan=6>
                    <table border=0>
                        <tr>
                            
                            <td align='center'><a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=a&categoria=producto\"); return false;'><img src='images/tabla/agregar.png'/></a></td>
                            <td>
                                <div class='paginador' >
                                    <a href='productos.php?p=1' ".$enabledPrimeroyAnterior."><img src='images/tabla/primero.png' alt='Primero' /></a>
                                    <a href='productos.php?p=".($indicePag-1)."' ".$enabledPrimeroyAnterior."><img src='images/tabla/atras.png' alt='Atr&aacute;;s') /></a>
                                    P&aacute;gina ".$indicePag." de ".$cantidadDePaginas."
                                    <a href='productos.php?p=".($indicePag+1)."' ".$enabledUltimoySiguiente."><img src='images/tabla/adelante.png' alt='Adelante' /></a>
                                    <a href='productos.php?p=".($cantidadDePaginas)."' ".$enabledUltimoySiguiente."><img src='images/tabla/ultimo.png' alt='&Uacute;ltimo'/></a>

                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                </tr>
            </tfoot>";
        return $html;
        }

        public static function traerClase($numeroFila ){
            if ($numeroFila % 2 == 0) {
                    return 'odd';
                    } else {
                    return 'even';
                    }
        }

        public static function getHTML( Producto $p,$numeroFila ){
            //primero mapeo los valores
            $producto = StringController::sanitizarParaHTML($p->getDescripcion());
            $id_producto = $p->getId();
			$stock_sm = $p->getStockSM();
			$observaciones = $p->getObservaciones();
            $stock_disponible = $p->getStockDisponible();
            $tamanio = StringController::sanitizarParaHTML($p->getTamanio());
            $sabor = StringController::sanitizarParaHTML($p->getSabor());
            $laboratorioOBJ = $p->getLaboratorio();
                    if (isset($laboratorioOBJ)){
                            $laboratorio = StringController::sanitizarParaHTML($laboratorioOBJ->getDescripcion());
                    }else{
                            $laboratorio = "";
                    }
            //despues los clavo en un String
            $clase = ProductoView::traerClase($numeroFila);
            $html = "
                <tr class='$clase'>
                    <td>$producto</td>
                    <td class='hidden'>$id_producto</td>
					<td>$tamanio</td>
					<td>$sabor</td>
					<td>$stock_sm</td>
					<td>$stock_disponible</td>
					<td>$laboratorio</td>
					<td>$observaciones</td>
                    <td>
                        <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=m&categoria=producto&id=".$id_producto."\"); return false;' ><img src='images/tabla/editar.png' /></a>
                        <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=b&categoria=producto&id=".$id_producto."\"); return false;' ><img src='images/tabla/borrar.png' /></a>
                    </td>
                </tr>
            ";
            return $html;
        }
        public static function getAltaProductoEmbebidoHTML(){
                $html= "<div id='divAgregarProducto'><table ><tr><td>".self::getAltaProductoHTML()."</td></tr>
                                <tr><td align='center'><input type='button' class='botonNegro' name ='addProducto' value ='Agregar Producto' onclick='agregarProducto();'></input></td></tr>
                                </table></div>";
                return $html;				
        }
        public static function getModifProductoHTML($id){
            $producto = new Producto($id);
            if (!$producto) {
                echo "Error: no se encontr&oacute; el producto con id $id";
                return false;
            }
			$laboratorio = $producto->getLaboratorio(); 
			if ($laboratorio != null){
				$comboLaboratorio = LaboratorioView::getComboLaboratorioHTML($producto->getLaboratorio()->getId());
			} else {
				$comboLaboratorio = LaboratorioView::getComboLaboratorioHTML();
			}
            $html = "<table>
                    <tr>
                        <td>
                            Nombre de Producto:
                        </td>
                        <td>
                            <input type='text' name='descripcion' id='descripcion' value='".$producto->getDescripcion()."'/>
                        </td>
						<td></td>
                    </tr>
					<tr>
                        <td>
                                Tama&ntilde;o:
                        </td>
                        <td>
                                <input type='text' name='tamanio' id='tamanio' value='".$producto->getTamanio()."'/>
                        </td>
						<td></td>
                    </tr>
					<tr>
                        <td>
                                Sabor:
                        </td>
                        <td>
                                <input type='text' name='sabor' id='sabor' value='".$producto->getSabor()."'/>
                        </td>
						<td></td>
                    </tr>
					<tr>
                        <td>
                                Laboratorios:
                        </td>
                        <td>
							<div id = 'respuestaLaboratorio'>
                                ".$comboLaboratorio."
							</div>	
                        </td>
						<td> 
							<a href='#' id='lnkAgregarLaboratorio' onclick='mostrarLaboratorio();' >Agregar Laboratorio </a>
						</td>
                    </tr>
					<tr>
						<td> 
						</td>
						<td colspan='2'>".LaboratorioView::getAltaLaboratorioEmbebidoHTML()."

						</td>

					<tr>
                    <tr>
                        <td>
                                Observaciones<br>(hasta 500 car.) :
                        </td>
                        <td>
                                <textarea id='observaciones' name='observaciones' rows=10 cols=30>".$producto->getObservaciones()."</textarea>
                        </td>
						<td></td>
                    </tr>
					
                    </table>
                ";
            return $html;

        }
    
        public static function getAltaProductoHTML(){
            $html = "<table>
                    <tr>
                        <td>
                            Nombre de Producto:
                        </td>
                        <td>
                            <input type='text' name='descripcion' id='descripcion'></input>
                        </td>
						<td>
						</td>
                    </tr>
					<tr>
                        <td>
                            Tama&ntilde;o:
                        </td>
                        <td>
                            <input type='text' name='tamanio' id='tamanio'></input>
                        </td>
						<td>
						</td>
                    </tr>
					<tr>
                        <td>
                            Sabor:
                        </td>
                        <td>
                            <input type='text' name='sabor' id='sabor'></input>
                        </td>
						<td>
						</td>
                    </tr>
					<tr>
                        <td>
                                Laboratorios:
                        </td>
                        <td>
							<div id = 'respuestaLaboratorio'>
                                ".LaboratorioView::getComboLaboratorioHTML()."
							</div>	
                        </td>
						<td> 
							<a href='#' id='lnkAgregarLaboratorio' onclick='mostrarLaboratorio();' >Agregar Laboratorio </a>
						</td>
                    </tr>
					<tr>
						<td> 
						</td>
						<td colspan='2'>".LaboratorioView::getAltaLaboratorioEmbebidoHTML()."

						</td>

					<tr>
                    <tr>
                        <td>
                                Observaciones<br>(hasta 500 car.) :
                        </td>
                        <td>
                                <textarea id='observaciones' name='observaciones' rows=10 cols=30></textarea>
                        </td>
						<td>
						</td>
                    </tr>
					
					

                    </table>
                ";
            return $html;
        }
        
        public static function getBajaProductoHTML($id) {
            $html = ProductoView::getModifProductoHTML($id);
            $html.= "<script type='text/javascript'>desactivar('descripcion'); desactivar('observaciones');</script>";
            return $html;
        }

        public static function getBuscadorHTML() {
            $html = '<form id="buscador" method="GET" >
                        <table>
                            <tr>
                                <td>Buscar</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Producto: </td>
                            </tr>
                            <tr>
                                    <td>'.ProductoView::getComboProductosHTML().'</td>
                            </tr>
                            <tr>
                                <td align = "center"><br /><input type="submit"  class="botonNegro" value="Buscar"/></td>
                            </tr>
                        </table>
                    </form>';
            return $html;
        }
    }