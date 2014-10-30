<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function __autoload( $className ){
        require_once 'classes/'.$className.'.class.php';
    }


echo PageView::getHeadTag();

?>


<?php
    $usuario = new Usuario(1);
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo $usuario->validarPermiso(8);
?>