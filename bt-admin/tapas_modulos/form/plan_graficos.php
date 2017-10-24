<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$Alto = 0;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->Alto))
		$Alto = $objDataModel->Alto;
}
?>
<div style="text-align:left;">
	<div style="float:left; width:30%">
    	<label>Alto:</label>
    </div>
	<div style="float:left; width:20%;">
    	<input type="text" value="<?php  echo $Alto?>" id="Alto" name="Alto" maxlength="5" size="8" />px
    </div>
    <div style="clear:both">&nbsp;</div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
           </li>
        </ul>
    </div></div>