<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$oBanners = new cBanners($vars['conexion']);
$bannercod="";
$bannerdesc = "";

$muestroagregar=false;

if (isset($vars['pagmodulocod']) || isset($vars['catmodulocod']))
{	
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->bannercod))
	{
		$bannercod = $objDataModel->bannercod;
		$datosbusqueda['bannercod'] = $bannercod;
		if(!$oBanners->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
			return false;
		
		$datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
		$bannerdesc = $datosbanner['bannerdesc'];
	}
}

$datos['limit'] = "LIMIT 0,20";
if(!$oBanners->BusquedaAvanzada($datos,$resultadobanners,$numfilas)) 
	$error = true;

?>
<link type="text/css" rel="stylesheet" href="modulos/tap_tapas/css/estilos.css" />
<div style="min-width:700px;">
        <div style="text-align:left;">
            <h2 class="titulopopup">Listado de Banners</h2>
        </div>
    <div style="padding-top:10px">

    <div id="newsSearchContent">
        <div style="float:left;">Descripci&oacute;n:</div> <input type="text" style="float:left;" id="bannerdescbannerSimple" name="bannerdescbannerSimple" onkeypress="SearchBanners()">      
        <div style="clear:both">&nbsp;</div>
		<div style="height:200px; overflow-y:auto; margin-top:10px;">
            <table style="width:95%" class="tableseleccion">
            
                     <tr>
                        <th width="20%">C&oacute;digo</th>
                        <th width="60%">Titulo</th>
                        <th width="20%">&nbsp;</th>
                     </tr> 
                     <tbody id="bannerLstData">
          <?php   if ($numfilas>0)
            {
                
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadobanners))
                {
            ?>
                        <tr>
                                <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannercod'],ENT_QUOTES); ?></td>
                                <td style="text-align:left" id="bannerdesc_<?php  echo $fila['bannercod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannerdesc'],ENT_QUOTES); ?></td>
                                <td style="text-align:center; font-weight:bold;">
                                   <a class="left" href="javascript:void(0)" onclick="AgregarBanner(<?php  echo $fila['bannercod']?>)">Agregar</a>
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

        
    <div style="text-align:left; margin-top:5px;">
        <h2 class="titulopopup">Banner Seleccionado:</h2>
        <div id="TituloBanner" style="float:left; width:70%; text-align:left; font-weight:bold; padding-top:10px; padding-left:40px"><?php  echo $bannerdesc?></div>
        <div style="clear:both">&nbsp;</div>
		<input type="hidden" name="bannercod" id="bannercod" value="<?php  echo $bannercod?>">
    </div>

        <div style="clear:both;"></div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="Validar()">Guardar y Cerrar</a>
               <?php  if (!$muestroagregar) {   ?>
               	 	 <a href="javascript:void(0)" onclick="ValidarAgregar()">Guardar y Agregar otro</a>
            	<?php  }?>
            </li>
        </ul>
    </div>
    <div id="separator"></div>
    </div>
</div>


<script type="text/javascript">
	function Validar()
	{
		if($("#bannercod").val()=='')
		{
			alert("Debe seleccionar un banner");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#bannercod").val()=='')
		{
			alert("Debe seleccionar un banner");
			
		}else{
			agregarModulo();
		}
		return true;
	}

	function AgregarBanner(codigo)
	{
		$("#TituloBanner").html($("#bannerdesc_"+codigo).html());
		$("#bannercod").val(codigo);
	}
	
	var timeoutHnd; 
	function SearchBanners(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarBanner,500) 
	}
	
	function BuscarBanner()
	{
		var param, url;
		$("#bannerLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando noticias...</td></div></tr>');
		param = "bannerdesc="+$("#bannerdescbannerSimple").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_banners_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#bannerLstData").html(msg);
		   }
		 });
	}
</script>