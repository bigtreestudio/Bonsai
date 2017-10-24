<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$Link="";
$encuestacod = "";
$encuestapregunta = "";
$oEncuestas = new cEncuestas($vars['conexion']);
$muestroagregar=false;
if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->encuestacod))
	{
		$graficocod = $objDataModel->encuestacod;
		$datosbusqueda['encuestacod'] = $encuestacod;
		if (!$oEncuestas->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
			return false;
		
		$datosencuesta = $vars['conexion']->ObtenerSiguienteRegistro($resultado);	
		$encuestapregunta = $datosencuesta['encuestapregunta'];
		

	}
	
	
}

$datos = array();
$datos["encuestaestado"]=ACTIVO;
if (!$oEncuestas->BusquedaAvanzada ($datos,$resultadoencuestas,$numfilas))
	die();

?>
<div style="min-width:700px;">
    <div style="text-align:left;">
        <h2 class="titulopopup">Listado de Encuestas</h2>
    </div>
    <div style="padding-top:10px">
    <div id="newsSearchContent">
        <div style="float:left;">Descripci&oacute;n:</div> <input type="text" style="float:left;" id="encuestapregunta" name="encuestapregunta" onkeypress="SearchEncuestas()">
        <div style="clear:both">&nbsp;</div>
		<div style="height:200px; overflow-y:auto; margin-top:10px;">
            <table style="width:95%" class="tableseleccion">
            
                     <tr>
                        <th width="20%">C&oacute;digo</th>
                        <th width="60%">Pregunta</th>
                        <th width="20%">&nbsp;</th>
                     </tr> 
                     <tbody id="encuestaLstData">
          <?php   if ($numfilas>0)
            {
                
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoencuestas))
                {
            ?>

                        <tr>
                                <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['encuestacod'],ENT_QUOTES); ?></td>
                                <td style="text-align:left" id="encuestapregunta_<?php  echo $fila['encuestacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['encuestapregunta'],ENT_QUOTES); ?></td>
                                <td style="text-align:center; font-weight:bold;">
                                   <a class="left" href="javascript:void(0)" onclick="AgregarEncuesta(<?php  echo $fila['encuestacod']?>)">Agregar</a>
     
                                </td>
                        </tr> 
            <?php 
                }
            }
            ?>
            		</tbody>            
            </table> 
  	    </div>
    </div>
    </div>
  
    <div style="text-align:left; margin-top:5px;">
        <h2 class="titulopopup">Encuesta Seleccionada:</h2>
        <div id="TituloEncuesta" style="float:left; width:70%; text-align:left; font-weight:bold; padding-top:10px; padding-left:40px"><?php  echo $encuestapregunta?></div>
        <input type="hidden" name="encuestacod" id="encuestacod" value="<?php  echo $encuestacod?>">
        <div style="clear:both">&nbsp;</div>
    </div>
    
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
		if($("#encuestacod").val()=='')
		{
			alert("Debe seleccionar una encuesta");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#encuestacod").val()=='')
		{
			alert("Debe seleccionar una encuesta");
			
		}else{
			agregarModulo();
		}
		return true;
	}	
	
	function AgregarEncuesta(codigo)
	{
		$("#TituloEncuesta").html($("#encuestapregunta_"+codigo).html());
		$("#encuestacod").val(codigo);
	}
	
	var timeoutHnd; 
	function SearchEncuestas(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarEncuestas,500) 
	}
	
	function BuscarEncuestas()
	{
		var param, url;
		$("#encuestaLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando encuestas...</td></div></tr>');
		param = "encuestapregunta="+$("#encuestapregunta").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_encuestas_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#encuestaLstData").html(msg);
		   }
		 });
	}
</script>