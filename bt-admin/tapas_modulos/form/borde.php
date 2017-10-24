<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$MargenDerIzq = 0;
$MargenSupInf = 0;
$muestroagregar=false;

if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->MargenDerIzq))
		$MargenDerIzq = $objDataModel->MargenDerIzq;
	if (isset($objDataModel->MargenSupInf))
		$MargenSupInf = $objDataModel->MargenSupInf;
}
?>
<div style="text-align:left;">
	<div style="float:left; width:30%">
    	<label>Margen Derecho e Izquierdo:</label>
    </div>
	<div style="float:left; width:20%;">
    	<input type="text" value="<?php  echo $MargenDerIzq?>" id="MargenDerIzq" name="MargenDerIzq" maxlength="5" size="8" />px
    </div>
    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:30%">
    	<label>Margen Superior e Inferior:</label>
    </div>
	<div style="float:left; width:70%;">
    	<input type="text" value="<?php  echo $MargenSupInf?>" id="MargenSupInf" name="MargenSupInf" />px
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