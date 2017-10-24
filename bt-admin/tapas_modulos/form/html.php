<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$Html = "";

if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->Html))
		$Html = utf8_decode($objDataModel->Html);

}
?>

<div style="text-align:left;">
	<div>
    	<label>HTML:</label>
    </div>
	<div>
    	<textarea name="Html" id="Html"  class="textarea full" style="width:95%;" cols="80" rows="10"><?php  echo $Html?></textarea>
    </div>
     
     <div style="clear:both">&nbsp;</div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            </li>
        </ul>
    </div> 
</div>