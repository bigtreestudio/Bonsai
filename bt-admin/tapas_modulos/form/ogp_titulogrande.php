<?php  
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$TituloGrande="";
$muestroagregar=false;
if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->TituloGrande))
		$TituloGrande = $objDataModel->Texto;
}
?>
<div style="text-align:left;">
	<div>
    	<label>T&iacute;tilo</label>
        <div style="clear:both">&nbsp;</div>
       <input type="text" style="width:100%;" id="TituloGrande" name="TituloGrande" maxlength="255" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($TituloGrande),ENT_QUOTES)?>" />
        <div style="clear:both">&nbsp;</div>
    </div>

    <div style="clear:both">&nbsp;</div>
     <div style="clear:both;"></div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
        	<?php  if (!$muestroagregar) {   ?>                                              
                <a href="javascript:void(0)" onclick="agregarModulo()">Guardar y Agregar otro</a>
          	<?php  } ?>
			
           </li>
        </ul>
    </div></div>