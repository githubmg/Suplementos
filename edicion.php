<?php session_start(); ?>

<?php
ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );

    /*
        La funci�n __autoload se ejecuta autom�ticamente CADA VEZ que hago
        new de una clase que no est� en memoria
        PHP me va a pasar como par�metro el nombre de la clase que se quiere instanciar
    */
    function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';      
    }

    if(!isset($_SESSION['usuario'])) {
        header('Location: ./login.php');
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">



<?php 
    echo PageView::getHeadTag();
?>




    <body>
        <div>
            <div style="overflow: auto; height: 600px">
		<?php
		
        if( isset($_GET['action'] ) && isset($_GET['categoria'] )){
			$accion = $_GET['action'];
			$categoria = $_GET['categoria'];
		} else {
                        
			$accion = 'a';
			$categoria = 'venta';
		}
		if ( isset($_GET['id'])) {
			$id = $_GET['id'];
		}
                function getParametros(){
                    $params = "?action=".$_GET['action']."&categoria=".$_GET['categoria'];
                    if ( isset($_GET['id'])) {
			$params .= "&id=".$_GET['id'];
                    }
                    return $params;
                }
		?>		
		<form method="post" action="classes/edicionController.class.php<?php echo getParametros();?>">	
		<?php	
			switch ($accion) {
                            case 'a':
                                    /* Alta */
                                    echo ActualizacionView::getFormAltaHTML($categoria);
                                    break;
                            case 'm':
                                    /* Modificacion */
                                    echo ActualizacionView::getFormModifHTML($categoria,$id);
                                    break;
                            case 'b':
                                    /* Borrado */
                                    echo ActualizacionView::getFormBajaHTML($categoria, $id);
                                    break;
                        }
							
                ?>
                    <input type="hidden" name="action" value="<?php echo $accion ?>" />
                    <input type="hidden" name="categoria" value="<?php echo $categoria ?>" />
					 <?php
					 if ($accion != 'b'){
						echo '<input type="submit" name="Grabar" value="Guardar cambios"class="botonNegro"/> ';
					}  else {
						echo '<input type="submit" name="aceptarB" value="Aceptar"class="botonNegro"/>'; 
					}
					?>
					 <input type="button" name="Cancelar" value="Cancelar" class="botonNegro" onClick="borrarItemsSesionAjax();window.close()" />
                    <?php 
                        if (isset($id)) {echo  '<input type="hidden" name="id" value="'.$id.'"/>';}
                        if (isset($accion)) {echo  '<input type="hidden" name="accion" value="'.$accion.'"/>';} 
                    ?>
		</form>
            </div>
        </div>
    </body>
</html>
