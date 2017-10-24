<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$PageFacebook = "https://www.facebook.com/bancoprovincia";
$ShowFaces = 1;
$Stream =0;
$muestroagregar=false;
if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->PageFacebook))
		$PageFacebook = $objDataModel->PageFacebook;
		
	if (isset($objDataModel->ShowFaces))
		$ShowFaces = $objDataModel->ShowFaces;
	
	if (isset($objDataModel->Stream))
		$Stream = $objDataModel->Stream;
}
?>
<div style="text-align:left;">
	<div style="float:left; width:30%">
    	<label>Facebook Page URL:</label>
    </div>
	<div style="float:left; width:20%;">
    	<input type="text" value="<?php  echo $PageFacebook?>" id="PageFacebook" name="PageFacebook" maxlength="150" size="50" />
    </div>
    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:30%">
    	<label>Mostrar caras amigos:</label>
    </div>
	<div style="float:left; width:70%;">
    	<select name="ShowFaces" id="ShowFaces">
        	<option value="1" <?php  if ($ShowFaces==1) echo 'selected="selected"'?> >Si</option>
        	<option value="0" <?php  if ($ShowFaces==0) echo 'selected="selected"'?>>No</option>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:30%">
    	<label>Mostrar &Uacute;ltimos movimientos:</label>
    </div>
	<div style="float:left; width:70%;">
    	<select name="Stream" id="Stream">
        	<option value="0" <?php  if ($Stream==0) echo 'selected="selected"'?>>No</option>
        	<option value="1" <?php  if ($Stream==1) echo 'selected="selected"'?> >Si</option>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
        <div style="clear:both;"></div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="Validar()">Guardar y Cerrar</a>
        	<?php  if (!$muestroagregar) {   ?>              
                <a href="javascript:void(0)" onclick="ValidarAgregar()">Guardar y Agregar otro</a>
          	<?php  } ?>
                
            </li>
        </ul>
    </div>
    </div>
	


<script type="text/javascript">
	function Validar()
	{
		if($("#PageFacebook").val()=='')
		{
			alert("Debe ingresar la URL Page de facebook");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#PageFacebook").val()=='')
		{
			alert("Debe ingresar la URL Page de facebook");
			
		}else{
			agregarModulo();
		}
		return true;
	}	
</script>