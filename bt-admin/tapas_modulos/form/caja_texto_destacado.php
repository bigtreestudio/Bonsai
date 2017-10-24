<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$Texto ="";
$FondoCaja = "naranja";

if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->Texto))
		$Texto = $objDataModel->Texto;
	if (isset($objDataModel->FondoCaja))
		$FondoCaja = $objDataModel->FondoCaja;
}
?>



<div style="text-align:left;">
        <div style="float:left; width:10%">
            <label>Texto:</label>
        </div>
        <div style="float:left; width:15%;">
            <select name="FondoCaja" id="FondoCaja">
                <option value="naranja" <?php  if ($FondoCaja=="naranja") echo 'selected="selected"'?> >Naranja</option>
                <option value="verde" <?php  if ($FondoCaja=="verde") echo 'selected="selected"'?>>Verde</option>
            </select>
        </div>
        <div style="clear:both">&nbsp;</div>
        <div style="float:left; width:10%">
            <label>Texto:</label>
        </div>
        <div style="float:left; width:75%;">
            <textarea name="Texto" id="Texto"  class="textarea full rich-text" style="width:95%;" cols="30" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Texto),ENT_QUOTES)?></textarea>
        </div>
        <div style="clear:both">&nbsp;</div>
        <div class="menucarga" style="text-align:right">
            <ul>
                <li>
                    <a href="javascript:void(0)" onclick="ModificaryCerrar()">Guardar y Cerrar</a>
                <?php  if (!$muestroagregar) {   ?>                                                            
                    <a href="javascript:void(0)" onclick="ModificaryAgregar()">Guardar y Agregar otro</a>
                 <?php  } ?>
                </li>
            </ul>
        </div> 
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