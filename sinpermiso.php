<? session_start(); ?>
<?php

    ini_set('error_reporting', E_ALL);
    ini_set( 'display_errors', 1 );
    
    /*
        La funci&oacute;n __autoload se ejecuta autom&aacute;ticamente CADA VEZ que hago
        new de una clase que no est&aacute; en memoria
        PHP me va a pasar como par&aacute;metro el nombre de la clase que se quiere instanciar
    */
    function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }

    $id_permiso = 2;

    if(!isset($_SESSION['usuario'])) {
        header('Location: ./login.php');
    }

    echo PageView::getHeadTag();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<body>

<?php
    echo PageView::getHeaderHTML();
?>
<div class="colmask leftmenu">
	<div class="colleft">
		<div class="col1">
		<?php

			$menuSeleccionado = 'ninguno';

			echo MenuView::getMenuHTML($menuSeleccionado);

			echo "<br>";
                        echo "<br>";
                        echo "Usted no tiene permiso para acceder a la opción seleccionada";




        ?>
		</div>
		<div class="col2">
                <?php
                    // Vacío
                ?>
                  <!-- Column 2 end -->
		</div>
	</div>
</div>
<?php
    echo PageView::getFooterHTML();
?>
</body>
</html>
