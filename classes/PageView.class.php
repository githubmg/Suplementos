<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PageView
 *
 * @author german
 */
class PageView {
    public static function getScriptTxtFecha() {
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
						$('#txtFechaVenta').datepicker('setDate', new Date());				
						});
					</script>";
		return $html;			
	}
	public static function getScriptTxtFechaGeneric($txtFechaClientId) {
		$html = "<script type='text/javascript'> 
                    $(document).ready(function() {
						$('#".$txtFechaClientId."').datepicker(
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
						$('#".$txtFechaClientId."').datepicker('setDate', new Date());				
						});
				</script>";
		return $html;			
	}
	
    public static function getHeadTag() {
        $head = '<head>
                    <title>SM Suplementos</title>
                    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
                    <meta name="description" content="Sistema de gesti&oacute;n de productos, compras y ventas." />
                    <meta name="keywords" content="vitaminas, productos" />
                    <meta name="robots" content="index, follow" />
                    <link rel="shortcut icon" href="images/logo/favicon.ico" type="image/x-icon" />
                    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
                    <link rel="stylesheet" type="text/css" href="css/menu.css" media="screen" />
                    <link rel="stylesheet" type="text/css" href="css/distribpantalla.css" media="screen" />
                    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
                    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
                    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
                    <link rel="stylesheet" type="text/css" href="css/comboAutocompletar.css" media="screen" />
                    <script src="js/autocompletar.js"></script>
                    <script src="js/llamadosAjax.js"></script>
                    <script src="js/autocompletar2.js"></script>
                    <script src="js/autocompletar3.js"></script>
                    <script src="js/autocompletar4.js"></script>
                    <script src="js/autocompletar5.js"></script>
					<script src="js/autocompletar6.js"></script>
                    
                    <script type="text/javascript" src="js/util.js"></script>
                    
                     
                    <script type="text/javascript">
                        function AbrirPopUp(url) {


                            window.open(url, "name","width=600,height=600,top=200,left=450, scrollbar=\'yes\'");
                        }
                    </script>  
                    <script type="text/javascript">
			var disabledDaysRange = [["9-1-2000 to 9-6-2016"]];
			function disableRangeOfDays(d) {
				for(var i = 0; i < disabledDaysRange.length; i++) {
					if($.isArray(disabledDaysRange[i])) {
						for(var j = 0; j < disabledDaysRange[i].length; j++) {
							var r = disabledDaysRange[i][j].split(" to ");
							r[0] = r[0].split("-");
							r[1] = r[1].split("-");
							if(new Date(r[0][2], (r[0][0]-1), r[0][1]) <= d && d <= new Date(r[1][2], (r[1][0]-1), r[1][1])) {
								return [true];
							}
						}
					}else';
        $head.="{
						if(((d.getMonth()+1) + '-' + d.getDate() + '-' + d.getFullYear()) == disabledDaysRange[i]) {
							return [true];
						}
					}
				}
				return [false];
			}
		  $(document).ready(function() {
			  $('#txtFecha').datepicker(
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
		  });
		  $(document).ready(function() {
			  $('#txtFecha2').datepicker(
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
					beforeShowDay: disableRangeOfDays
				});         
		  });
		</script>";
        $head.='<script>
                    function agregarCliente(){
                            $("#divAgregarCliente").toggle();
                    }
                    function mostrarAgregarProducto(){
                            $("#divAgregarProducto").toggle();
                    }
                    function mostrarAgregarProveedor(){
                            $("#divAgregarProveedor").toggle();
                    }
					function mostrarLaboratorio(){
                            $("#divAgregarLaboratorio").toggle();
                    }
					
                    $(document).ready(function() {
                    // Handler for .ready() called.
					$( "#comboboxlab" ).toggle();
                    $( "#comboboxcli" ).toggle();
                    $( "#comboboxpro" ).toggle();
                    $( "#comboboxprov" ).toggle();
                    $( "#comboboxloc" ).toggle();
                    $( "#comboboxprove" ).toggle();
                    $( "#divAgregarCliente").toggle();
                    $( "#divAgregarProducto").toggle();
                    $( "#divAgregarProveedor").toggle();
					$( "#divAgregarLaboratorio").toggle();
					
                    });
                               
                    </script>
                </head>';
        
        return $head;
    }
	
    public static function getTableFooter($indicePag,$cantidadDePaginas,$cantidadDeColumnas,$categoria,$href,$parametros){
			$enabledPrimeroyAnterior = "";
			$enabledUltimoySiguiente = "";

			if ($indicePag==1) {
				$enabledPrimeroyAnterior = "class='active'";
			}
			if ($indicePag==$cantidadDePaginas) {
				$enabledUltimoySiguiente = "class='active'";
			}

			$html = "
				<!-- tfoot -->
					<tr class='footerTabla'>
					<td colspan=".$cantidadDeColumnas.">
						<div style='float:left;padding-left:15px'>
						<a href='javascript:void(0);' onClick='AbrirPopUp(\"edicion.php?action=a&categoria=".$categoria."\"); return false;'><img src='images/tabla/agregar.png'/></a>
						</div>
						<div class='paginador' style='float:left;padding-left:280px'>
						<a href='".$href."p=1' ".$enabledPrimeroyAnterior."><img src='images/tabla/primero.png' alt='Primero' /></a>
						<a href='".$href."p=".($indicePag-1)."' ".$enabledPrimeroyAnterior."><img src='images/tabla/atras.png' alt='Atr&aacute;;s') /></a>
						P&aacute;gina ".$indicePag." de ".$cantidadDePaginas."
						<a href='".$href."p=".($indicePag+1)."' ".$enabledUltimoySiguiente."><img src='images/tabla/adelante.png' alt='Adelante' /></a>
						<a href='".$href."p=".($cantidadDePaginas)."' ".$enabledUltimoySiguiente."><img src='images/tabla/ultimo.png' alt='&Uacute;ltimo'/></a>
						</div>";
						$paramGET='?categoria='.$categoria;
						if(isset($parametros['fecha_desde'])) {
							$paramGET=$paramGET.'&fecha_desde='.$parametros['fecha_desde'];
						}
						if(isset($parametros['fecha_hasta'])) {
							$paramGET=$paramGET.'&fecha_hasta='.$parametros['fecha_hasta'];
						}
						if(isset($parametros['comboProductos'])) {
							$paramGET=$paramGET.'&comboProductos='.$parametros['comboProductos'];
						}
						$html .= "<div style='float:left;padding-left:280px'>
									<a href='exportar.php".$paramGET."'><img src=\"images/iconos/excel.png\">
								</div>
								</td>
							
							</tr>
						<!-- /tfoot -->";
    return $html;
    }
    public static function getHeaderHTML() {
        $html ='<div id="header" align="left"> 
                    <img id="logo" src="images/logo/Logo SM 01.png">';
        $html.='</div>';
	return $html;      
    }
	public static function getBuscadorFechasHTML() {
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
								<br />
                                <td  align="center" ><input type="submit" class="botonNegro" value="Buscar"/></td>
                            </tr>
                        </table>
                    </form>';
            return $html;
        }
    public static function getActualizarStockHTML() {
        /*$html = '<img src="images/logo/logo_sysmo.png" />';*/
		$html = '<div id="actualizar" align="center">
					<form id="actualizarCostos" method="POST" >
						<input type="submit" class="botonNegro" value = "Actualizar Costos" name="ActualizarCostos"/>
					</form>
				</div>
				';
		
        return $html;
    }
	
    public static function getFooterHTML() {
        /*$html = '<img src="images/logo/logo_sysmo.png" />';*/
		$html = '<div id="footer">.
	
				</div>
				';
		
        return $html;
    }
	public static function getExportarAExcel($param){
		$html = '<img src="images/iconos/excel.png">';
		
        return $html;
	}
        
}

?>
