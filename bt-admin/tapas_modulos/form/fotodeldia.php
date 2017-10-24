<?php  
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$oMultimedia = new cMultimedia($vars['conexion'],"");

$fotodiacod="";
$fotodeldiatitulo=utf8_decode("Foto del día");
$oFotosDia = new cFotosDia($conexion);
$existefoto=false;
$fotodiatitulo = "";
if (isset($vars['zonamodulocod']))
{
	$existefoto=true;
	$objDataModel = json_decode($vars['modulodata']);
	$fotodiacod = $objDataModel->fotodiacod;
	$fotodeldiatitulo=utf8_decode($objDataModel->fotodeldiatitulo);
	
	$oFotosDia = new cFotosDia($vars['conexion']);
	$datosbusqueda['fotodiacod'] = $fotodiacod;
	if (!$oFotosDia->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
		return false;
		
	//guardo datos del multimedia elegido
	$fotoDia =  $vars['conexion']->ObtenerSiguienteRegistro($resultado);
	$imagenFotoDia = $oMultimedia->DevolverDireccionImg($fotoDia);
	$fotodiatitulo =  FuncionesPHPLocal::HtmlspecialcharsBigtree($fotoDia['fotodiatitulo']);
}

//busco fotos de la galeria
$datos['fotodiaestado'] = ACTIVO;
$datos['limit'] = "LIMIT 0,20";
if(!$oFotosDia->BusquedaAvanzada($datos,$resultado,$numfilas)) {
	$error = true;
}

?>
<div style="min-width:700px;">
    <div style="text-align:left;">
            <h2 class="titulopopup">Foto del d&iacute;a</h2>
    </div>
    <div style="float:left; width:200px;">
	    <input type="text" id="fotodeldiatituloDesc" style="width:100%;" name="fotodeldiatituloDesc" value="">
    </div>
    <div style="float:left; width:50px; margin-left:10px;">
	    <input type="button" name="buscar" value="Buscar" id="Buscar" onclick="BuscarFoto()" />
    </div>
    <div class="clearboth"></div>
    <div style="height:200px; overflow-y:auto; margin-top:10px;">
        <table style="width:95%" class="tableseleccion">
        
                 <tr>
                    <th width="20%">C&oacute;digo</th>
                    <th width="20%">Foto</th>
                    <th width="40%">Titulo</th>
                    <th width="10%">Fecha</th>
                    <th width="10%">Agregar</th>
                 </tr> 
                 <tbody id="fotoDiaLstData">
          <?php 
          if ($numfilas>0)
          {
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                {
					$imagen = $oMultimedia->DevolverDireccionImg($fila);
            	  ?>
                    <tr>
                        <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['fotodiacod'],ENT_QUOTES); ?></td>
                        <td style="text-align:center"><img src="<?php  echo $imagen; ?>" /></td>
                        <td style="text-align:left" id="fotodiatitulo_<?php  echo $fila['fotodiacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['fotodiatitulo'],ENT_QUOTES); ?></td>
                        <td style="text-align:left"><?php  echo FuncionesPHPLocal::ConvertirFecha($fila['fotofecha'],"aaaa-mm-dd","dd/mm/aaaa"); ?></td>
                        <td style="text-align:center; font-weight:bold;">
                           <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarFoto(<?php  echo $fila['fotodiacod']?>,'<?php  echo $imagen?>')">&nbsp;</a>
                        </td>
                    </tr> 
            <?php 
                }
          }
          ?>
                </tbody>            
        </table> 
    </div>
    <div style="float:left; width:200px;">
	    Titulo: <input type="text" id="fotodeldiatitulo" name="fotodeldiatitulo" value="<?php  echo $fotodeldiatitulo?>">
    </div>
    <div style="width:150px;float:left;margin-left:20px;" id="previewfoto">
    	<?php  if ($existefoto){?>
        	<img src="<?php  echo $imagenFotoDia?>" id="fotoseleccionada_img"/>
        <?php  }?>
    </div>
    <div style="width:350px;float:left;margin-left:20px;" id="titulopreview">
		<?php  echo $fotodiatitulo?>
    </div>
    <div class="clearboth" style="font-size:1px;height:1px;">&nbsp;</div>
     <input type="hidden" id="fotodiacod" name="fotodiacod" value="<?php  echo $fotodiacod?>"/>
     <div class="clearboth" style="font-size:1px;height:1px;">&nbsp;</div>
    <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="Validar()">Guardar y Cerrar</a>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
	function Validar()
	{
		if($("#fotodiacod").val()=='')
		{
			alert("Debe seleccionar una imagen como foto del dia");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#fotodiacod").val()=='')
		{
			alert("Debe seleccionar una imagen como foto del dia");
			
		}else{
			agregarModulo();
		}
		return true;
	}
	function AgregarFoto(fotodiacod, nombre_archivo)
	{
		$("#previewfoto").html('<img src="'+nombre_archivo+'" id="fotoseleccionada_img"/>');
		$("#titulopreview").html($("#fotodiatitulo_"+fotodiacod).html());
		$("#fotodiacod").val(fotodiacod);
			
	}
	function BuscarFoto()
	{
		var param, url;
		$("#fotoDiaLstData").html('<tr><td colspan="5" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando...</td></div></tr>');
		param = "fotodiatitulo="+$("#fotodeldiatituloDesc").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_fotodia_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#fotoDiaLstData").html(msg);
		   }
		 });
	}
	
</script>
