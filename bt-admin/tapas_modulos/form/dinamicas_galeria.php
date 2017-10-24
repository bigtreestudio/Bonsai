<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
		
		
}

?>
<div style="text-align:left;">
    <div >
    	<label>M&oacute;dulo de galeria (muestra la primer galeria del listado de galerias)</label>
    </div>    
    
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            </li>
        </ul>
    </div>
</div>