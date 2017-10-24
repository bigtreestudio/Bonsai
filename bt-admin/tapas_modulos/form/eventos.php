<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$CantidadEventos = 5;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->CantidadEventos))
		$CantidadEventos = $objDataModel->CantidadEventos;
}
?>
<div style="text-align:left;">
	<div style="float:left; width:30%">
    	<label>Cantidad Eventos:</label>
    </div>
	<div style="float:left; width:20%;">
    	<select name="CantidadEventos" id="CantidadEventos">
		<?php  for($i=1;$i<20;$i++){?>
        	<option value="<?php  echo $i?>" <?php  if ($i==$CantidadEventos) echo 'selected="selected"'?>><?php  echo $i?></option>
        <?php  }?>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            </li>
        </ul>
    </div></div>