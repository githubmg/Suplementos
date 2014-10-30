<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioView
 *
 * @author german
 */
class UsuarioView {
    
    public static function getListadoHTML($parametros) {
        
        $cantidadDePaginas = DB::getCantidadDePaginas('usuario');

        $html = "<table id='ventas' class='tabla'>";
        $html.= self::getTitulos();

        $idsUsuarios = DB::getIdsUsuarios();

        foreach($idsUsuarios as $id) {
            $usuario = new Usuario(intval($id['id_usuario']));
            $html.= self::getHTML($usuario);
        }

        $html.= self::getTableFooter($parametros, $cantidadDePaginas);
        $html.= "</table>";
        return $html;
    }
    
    public static function getTitulos() {
        $html = "<thead class='headerTabla'>";
        $html.= "   <tr>
                        <th scope='col' class='' > Login </th>
                        <th scope='col' class='' > Nombre </th>
                        <th scope='col' class=''> Apellido </th>
                        <th scope='col' class=''> Activo </th>
                        <th scope='col' class=''> Acciones </th>
                    </tr>
                </thead>";
        
        return $html;
    }
    
    public static function getTableFooter($parametros, $cantidadDePaginas){    
        $indicePag = $parametros['p'];
        $href = 'usuarios.php?';

        
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
                        <td colspan=5>
                                <div style='float:left;padding-left:15px'>
                                <a href='javascript:void(0);' onClick='AbrirPopUp(\"ABMUsuario.php?action=alta\"); return false;'><img src='images/tabla/agregar.png'/></a>
                                </div>
                                <div class='paginador' style='float:left;padding-left:280px'>
                                <a href='".$href."p=1' ".$enabledPrimeroyAnterior."><img src='images/tabla/primero.png' alt='Primero' /></a>
                                <a href='".$href."p=".($indicePag-1)."' ".$enabledPrimeroyAnterior."><img src='images/tabla/atras.png' alt='Atr&aacute;;s') /></a>
                                P&aacute;gina ".$indicePag." de ".$cantidadDePaginas."
                                <a href='".$href."p=".($indicePag+1)."' ".$enabledUltimoySiguiente."><img src='images/tabla/adelante.png' alt='Adelante' /></a>
                                <a href='".$href."p=".($cantidadDePaginas)."' ".$enabledUltimoySiguiente."><img src='images/tabla/ultimo.png' alt='&Uacute;ltimo'/></a>
                                </div>";
        $html .= "</td>

                </tr>
        <!-- /tfoot -->";
        return $html; 
    }
    
    public static function getHTML(Usuario $usuario) {
        
        $login = $usuario->getLogin();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $activo = $usuario->getActivo();
        $id = $usuario->getId();
        
        $html = "<tr><td>";
        $html.= $login;
        $html.="</td><td>";
        $html.= $nombre;
        $html.="</td><td>";
        $html.= $apellido;
        $html.="</td><td>";
        if( $activo== 1) {
            $html.= 'S&iacute;';
        } else {
            $html.= 'No';
        }
        $html.="</td><td>";
        $html.="<a href='ABMUsuario.php?action=modificacion&categoria=usuario&id=".$id."' target='_blank' onClick='window.open(this.href, this.target, \"width=500,height=400,top=200,left=400\");return false;' ><img src='images/tabla/editar.png' /></a>
                <a href='ABMUsuario.php?action=baja&categoria=usuario&id=".$id."' target='_blank' onClick='window.open(this.href, this.target, \"width=500,height=400,top=200,left=400\");return false;' ><img src='images/tabla/borrar.png' /></a>
                </td>";
        $html.= "</td></tr>";
        
        return $html;
    }
    
    public static function getAltaUsuarioHTML(){
        $html = "<table>";
        $html.= "<tr><td>Login:</td><td><input type='text' id='login' name='login' /></td></tr>";
        $html.= "<tr><td>Nombre:</td><td><input type='text' id='nombre' name='nombre' /></td></tr>";
        $html.= "<tr><td>Apellido:</td><td><input type='text' id='apellido' name='apellido' /></td></tr>";
        $html.= "<tr><td>Contrase&ntilde;a:</td><td><input type='password' id='pass1' name='pass1' /></td></tr>";
        $html.= "<tr><td>Confirmar Contrase&ntilde;a:</td><td><input type='password' id='pass2' name='pass2' /></td></tr>";
        $html.= "</table>";
        return $html;
    }

    public static function getModifUsuarioHTML($id_usuario){
        if ($id_usuario) {
        $usuario = new Usuario(intval($id_usuario));
        $login = $usuario->getLogin();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $activo = $usuario->getActivo();

        $html = "<script type='text/javascript'> function cambiarContraseña() {
                    habilitado = document.getElementById('cambiarpass').checked;
                    if (habilitado == true) {
                        document.getElementById('pass1').disabled=false;
                        document.getElementById('pass2').disabled=false;
                        document.getElementById('pass1').value='';
                        document.getElementById('pass2').value='';
                        } else {
                        document.getElementById('pass1').disabled=true;
                        document.getElementById('pass2').disabled=true;
                        document.getElementById('pass1').value='';
                        document.getElementById('pass2').value='';
                        }
                    }
                </script>";
        $html.= "<table>";
        $html.= "<tr><td>Login:</td><td><input type='text' id='login' name='login' value='".$login."' /></td></tr>";
        $html.= "<tr><td>Nombre:</td><td><input type='text' id='nombre' name='nombre' value='".$nombre."' /></td></tr>";
        $html.= "<tr><td>Apellido:</td><td><input type='text' id='apellido' name='apellido' value='".$apellido."'/></td></tr>";
        $html.= "<tr><td>Cambiar Contraseña:</td><td><input type='checkbox' onChange='cambiarContraseña()' id='cambiarpass' name='cambiarpass' value='false' /></td></tr>";
        $html.= "<tr><td>Contrase&ntilde;a:</td><td><input type='password' disabled='true' id='pass1' name='pass1' /></td></tr>";
        $html.= "<tr><td>Confirmar Contrase&ntilde;a:</td><td><input disabled='true' type='password' id='pass2' name='pass2' /></td></tr>";
        $html.= "<tr><td>Activo:</td><td><input type='check' id='activo' name='activo' value='".$activo."' /></td></tr>";
        $html.= "</table>";
        $html.= "<input id='id_usuario' name='id_usuario' type='hidden' value='".$id_usuario."' />";

        } else {
            $html = "Error: no se ha indicado el usuario";
        }

        return $html;
    }

    public static function getBajaUsuarioHTML($id_usuario){
        if ($id_usuario) {
        $usuario = new Usuario(intval($id_usuario));
        $login = $usuario->getLogin();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $activo = $usuario->getActivo();

        $html = "<table>";
        $html.= "<tr><td>Login:</td><td><input disabled='true' type='text' id='login' name='login' value='".$login."' /></td></tr>";
        $html.= "<tr><td>Nombre:</td><td><input disabled='true' type='text' id='nombre' name='nombre' value='".$nombre."' /></td></tr>";
        $html.= "<tr><td>Apellido:</td><td><input disabled='true' type='text' id='apellido' name='apellido' value='".$apellido."'/></td></tr>";
        
        $html.= "</table>";
        $html.= "<input id='id_usuario' name='id_usuario' type='hidden' value='".$id_usuario."' />";

        } else {
            $html = "Error: no se ha indicado el usuario";
        }

        return $html;
    }
        
}

?>
