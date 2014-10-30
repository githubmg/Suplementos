<?php
	
    class LaboratorioView {
        public static function getComboLaboratorioHTML(){

        $laboratorios = DB::getIdsLab();
        $html ='
                <div class="ui-widget">
                    <select id="comboboxlab" name="comboLaboratorios">
                        <option value="">Seleccione...</option>';

        $cantArgs= func_num_args();
        if ($cantArgs==1) {
            foreach( $laboratorios as $mi_lab ){
                $c = new Laboratorio($mi_lab['id_laboratorio']);
                if (func_get_arg(0)==$c->getId()) {
                    $html.='<option selected="selected" value="'.$c->getId().'">'.$c->getDescripcion().'</option>';
                } else {
                    $html.='<option value="'.$c->getId().'">'.$c->getDescripcion().'</option>';
                }
            }
        } else {
            foreach( $laboratorios as $mi_lab ){
                $c = new Laboratorio(intval($mi_lab['id_laboratorio']));
                $html.='<option value="'.$c->getId().'">'.$c->getDescripcion().'</option>';
            }
        }

        $html.='    </select>
                </div>';
        return $html;

        }
		public static function getAltaLaboratorioEmbebidoHTML(){
                $html= "<div id='divAgregarLaboratorio'><table class='embebidoOculto'><tr><td>".self::getAltaLaboratorioHTML()."</td></tr>
                                <tr><td align='center'><input type='button' class='botonNegro' name ='addLab' value ='Agregar Laboratorio' onclick='agregarLaboratio();'></input></td></tr>
                                </table></div>";
                return $html;				
        }
        public static function getAltaLaboratorioHTML(){
  
            $html = "<table>
                    <tr>
                        <td>Laboratorio:</td>
                        <td>
                            <input type='text' name='laboratorio' id='laboratorio' value=''/>
                        </td>
                    </tr>
                    </table>
                ";
            return $html;

        }
      
    }