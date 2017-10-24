<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$Link="";
$graficocod = "";
$graficotitulo = "";
$oGraficos = new cGraficos($vars['conexion']);
$muestroagregar=false;
if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->graficocod))
	{
		$graficocod = $objDataModel->graficocod;
		$datosbusqueda['graficocod'] = $graficocod;
		if (!$oGraficos->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
			return false;
		
		$datosgrafico = $vars['conexion']->ObtenerSiguienteRegistro($resultado);	
		$graficotitulo = $datosgrafico['graficotitulo'];
		

	}
	
	
}

$datos = array();
if (!$oGraficos->BusquedaAvanzada ($datos,$resultadograficos,$numfilas))
	die();

?>
<div style="min-width:700px;">
    <div style="text-align:left;">
        <h2 class="titulopopup">Listado de Graficos</h2>
    </div>
    <div style="padding-top:10px">
    <div id="newsSearchContent">
        <div style="float:left;">Descripci&oacute;n:</div> <input type="text" style="float:left;" id="graficodesc" name="graficodesc" onkeypress="SearchGraficos()">
        <div style="clear:both">&nbsp;</div>
		<div style="height:200px; overflow-y:auto; margin-top:10px;">
            <table style="width:95%" class="tableseleccion">
            
                     <tr>
                        <th width="20%">C&oacute;digo</th>
                        <th width="60%">Titulo</th>
                        <th width="20%">&nbsp;</th>
                     </tr> 
                     <tbody id="graficoLstData">
          <?php   if ($numfilas>0)
            {
                
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadograficos))
                {
            ?>
                        <tr>
                                <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['graficocod'],ENT_QUOTES); ?></td>
                                <td style="text-align:left" id="graficotitulo_<?php  echo $fila['graficocod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['graficotitulo'],ENT_QUOTES); ?></td>
                                <td style="text-align:center; font-weight:bold;">
                                   <a class="left" href="javascript:void(0)" onclick="AgregarGrafico(<?php  echo $fila['graficocod']?>)">Agregar</a>
     
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
        <h2 class="titulopopup">Grafico Seleccionado:</h2>
        <div id="TituloGrafico" style="float:left; width:70%; text-align:left; font-weight:bold; padding-top:10px; padding-left:40px"><?php  echo $graficotitulo?></div>
        <input type="hidden" name="graficocod" id="graficocod" value="<?php  echo $graficocod?>">
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
		if($("#graficocod").val()=='')
		{
			alert("Debe seleccionar un grafico");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#graficocod").val()=='')
		{
			alert("Debe seleccionar un grafico");
			
		}else{
			agregarModulo();
		}
		return true;
	}	
	
	function AgregarGrafico(codigo)
	{
		$("#TituloGrafico").html($("#graficotitulo_"+codigo).html());
		$("#graficocod").val(codigo);
	}
	
	var timeoutHnd; 
	function SearchGraficos(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarGraficos,500) 
	}
	
	function BuscarGraficos()
	{
		var param, url;
		$("#graficoLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando graficos...</td></div></tr>');
		param = "graficotitulo="+$("#graficodesc").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_graficos_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#graficoLstData").html(msg);
		   }
		 });
	}
</script>