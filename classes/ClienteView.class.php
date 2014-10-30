<?php
	
    class ClienteView {
        public static function getComboClientesHTML(){

        $listaClientes = DB::getIdsCliente(NULL);
        $html ='
                <div class="ui-widget">
                    <select id="comboboxcli" name="comboClientes">
                        <option value="">Seleccione...</option>';

        $cantArgs= func_num_args();
        if ($cantArgs==1) {
            foreach( $listaClientes as $mi_cliente ){
                $c = new Cliente($mi_cliente['id_cliente']);
                if (func_get_arg(0)==$c->getId()) {
                    $html.='<option selected="selected" value="'.$c->getId().'">'.$c->getPersona()->getNombre().'</option>';
                } else {
                    $html.='<option value="'.$c->getId().'">'.$c->getPersona()->getNombre().'</option>';
                }
            }
        } else {
            foreach( $listaClientes as $mi_cliente ){
                $c = new Cliente(intval($mi_cliente['id_cliente']));
                $html.='<option value="'.$c->getId().'">'.$c->getPersona()->getNombre().'</option>';
            }
        }

        $html.='    </select>
                </div>';
        return $html;

        }
        
        public static function getListadoHTML( $todosDePagina, $indicePag, $cantidadDePaginas){
            $html = "<table id='clientes' class='tablaCliente'>";
            $html .= ClienteView::getTitulos();
            $i=0;
            foreach( $todosDePagina as $cliente ){

                $i++;
                $p = new Cliente($cliente['id_cliente']);
                $html .= ClienteView::getHTML( $p ,$i);


            } 
            $html .= PageView::getTableFooter($indicePag,$cantidadDePaginas,13,'cliente','clientes.php?',null);
            $html .="</table>";

            return $html;
        }

        public static function getTitulos(){
            //T&iacute;tulos de la clase
            $html = "
            <thead class='headerTabla'>
                <tr>
                    <th scope='col' class='colAncha' > Cliente </th>
                    <th scope='col' class='hidden'> Id_Cliente </th>
                    <th scope='col'> Tipo </th>
                    <th scope='col' > Provincia </th>
                    <th scope='col' > Localidad </th>
                    <th scope='col' > Domicilio </th>
                    <th scope='col' > Telefono</th>
                    <th scope='col'> Email </th>
                    <th scope='col' > Observaciones</th>
                    <th scope='col' > Acciones </th>
                </tr>
            </thead>
            ";
            return $html;
        }

      

        public static function traerClase($numeroFila ){
            if ($numeroFila % 2 == 0) {
                    return 'odd';
                    } else {
                    return 'even';
                    }
        }

        public static function getHTML( Cliente $c,$numeroFila ){
            //primero mapeo los valores
            
            $persona = $c->getPersona();
            $subempresa = $c->getSubempresa();
            $cliente = StringController::sanitizarParaHTML($persona-> getNombre());
            $id_cliente = $c->getId();
            $domicilio =  $persona->getDomicilio();
            if ($domicilio->getLocalidad()->getId() ){
               $provincia = StringController::sanitizarParaHTML($domicilio->getLocalidad()->getProvincia()->getDescripcion());
               $localidad = StringController::sanitizarParaHTML($domicilio->getLocalidad()->getDescripcion());
            } else {
                $provincia = "";
                $localidad = "";                
            }

            $txtDomicilio = StringController::sanitizarParaHTML($domicilio->getDescripcion());
            $observaciones = StringController::sanitizarParaHTML($persona->getObservaciones());
            $email = StringController::sanitizarParaHTML($persona->getEmail());
            $telefono = StringController::sanitizarParaHTML($persona->getTelefono());

            //despues los clavo en un String
            $clase = ClienteView::traerClase($numeroFila);
            $html = "
                <tr class='$clase'>
                    <td >$cliente</td>
                    <td class='hidden'>$id_cliente</td>
                    <td>$subempresa</td>
                    <td>$provincia</td>
                    <td>$localidad</td>
                    <td>$txtDomicilio</td>
                    <td>$telefono</td>
                    <td>$email</td>
                    <td>$observaciones</td>
                    <td>   
                        <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=m&categoria=cliente&id=".$id_cliente."\"); return false;' ><img src='images/tabla/editar.png' /></a>
                        <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=b&categoria=cliente&id=".$id_cliente."\"); return false;' ><img src='images/tabla/borrar.png' /></a>
                    </td>
                </tr>
            ";
            return $html;
        }
        
        
        public static function getModifClienteHTML($id){
            $cliente = new Cliente($id);
            if (!$cliente) {
                echo "Error: no se encontr&oacute; el cliente con id $id";
                return false;
            }
            if ($cliente->getPersona()->getDomicilio()->getLocalidad()->getId() ){
               $idProvincia = $cliente->getPersona()->getDomicilio()->getLocalidad()->getProvincia()->getId();
               $idLocalidad = $cliente->getPersona()->getDomicilio()->getLocalidad()->getId();                
            } else {
                $idProvincia = null;
                $idLocalidad = null;                
            }
			if ($cliente->getSubempresa() == 'SM'){
				$checked = "checked='true'";
			}else{
				$checked = '';
			}
			
            
            $html = "<table>
                    <tr>
                        <td>Nombre:</td>
                        <td>
                            <input type='text' name='nombre' maxlength=100 id='nombre' value='".$cliente->getPersona()->getNombre()."'/>
                        </td>
                    </tr>
					<tr>
                        <td>Tipo:</td>
                        <td>
                             <input type='checkbox' name='SM' value='SM' ".$checked.">  SM
                        </td>
                    </tr>
                    <tr>
                        <td>Provincia:</td>
                        <td>".ProvinciaView::getComboProvinciaHTML($idProvincia)."</td>
                    </tr>
                    <tr>
                        <td>Localidad/Barrio:</td>
                        <td>".LocalidadView::getComboLocalidadHTML($idLocalidad)."</td>
                    </tr>
                    <tr>
                        <td>Domicilio:</td>
                        <td><input type='text' maxlength=250 name='domicilio' id='domicilio' value='".$cliente->getPersona()->getDomicilio()->getDescripcion()."'/></td>
                    </tr>
                    <tr>
                        <td>Tel&eacute;fono:</td>
                        <td><input type='text' name='telefono' maxlength=40 id='telefono' value='".$cliente->getPersona()->getTelefono()."'/></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type='text' name='email' id='email' maxlength=95 value='".$cliente->getPersona()->getEmail()."'/></td>
                    </tr>
                    <tr>
                        <td>
                                Observaciones<br>(hasta 500 car.) :
                        </td>
                        <td>
                                <textarea id='observaciones' maxlength=495 name='observaciones' rows=10 cols=30>".$cliente->getPersona()->getObservaciones()."</textarea>
                        </td>
					
                    </tr>
                    </table>
                ";
            return $html;

        }
        public static function getAltaClienteEmbebidoHTML(){
                $html= "<div id='divAgregarCliente'><table class='embebidoOculto'><tr><td>".self::getAltaClienteHTML()."</td></tr>
                                <tr><td align='center'><input type='submit' class='botonNegro' name ='addCliente' value ='Agregar Cliente' onclick='agregarCliente();'></input></td></tr>
                                </table></div>";
                return $html;				
        }
        public static function getAltaClienteHTML(){
            
            
            $html = "<table>
                    <tr>
                        <td>Nombre:</td>
                        <td>
                            <input type='text' name='nombre' id='nombre' value=''/>
                        </td>
                    </tr>
					<tr>
                        <td>Tipo:</td>
                        <td>
                             <input type='checkbox' name='SM' value='SM'>  SM
                        </td>
                    </tr>
                    <tr>
                        <td>Provincia:</td>
                        <td>".ProvinciaView::getComboProvinciaHTML()."</td>
                    </tr>
                    <tr>
                        <td>Localidad/Barrio:</td>
                        <td><div id='respuestaComboLoc'>".LocalidadView::getComboLocalidadHTML()."</div></td>
                    </tr>
                    <tr>
                        <td>Domicilio:</td>
                        <td><input maxlength=250 type='text' name='domicilio' id='domicilio' value=''/></td>
                    </tr>
                    <tr>
                        <td>Tel&eacute;fono:</td>
                        <td><input type='text' maxlength=40 name='telefono' id='telefono' value=''/></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type='text' name='email' maxlength=95 id='email' value=''/></td>
                    </tr>
                     <tr>
                        <td>
                                Observaciones<br>(hasta 500 car.) :
                        </td>
                        <td>
                                <textarea maxlength=500 id='observaciones' name='observaciones' rows=10 cols=30></textarea>
                        </td>
					
                    </tr>
                    </table>
                ";
            return $html;

        }
        
        public static function getBajaClienteHTML($id){
            $html = ClienteView::getModifClienteHTML($id);
            
            $html.= "<script type='text/javascript'>desactivar('nombre'); desactivar('email'); desactivar('observaciones'); desactivar('telefono'); desactivar('domicilio');</script>";
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
                                <td>Cliente: </td>
                            </tr>
                            <tr>
                                    <td>'.ClienteView::getComboClientesHTML().'</td>
                            </tr>		
                            <tr>
                                <td align = "center"><br /><input type="submit"  class="botonNegro" value="Buscar"/></td>
                            </tr>
                        </table>
                    </form>';
            return $html;
        }
               
    }
