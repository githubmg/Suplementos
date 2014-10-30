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
class ProvinciaView {
   /* public static function getComboLocalidadHTML() {
        $idLocalidades = DB::getIdsLocalidades();
        $idProvincias = DB::getIdsProvincias();
    }*/

 public static function getComboProvinciaHTML(){

        $idProvincias = DB::getIdsProvincias();
             
        $html ='
                <div class="ui-widget">
                    <select id="comboboxprov" name="comboProvincias">
                        <option value="">Seleccione...</option>';

        $cantArgs= func_num_args();
        if ($cantArgs==1 && func_get_arg(0) != null) {
            foreach( $idProvincias as $mi_provincia ){
                $p = new Provincia($mi_provincia['id_provincia']);
                if (func_get_arg(0)==$p->getId()) {
                    $html.='<option selected="selected" value="'.$p->getId().'">'.$p->getDescripcion().'</option>';
                } else {
                    $html.='<option value="'.$p->getId().'">'.$p->getDescripcion().'</option>';
                }
            }
        } else {
            foreach( $idProvincias as $mi_provincia ){
                $p = new Provincia($mi_provincia['id_provincia']);
                $html.='<option value="'.$p->getId().'">'.$p->getDescripcion().'</option>';
            }
        }

        $html.='    </select>
                </div>';
        return $html;

        }
}
?>
