<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$PageFacebook = "";
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
    	<label>Caja de twitter</label>
    </div>
    <div style="clear:both">&nbsp;</div>
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
		saveModulo();
		return true;
	}
	
	function ValidarAgregar()
	{
		agregarModulo();
		return true;
	}	
</script>