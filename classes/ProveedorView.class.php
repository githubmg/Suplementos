<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProveedorView
 *
 * @author german
 */
class ProveedorView {
    
    public static function getListadoHTML( $todosDePagina, $indicePag, $cantidadDePaginas){
        $html = "<table id='proveedores' class='tabla'>";
        $html .= ProveedorView::getTitulos();
        $i=0;
        foreach( $todosDePagina as $proveedor ){

            $i++;
            $p = new Proveedor(intval($proveedor['id_proveedor']));
            $html .= ProveedorView::getHTML( $p ,$i);

        } 
        $html .= PageView::getTableFooter($indicePag,$cantidadDePaginas,12,'proveedor','',null);
        $html .="</table>";

        return $html;
    }
    
    public static function getComboProveedoresHTML(){
            
            $listaIdProveedores = DB::getIdsProveedor(NULL);
        
            $html = '<div class="ui-widget">
                        <select id="comboboxprove" name="comboProveedores">
                            <option value="">Seleccione...</option>';
            if (func_num_args()==1) {
                
                $id = func_get_arg(0);
                
                foreach( $listaIdProveedores as $proveedor ){
                    $p = new Proveedor(intval($proveedor['id_proveedor']));
                    if ($id == $p->getId()) {
                        $html.='<option selected="selected" value="'.$p->getId().'">'.$p->getPersona()->getNombre().'</option>';
                    } else {
                        $html.='<option value="'.$p->getId().'">'.$p->getPersona()->getNombre().'</option>';
                    }
                    
                }
            } else {
                foreach( $listaIdProveedores as $proveedor ){
                    $p = new Proveedor(intval($proveedor['id_proveedor']));
                    $html.='<option value="'.$p->getId().'">'.$p->getPersona()->getNombre().'</option>';
                }
            }
            
            
            $html.='
                        </select>
                    </div>';

        return $html;
    }
    
    public static function getTitulos(){
        //T&iacute;tulos de la clase
        $html = "
        <thead class='headerTabla'>
            <tr>
                <th scope='col' class='colAncha'> Proveedor </th>
                <th scope='col' class='hidden'> Id_Proveedor </th>
                <th scope='col' class='colAncha'> Provincia </th>
                <th scope='col' class='colAncha'> Localidad </th>
                <th scope='col' class='colAngosta'> Domicilio </th>
                <th scope='col' class='colAncha'> Telefono</th>
                <th scope='col' class='colAncha'> Email </th>
                <th scope='col' class='colAngosta'> Observaciones</th>
                <th scope='col' class='colAngosta'> Acciones </th>
            </tr>
        </thead>
        ";
        return $html;
    }
    public static function getAltaProveedorEmbebidoHTML(){
            $html= "<div id='divAgregarProveedor'><table><tr><td>".self::getAltaProveedorHTML()."</td></tr>
                            <tr><td align='center'><input type='submit' class='boton' name ='addProveedor' value ='Agregar Proveedor' onclick='mostrarAgregarProveedor();'></input></td></tr>
                            </table></div>";
            return $html;				
    }
    
    
    public static function traerClase($numeroFila ){
        if ($numeroFila % 2 == 0) {
                return 'odd';
                } else {
                return 'even';
                }
    }
    
    public static function getHTML( Proveedor $p,$numeroFila ){
        //primero mapeo los valores
        $persona = $p->getPersona();
        $proveedor = StringController::sanitizarParaHTML($persona-> getNombre());
        $id_proveedor = $p->getId();
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
        $clase = ProveedorView::traerClase($numeroFila);
        $html = "
            <tr class='$clase'>
                <td>$proveedor</td>
                <td class='hidden'>$id_proveedor</td>
                <td>$provincia</td>
                <td>$localidad</td>
                <td>$txtDomicilio</td>
                <td>$telefono</td>
                <td>$email</td>
                <td>$observaciones</td>
                <td>
                    
                    <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=m&categoria=proveedor&id=".$id_proveedor."\"); return false;' ><img src='images/tabla/editar.png' /></a>
                    <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=b&categoria=proveedor&id=".$id_proveedor."\"); return false;' ><img src='images/tabla/borrar.png' /></a>
                </td>
            </tr>
        ";
        return $html;
    }
    
  public static function getModifProveedorHTML($id){
            $proveedor = new Proveedor(intval($id));
            if (!$proveedor) {
                echo "Error: no se encontr&oacute; el proveedor con id $id";
                return false;
            }
            if ($proveedor->getPersona()->getDomicilio()->getLocalidad()->getId() ){
               $idProvincia = $proveedor->getPersona()->getDomicilio()->getLocalidad()->getProvincia()->getId();
               $idLocalidad = $proveedor->getPersona()->getDomicilio()->getLocalidad()->getId();                
            } else {
                $idProvincia = null;
                $idLocalidad = null;                
            }
            
            $html = "<table>
                    <tr>
                        <td>Nombre:</td>
                        <td>
                            <input type='text' maxlenght=100 name='nombre' id='nombre' value='".$proveedor->getPersona()->getNombre()."'/>
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
                        <td><input maxlenght=250 type='text' name='domicilio' id='domicilio' value='".$proveedor->getPersona()->getDomicilio()->getDescripcion()."'/></td>
                    </tr>
                    <tr>
                        <td>Tel&eacute;fono:</td>
                        <td><input maxlenght=45 type='text' name='telefono' id='telefono' value='".$proveedor->getPersona()->getTelefono()."'/></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input maxlenght=100 type='text' name='email' id='email' value='".$proveedor->getPersona()->getEmail()."'/></td>
                    </tr>
					<tr>
                        <td>
                                Observaciones<br>(hasta 500 car.) :
                        </td>
                        <td>
                                <textarea maxlenght=500 id='observaciones' name='observaciones' rows=10 cols=30>".$proveedor->getPersona()->getObservaciones()."</textarea>
                        </td>
					
                    </tr>
                </table>
                ";
            return $html;

        }
    
        public static function getAltaProveedorHTML(){
            
            
            $html = "<table>
                    <tr>
                        <td>Nombre:</td>
                        <td>
                            <input maxlenght=100 type='text' name='nombre' id='nombre' value=''/>
                        </td>
                    </tr>
                    <tr>
                        <td>Provincia:</td>
                        <td>".ProvinciaView::getComboProvinciaHTML()."</td>
                    </tr>
                    <tr>
                        <td>Localidad/Barrio:</td>
                        <td>".LocalidadView::getComboLocalidadHTML()."</td>
                    </tr>
                    <tr>
                        <td>Domicilio:</td>
                        <td><input maxlenght=250 type='text' name='domicilio' id='domicilio' value=''/></td>
                    </tr>
                    <tr>
                        <td>Tel&eacute;fono:</td>
                        <td><input maxlenght=45 type='text' name='telefono' id='telefono' value=''/></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input maxlenght=100 type='text' name='email' id='email' value=''/></td>
                    </tr>
					<tr>
                        <td>
                                Observaciones<br>(hasta 500 car.) :
                        </td>
                        <td>
                                <textarea maxlenght=500 id='observaciones' name='observaciones' rows=10 cols=30></textarea>
                        </td>
					
                    </tr>
                    </table>
                ";
            return $html;

        }
        
        public static function getBajaProveedorHTML($id) {
            $html = ProveedorView::getModifProveedorHTML($id);
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
                                <td>Proveedor: </td>
                            </tr>
                            <tr>
                                    <td>'.ProveedorView::getComboProveedoresHTML().'</td>
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