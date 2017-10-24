<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
}
?>
<div style="text-align:left;">
	<div style="float:left; width:30%">
    	<label>M&oacute;dulo de tags de los planes de proyecto</label>
    </div>
    <div style="clear:both">&nbsp;</div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
           </li>
        </ul>
    </div></div>