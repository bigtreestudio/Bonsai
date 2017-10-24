<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$Numero = "";
$Icono = "";
$Texto ="";
$muestroagregar=false;

if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->Numero))
		$Numero = $objDataModel->Numero;

	if (isset($objDataModel->Texto))
		$Texto = $objDataModel->Texto;
		
	if (isset($objDataModel->Icono))
		$Icono = $objDataModel->Icono;

}
?>
<div style="float:left;width:60%;">
	<div>
    	<label>Numero:</label>
   		<div style="clear:both;height:5px;">&nbsp;</div>
    	<input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Numero),ENT_QUOTES)?>" id="Numero" name="Numero" maxlength="10" size="40" />
    </div>
    <div style="clear:both">&nbsp;</div>
	<div>
    	<label>Icono:</label>
    	<select name="Icono" id="Icono">
        	<option value="" <?php  if ($Icono=="") echo 'selected="selected"'?> >Sin icono</option>
           	<option value="tilde" <?php  if ($Icono=="tilde") echo 'selected="selected"'?> >Tilde</option>
            <option value="mas" <?php  if ($Icono=="mas") echo 'selected="selected"'?> >M&aacute;s</option>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
	<div>
    	<label>Texto:</label>
   		 <div style="clear:both;height:5px;">&nbsp;</div>
    	<input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Texto),ENT_QUOTES)?>" id="Texto" name="Texto" maxlength="50" size="40" />
    </div>
    <div style="clear:both">&nbsp;</div>
	
</div>
<div style="float:left;width:40%;">
	<img src="/public/gcba/imagenes/indicador.png" title="Indicador OGP"/>
</div>
<div style="clear:both; height:10px;">&nbsp;</div>
<div class="menucarga" style="text-align:right">
    <ul>
        <li>
            <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            <? if (!$muestroagregar) {   ?>
            <a href="javascript:void(0)" onclick="agregarModulo()">Guardar y Agregar otro</a>
            <? } ?>
        </li>
    </ul>
</div>  
  
 <script type="text/javascript">
   
	function ModificaryCerrar()
	{

		saveModulo()
	}

	function ModificaryAgregar()
	{
		agregarModulo()
	}
</script>