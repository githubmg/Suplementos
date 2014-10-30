<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompraView
 *
 * @author ggrin
 */
class CompraView {
    
    /* Así era antes de mostrar los items en el listado, cuando se mostraban las compras 
    
    public static function getListadoHTML( $todasDePagina, $cantidadDePaginas, $parametros){
        $html = "<table id='compras' class='tabla'>";
        $html .= CompraView::getTitulos();
        $i=0;
        foreach( $todasDePagina as $compra ){

            $i++;
            $c = new Compra(intval($compra['id_compra']));
            $html .= CompraView::getHTML( $c ,$i);

        }

        
        $html .= CompraView::getTableFooter($parametros, $cantidadDePaginas);
        $html .="</table>";

        return $html;
    }
    */
    
    public static function getListadoHTML( $todasDePagina, $cantidadDePaginas, $parametros){
        $html = "<table id='compras' class='tabla'>";
        $html .= CompraView::getTitulos($parametros,$cantidadDePaginas );
        $i=0;
        foreach( $todasDePagina as $item_compra ){

            $i++;
            $item = new ItemCompra(intval($item_compra['id_item_compra']));
            $html .= CompraView::getHTML( $item ,$i);

        }

        
        $html .= CompraView::getTableFooter($parametros, $cantidadDePaginas);
        $html .="</table>";

        return $html;
    }

    public static function getTitulos($parametros,$cantidadDePaginas){
        //T&iacute;tulos de la clase
        $html = "
        <thead class='headerTablaCompras'>
        ";
        $html.= CompraView::getTableFooter($parametros, $cantidadDePaginas);
        $html.="
            <tr>
                <th scope='col' > Nro. Compra </th>
                <th scope='col' > Proveedor </th>
                <th scope='col' class='hidden'> Id_Proveedor </th>
                <th scope='col' > Subempresa </th>
                <th scope='col' > Producto </th>
                <th scope='col' class='hidden'> Id Producto </th>
                <th scope='col' class='colAngosta' > Cantidad </th>
                <th scope='col' class='colAngosta' > Precio Unitario </th>
                <th scope='col'> Monto </th>
                <th scope='col'> Total Compra </th>
                <th scope='col'> Stock Disponible </th>
                <th scope='col' class='colAngosta'> Laboratorio </th>
                <th scope='col'> Fecha </th>
                <th scope='col'> Acciones </th>
            </tr>
        </thead>
        ";
        return $html;
    }

    /* Así era antes de mostrar los items en el listado, cuando se mostraban las compras
    public static function getHTML( Compra $c,$i ){
        //primero mapeo los valores
        $id_compra = $c->getId();
        $proveedor = $c->getProveedor()->getPersona()-> getNombre();
        $id_proveedor = $c->getProveedor()->getId();
        $producto = $c->getProducto()->getDescripcion();
        $id_producto = $c->getProducto()->getId();
        $cantidad = $c->getCantidad();
        $costo_unitario = $c->getCosto_unitario();
        $stock_disponible = $c->getProducto()->getStockDisponible();
        $laboratorioOBJ = $c->getProducto()->getLaboratorio();
        if (isset($laboratorio)){
            $laboratorio = $laboratorioOBJ->getDescripcion();
        }else{
            $laboratorio = "";
        }
        
        
        $fecha = $c->getFecha();
        //despues los clavo en un String
        $clase = CompraView::traerClase($i);
        $html = "
            <tr class='$clase'>
                <td>$proveedor</td>
                <td class='hidden'>$id_proveedor</td>
                <td>$producto</td>
                <td class='hidden'>$id_producto</td>
                <td>$cantidad</td>
                <td>$costo_unitario</td>
                <td>$stock_disponible</td>
                <td>$laboratorio</td>
                <td>$fecha</td>
                <td>
                    <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=m&categoria=compra&id=".$id_compra."\"); return false;' ><img src='images/tabla/editar.png' /></a>
                    <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=b&categoria=compra&id=".$id_compra."\"); return false;' ><img src='images/tabla/borrar.png' /></a>
                </td>
            </tr>
        ";
        return $html;
    }
    */
    
    public static function getHTML( ItemCompra $item,$i ){
        //primero mapeo los valores
        $id_item_compra = $item->getId();
        $compra = $item->getCompra();
        $subempresa = $item->getSubempresa();
        $id_compra = $compra->getId();
        $proveedor = $compra->getProveedor()->getPersona()-> getNombre();
        $id_proveedor = $compra->getProveedor()->getId();
        $producto = $item->getProducto()->getDescripcion();
        $id_producto = $item->getProducto()->getId();
        $cantidad = $item->getCantidad();
        $costo_total = $item->getMontoTotal();
        $precio_unitario = $item->getPrecioUnitario();
        $stock_disponible = $item->getProducto()->getStockDisponible();
        $laboratorioOBJ = $item->getProducto()->getLaboratorio();
        if (isset($laboratorioOBJ)){
                $laboratorio = $laboratorioOBJ->getDescripcion();
        }else{
                $laboratorio = "";
        }
        $monto_total = round ($compra->getTotal(),2);
        
        $fecha = StringController::formatearFecha($compra->getFecha());
        //despues los clavo en un String
        $clase = CompraView::traerClase($i);
        $html = "
            <tr class='$clase'>
                <td>$id_compra</td>
                <td>$proveedor</td>
                <td class='hidden'>$id_proveedor</td>
                <td>$subempresa</td>
                <td>$producto</td>
                <td class='hidden'>$id_producto</td>
                <td>$cantidad</td>
                <td>$precio_unitario</td>
                <td>$costo_total</td>
                <td>$monto_total</td>
                <td>$stock_disponible</td>
                <td>$laboratorio</td>
                <td>$fecha</td>
                <td>
                    <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=m&categoria=compra&id=".$id_item_compra."\"); return false;' ><img src='images/tabla/editar.png' /></a>
                    <a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=b&categoria=compra&id=".$id_item_compra."\"); return false;' ><img src='images/tabla/borrar.png' /></a>
                </td>
            </tr>
        ";
        return $html;
    }
    
    public static function getBuscadorComprasHTML() {
            $html = '<form id="buscador" method="GET" >
                        <table>
                            <tr>
                               <td> <br /></td>
                            </tr>
                            <tr>
                                <td>Nro Compra:</td>
                            </tr>
                            <tr>
                                <td align="center"><input id="id_compra" type="text" name="id_compra"/></td>

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
                                <td>Proveedor: </td>
                            </tr>
                            <tr>    
                                <td>'.ProveedorView::getComboProveedoresHTML().'</td>
                            </tr>
                            <tr>    
                                <td>Producto: </td>
                            </tr>
                            <tr>    
                                <td>'.ProductoView::getComboProductosHTML().'</td>
                            <tr>
                                <td align="center"> <input type="checkbox" name="subempresa" value="SM">SM </td>
                            </tr>
                            <tr>
                                <br />
                                <td  align="center" ><input type="submit" class="botonNegro" value="Buscar"/></td>
                            </tr>
                        </table>
                    </form>';
            return $html;
        }

    public static function getTableFooter($parametros,$cantidadDePaginas){
    $enabledPrimeroyAnterior = "";
    $enabledUltimoySiguiente = "";

    $indicePag = $parametros['p'];
    $href='compras.php?';
    if(isset($parametros['fecha_desde'])) {
        $href=$href.'fecha_desde='.urlencode($parametros['fecha_desde']).'&';
    }

    if(isset($parametros['fecha_hasta'])) {
        $href=$href.'fecha_hasta='.urlencode($parametros['fecha_hasta']).'&';
    }

    if(isset($parametros['comboProveedores'])) {
        $href=$href.'comboProveedores='.$parametros['comboProveedores'].'&';
    }

    if(isset($parametros['comboProductos'])) {
        $href=$href.'comboProductos='.$parametros['comboProductos'].'&';
    }
    if(isset($parametros['id_compra'])) {
        $href=$href.'id_compra='.$parametros['id_compra'].'&';
    }

    if ($indicePag==1) {
        $enabledPrimeroyAnterior = "class='active'";
    }
    if ($indicePag==$cantidadDePaginas) {
        $enabledUltimoySiguiente = "class='active'";
    }

        return str_replace("footerTabla", "footerTablaCompras", PageView::getTableFooter($indicePag,$cantidadDePaginas,12,'compra',$href,$parametros));
    }

    public static function traerClase($i ){
        if ($i % 2 == 0) {
            return 'oddCompras';
        } else {
            return 'evenCompras';
        }
    }
    
    public static function getAltaCompraHTML(){
  
        $html = PageView::getScriptTxtFecha()."<br />
        <script>     
                                window.onbeforeunload = borrarItemsSesionAjax();
                                
                                function verificarPrecioCantidad(campo, texto) {
                                    validarNumero(campo, texto);
                                    precio = document.getElementById('precio');
                                    cantidad = document.getElementById('cantidad');
                                    monto = document.getElementById('monto');
                                    if(!isNaN(precio.value) && !isNaN(cantidad.value)) {
                                        monto.value = precio.value*cantidad.value;
                                    }
                                }
                                
                </script>
        <table>
                        <tr>
                            <td>Fecha:</td>
                            <td><input type='text' id='txtFechaVenta' name='fecha_venta'></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Proveedor:</td>
                            <td>".ProveedorView::getComboProveedoresHTML()."</td>
                            <td><a href='#' id='lnkAgregarProveedor' onclick='mostrarAgregarProveedor();' >Agregar Proveedor </a></td>      
                        </tr>
                        <tr>
                            <td> </td>
                            <td colspan='2'>".ProveedorView::getAltaProveedorEmbebidoHTML()."

                            </td>
                        </tr>
                        <tr>
                            <td> Agregar Items: </td>
                            <td colspan='2'>
                                <table style='border: 1px solid #333333;'>   
                                    <tr>
                                        <td>Producto:</td>
                                        
                                        <td>
                                        <div id='respuestaProducto'>".ProductoView::getComboProductosHTML()."</div></td>
                                        <td> 
                                                    <a href='#' id='lnkAgregarProducto' onclick='mostrarAgregarProducto();' >Agregar Producto </a>
                                        </td>   
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td colspan='2'>".ProductoView::getAltaProductoEmbebidoHTML()."</td>

                                    <tr>
                                    <tr>
                                       <td>Subempresa:</td>
                                        <td> <input type='checkbox' id='subempresaForm' name='subempresaForm' value='SM'>SM </td>
                                        <td> 
                                        </td>   
                                    </tr>
                                    <tr>
                                        <td>Stock Disponible:</td>
                                        <td><div id='respuesta'></div></td>
                                        <td></td>   
                                    </tr>
                                    <tr>
                                        <td >Precio Unitario :</td>
                                        <td><input maxlength=10 onChange='verificarPrecioCantidad(precio, \"El campo Precio Unitario  debe ser numérico\")' width = '15px' type='text' name='precio' id='precio' value='0'/> </div></td>
                                        <td> </td>  
                                    </tr>
                                    <tr>
                                        <td>Cantidad:</td>
                                        <td><input maxlength=10 onChange='verificarPrecioCantidad(cantidad, \"El campo Cantidad debe ser numérico\")' width = '15px' type='text' name='cantidad' id='cantidad' value='0'/></td>
                                        <td> </td>  
                                    </tr>
                                    <tr>
                                        <td >Monto:</td>
                                        <td><input maxlength=10 onChange='validarNumero(monto, \"El campo Monto  debe ser numérico\")' width = '15px' type='text' name='monto' id='monto' value='0'/> </div></td>
                                        <td> </td>  
                                    </tr>
                                    <tr>
                                        <td colspan = '3'><a href='#' id='lnkAgregaItem' onclick='agregarItemCompra();' > Agregar Item </a></td>
                                    </tr>       
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan = '3'>Items de la Compra:</td>
                        </tr>
                        <tr>
                            <td colspan = '3'>
                                <div id='tablaItems'></div>
                            </td>
                        </tr>
                   </table>
                    
                   
            ";
        return $html;
    }
    
    public static function getModifCompraHTML($id){
        
            $item = new ItemCompra($id);
            $compra = $item ->getCompra();
            $fecha = $compra->getFecha();

            $sm = '';
            if ($item ->getSubempresa() == 'SM'){
                $sm = 'checked';    
            }
            $html = "<script type='text/javascript'> 
                function verificarPrecioCantidad(campo, texto) {
                                    validarNumero(campo, texto);
                                    precio = document.getElementById('precio');
                                    cantidad = document.getElementById('cantidad');
                                    monto = document.getElementById('monto');
                                    if(!isNaN(precio.value) && !isNaN(cantidad.value)) {
                                        monto.value = precio.value*cantidad.value;
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
                                                    <td >Precio Unitario :</td>
                                                    <td><input maxlength=10 onChange='verificarPrecioCantidad(precio, \"El campo Precio Unitario  debe ser numérico\")' width = '15px' type='text' name='precio' id='precio' value='".$item->getPrecioUnitario()."'/> </div></td>
                                                    <td> </td>  
                        </tr>
                        <tr>
                            <td height = '50px'>Subempresa:</td>
                            <td><input type='checkbox' id='subempresaForm' name='subempresaForm' value='SM' $sm >SM </td>
                                            
                            <td> 
                            </td>   
                        </tr>
                        <tr>
                            <td >Cantidad:</td>
                            <td><input maxlength=10 onChange='verificarPrecioCantidad(cantidad, \"El campo Cantidad debe ser numérico\")' width = '15px' type='text' name='cantidad' id='cantidad' value='".$item->getCantidad()."'/></td>
                            <td>                            </td>   
                        </tr>
                                                <tr>
                            <td >Monto:</td>
                            <td><input maxlength=10 onChange='validarNumero(monto, \"El campo Monto debe ser numérico\")' width = '15px' type='text' name='monto' id='monto' value='".$item->getMontoTotal()."''/> </div></td>
                            <td> 
                            </td>   
                        </tr>
                        <tr>
                        <tr>
                        <td colspan='3'>
                        <table style='border: 1px solid #333333;'>
                            <tr>
                            <td colspan='3' align='center'>
                                <u> Modificar Compra </u>
                            </td>                   
                            </tr>
                            <tr>
                                <td>Proveedor:</td>
                                <td>".  ProveedorView::getComboProveedoresHTML($compra->getProveedor()->getId())."</td>
                                <td> 
                                        <a href='#' id='lnkAgregarCliente' onclick='mostrarAgregarProveedor();' >Agregar Proveedor </a>
                                </td>       
                            </tr>
                            <tr>
                                <td> </td>
                                <td colspan='2'>".ProveedorView::getAltaProveedorEmbebidoHTML()."

                                </td>

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
                ";
            return $html;
        }

      
    public static function getBajaCompraHTML($id){
        
        $html = CompraView::getModifCompraHTML($id);
        $html.= "<script type='text/javascript'>desactivar('precio'); desactivar('comboProveedor'); desactivar('cantidad');</script>";
        return $html;
    }
}

?>