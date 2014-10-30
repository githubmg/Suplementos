<?php session_start(); ?>
<?php

        ini_set('error_reporting', E_ALL);
        ini_set( 'display_errors', 1 );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Sistema</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="description" content="Sistema de venta de productos." />
	<meta name="keywords" content="vitaminas productos" />
	<meta name="robots" content="index, follow" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/menu.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/distribpantalla.css" media="screen" />

</head>
    <body>
		<h1 style="text-align:center;">Sitio de test</h1>
        <?php


        if(isset($_GET['logout'])||isset($_GET['error'])) {
            unset($_SESSION);
        }

        function __autoload( $className ){
                require_once 'classes/'.$className.'.class.php';
            }

        echo PageView::getHeaderHTML();

        ?>
        <div id="formulario" class="colmask leftmenu">
            <form action="classes/UsuarioController.class.php" method="post" >
                <input type="hidden" value ="login" id="action" name ="action" />
                <table align="center">
                    <thead>
                        <tr>
                            <td colspan="3" align="center">Ingreso al Sistema</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Usuario: </td><td><input type="text" id="usuario" name="usuario" /></td>
                        </tr>
                        <tr>
                            <td>Contrase&ntilde;a: </td><td><input type="password" id="pass" name="pass" /></td>
                        </tr>
                        <?php
                        if (isset($_GET['error'])) {
                            $errorMessage = '<tr>
                                <td colspan="2">Error! El usuario y/o la contrase&ntilde;a son incorrectos </td>
                            </tr>';
                            echo $errorMessage;
                        }
                        ?>
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <td align="center" colspan="3"><input type="submit" value="Ingresar"/></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>

    </body>
