<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocalidadView
 *
 * @author german
 */
class LocalidadView {
   /* public static function getComboLocalidadHTML() {
        $idLocalidades = DB::getIdsLocalidades();
        $idProvincias = DB::getIdsProvincias();
    }*/

 public static function getComboLocalidadHTML(){
 // Tres sobrecargas: Sin ning�n par�metro, devuelve Seleccione..., Con el primer par�metro devuelve todas las de una Provincia, Con el segundo par�metro devuelve todas las de una provincia con una seleccionada

        
             
        $html ='<div id="respuestaComboLoc">
				<div class="ui-widget" >
                    <select id="comboboxloc" name="comboLocalidades">';
                        
        $cantArgs= func_num_args();
		switch ($cantArgs){
			case 0:
				$html .='<option selected="selected" value="">Seleccione una provincia...</option>';
				break;
			case 1:	
				$idLocalidades = DB::getIdsLocalidades(intval(func_get_arg(0)));
				foreach( $idLocalidades as $mi_localidad ){
					$l = new Localidad($mi_localidad['id_localidad']);
					$html.='<option value="'.$l->getId().'">'.$l->getDescripcion().'</option>';
				}
				break;	
			case 2: 
			$idLocalidades = DB::getIdsLocalidades(intval(func_get_arg(0)));
			foreach( $idLocalidades as $mi_localidad ){
					$l = new Localidad($mi_localidad['id_localidad']);
					if (func_get_arg(1)==$l->getId()) {
						$html.='<option selected="selected" value="'.$l->getId().'">'.$l->getDescripcion().'</option>';
					} else {
						$html.='<option value="'.$l->getId().'">'.$l->getDescripcion().'</option>';
					}
				}
			break;		
		}
       	
        $html.='    </select>
				</div>
                </div>';
				
        return $html;

        }
}
?>
