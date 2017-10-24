<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$muestroagregar=false;
if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
}
?>
<div style="text-align:left;">
	<div>
    	<label>Barra OGP</label>
        <div style="clear:both">&nbsp;</div>
        <img src="/public/gcba/imagenes/ogp_barra.png" title="Barra OGP"/>
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