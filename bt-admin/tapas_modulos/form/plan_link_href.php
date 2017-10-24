<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$IdLink = "";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->IdLink))
		$IdLink  = $objDataModel->IdLink;
}
?>
<div style="text-align:left;">
	<div style="float:left; width:30%">
    	<label>Id Link:</label>
    </div>
	<div style="float:left; width:60%;">
    	<input type="text" value="<?php  echo $IdLink?>" id="IdLink" name="IdLink" maxlength="255" size="50"/>
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