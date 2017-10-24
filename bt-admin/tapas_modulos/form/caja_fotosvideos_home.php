<?php  
//print_r($vars);
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA, "multimedia"=>"si"));
$galeriacod="";
$galeriatitulo="";
$multimediacod="";
$cargogalerias=false;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->galeriacod) && count($objDataModel->galeriacod)>0)
		$cargogalerias=true;
}

$valoranchodefault=100;	
$oGalerias= new cGalerias($vars['conexion']);
$oMultimedia= new cMultimedia($vars['conexion'], "");
$datos=array(); 
$datos['orderby'] = "galeriacod desc";
$datos['galeriaestadocod']=ACTIVO;
$datos['limit'] = "LIMIT 0,5";
if(!$oGalerias->BuscarAvanzadaxGaleria($datos,$resultadogaleria,$numfilas)) {
	$error = true;
}

?>
<div style="float:left; width:450px">
    <div style="padding-top:10px">
        <div style="float:left; width:220px; text-align:left;">
        	<input type="text" style="width:100%;" id="galeriatitulo" name="galeriatitulo" onkeypress="SearchGaleria(arguments[0]||event)">
        </div>
        <div style="float:left; width:160px; text-align:left;">
        	<select name="multimediaconjuntocod" id="multimediaconjuntocod" onchange="SearchGaleria(arguments[0]||event)">
            	<option value="">Todos</option>
                <option value="<?php  echo FOTOS?>">Fotos</option>
                <option value="<?php  echo VIDEOS?>">Videos</option>
            </select>
        </div>
		<div style="margin-top:5px; float:left">
            <table  class="tableseleccion">
                     <tr>
                        <th width="10%">C&oacute;d</th>
                        <th width="70%">Titulo</th>
                        <th width="20%">&nbsp;</th>
                     </tr> 
                     <tbody id="galeriaLstData">
						  <?php   if ($numfilas>0)
                            {
                                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadogaleria))
                                {
                           			 ?>
                                     <tr>
                                         <td style="text-align:center">
										 	<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriacod'],ENT_QUOTES); ?>
                                         </td>
                                         <td style="text-align:left" id="galeriatitulo_<?php  echo $fila['galeriacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree("(".$fila['multimediaconjuntodesc'].") ".$fila['galeriatitulo'],ENT_QUOTES); ?></td>
                                            <td style="text-align:center; font-weight:bold;">
                                               <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarGaleria(<?php  echo $fila['galeriacod']?>)">&nbsp;</a>
                                            </td>
                                     </tr> 
                            <?php 
                                }
                            }
                            ?>
            		</tbody>            
            </table> 
  	    </div><!-- Cierre div-->
    </div><!-- Cierre div-->
</div><!-- Cierre div columna izq-->

<div style="float:left; width:225px; margin-left:20px">
    <div>
    	<h2 class="titulopopup">Galer&iacute;a Seleccionada</h2>
    </div>
    <hr style="margin:5px 0;" />
    <div style="text-align:left; margin-top:5px;">
        <div style="margin:5px 1px 5px 0px">
            <div style="float:left; width:70px">Titulo:</div>
            <div id="TituloGaleria" style="float:left; text-align:left; font-weight:bold"></div><!-- Cierre TituloGaleria-->
            <div style="clear:both; height:10px;">&nbsp;</div>
            <div style="float:left;width:100px;">
            	Foto / Video: 
            </div>
            
            <div id="combo_fotos_galeria" style="float:left;">
            	<input type="hidden" name="multimediacod" id="multimediacod" value=""/>
                Seleccione primero una galer&iacute;a
                <div style="clear:both; height:1px;">&nbsp;</div>
            </div>
            <div style="clear:both; height:10px;">&nbsp;</div>
            <div id="multimediacodthumb">
            </div>
            <div id="multimediaurl"></div>
            <div style="clear:both; height:10px;">&nbsp;</div>
            <div class="menucarga" style="text-align:right">
                <ul>
                    <li>
                        <a href="javascript:void(0)" id="AgregarGaleria" onclick="AgregarGaleriaTabla()">Agregar</a>
                        <a href="javascript:void(0)" id="ModificarGaleria" style="display:none" onclick="ModificarGaleriaTabla()">Modificar</a>
                        <a href="javascript:void(0)" id="CancelarGaleria" style="display:none" onclick="CancelarAccionModif()">Cancelar</a>
                    </li>
                </ul>
            </div><!-- Cierre menucarga-->
            <input type="hidden" name="galeriacod" id="galeriacod" value="" />
            
        </div><!-- Cierre div-->
    </div><!-- Cierre div-->
    <div style="clear:both; height:1px;"></div>
  </div> <!-- Cierre columna derecha-->

<div style="clear:both">&nbsp;</div>
<div>
    <table class="tableseleccion">
             <tr>
                <th width="5%">C&oacute;d</th>
                <th width="35%">Titulo</th>
                <th width="15%">Foto/Video</th>
                <th width="15%" style="text-align:center">Orden</th>
                <th width="15%" style="text-align:center">Editar</th>
                <th width="15%" style="text-align:center">Eliminar</th>
             </tr> 
             <tbody id="TituloGaleriaTabla">             
				<?php  
				if ($cargogalerias){
						foreach ($objDataModel->galeriacod as $galeriacod)
						{
							
							$datosbusqueda['galeriacod'] = $galeriacod;
							if(!$oGalerias->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
								return false;
							
							$datosgaleria = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
							?>
                            <tr id="trgaleriacod_<?php  echo $galeriacod?>">
                            	<td>
                                	<?php  echo $galeriacod;?>
                                    <input type="hidden" id="galeriacod_<?php  echo $galeriacod?>" name="galeriacod[<?php  echo $galeriacod?>]" value="<?php  echo $galeriacod?>" />
                                    <input type="hidden" id="multimediacod_<?php  echo $galeriacod?>" name="multimediacod[<?php  echo $galeriacod?>]" value="<?php  echo  $objDataModel->multimediacod->$galeriacod?>" />
                                   
                               </td>
                            	<td id="galeriatitulo_<?php  echo $galeriacod?>">
                               		<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria['galeriatitulo'],ENT_QUOTES)?>
                                </td>
                                <td id="galeriaimagen_<?php  echo $galeriacod?>">
                                	<?php  if (isset($objDataModel->multimediacod->$galeriacod)){
										
										$datos['multimediacatcarpeta']="THUMBCUAD";
										$datosP=array(
											'multimediacod'=>$objDataModel->multimediacod->$galeriacod);
										if ($oMultimedia->BuscarMultimediaxCodigo($datosP, $resultadoM, $numfilasM))
										{
											$filaM = $conexion->ObtenerSiguienteRegistro($resultadoM);
											?>
											<img src="<?php  echo $oMultimedia->DevolverDireccionImg($filaM)?>"/>	
                      	                   <?php  
										 
										}
                                    }?>
                                </td>
                            	<td style="text-align:center">
                                	<a href="javascript:void(0)" class="orden" title="Ordenar">
                                    	<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                                </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Editar(<?php  echo$galeriacod?>)" title="Editar">
                                    	<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />
                                    </a>
                               </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Eliminar(<?php  echo$galeriacod?>)" title="Eliminar">
                                    	<img src='modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                               </td>
                            </tr>
                            <?php  
						}
                }?>
             </tbody>            
    </table> 
</div> 
<div style="clear:both; height:30px;">&nbsp;</div>
<div class="menucarga" style="text-align:right">
    <ul>
        <li>
            <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            <?php  if (!$muestroagregar) {   ?>
            <a href="javascript:void(0)" onclick="agregarModulo()">Guardar y Agregar otro</a>
            <?php  } ?>
        </li>
    </ul>
</div>           




<script type="text/javascript">
	
	jQuery(document).ready(function(){
		MovimientoOrden();
	});


	function AgregarGaleria(codigo)
	{
		LimpiarDatos();
		CancelarAccionModif();
		$("#TituloGaleria").html($("#galeriatitulo_"+codigo).html());
		$("#galeriacod").val(codigo);
		CargarFotosGaleria(codigo);
	}
	
	
	function ModificarGaleriaTabla()
	{
		$("#multimediacod_"+$("#galeriacod").val()).val($("#multimediacod").val());
		CancelarAccionModif();
	}
	
	
	function AgregarGaleriaTabla()
	{
		
		var html = "";
		if ($("#galeriacod").val()=="")
		{
			alert("Debe seleccionar una galeria")
			return false;	
		}
		if ($("#multimediacod").val()=="")
		{
			alert("Debe seleccionar la foto o video de la galeria que se visualizara en la caja")
			return false;	
		}
		
		var codigo = $("#galeriacod").val();
		
		if($("#galeriacod_"+codigo).length==0)
		{
			var titulo = $("#galeriatitulo_"+codigo).html();
			var img = $("#multimediacodthumb").html();
			var input = $('<input>').attr({type: 'hidden',id: 'galeriacod_'+codigo, name: 'galeriacod['+codigo+']', value:codigo});
			var inputMultimedia = $('<input>').attr({type: 'hidden',id: 'multimediacod_'+codigo, name: 'multimediacod['+codigo+']', value:$("#multimediacod").val()});

			var aEliminar = $('<a>').attr({onclick: 'Eliminar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Eliminar' />");
			var aEditar = $('<a>').attr({onclick: 'Editar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />");
			var aOrden = $('<a>').attr({class: 'orden', onclick: 'javascript:void(0)'}).html("<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />");
	
			var TDcodigo = $('<td>').html(codigo).append(input).append(inputMultimedia);
			var TDtitulo = $('<td>').attr({id: 'galeriatitulo_'+codigo}).html(titulo);
			var TDimg = $('<td>').attr({id: 'galeriaimagen_'+codigo}).html(img);
			var TDorden = $('<td>').attr({style:'text-align: center'}).html(aOrden);
			var TDeditar = $('<td>').attr({style:'text-align: center'}).html(aEditar);
			var TDeliminar = $('<td>').attr({style:'text-align: center'}).html(aEliminar);
	
			var tr = $('<tr>').attr({id: 'trgaleriacod_'+codigo});
			tr.append(TDcodigo);
			tr.append(TDtitulo);
			tr.append(TDimg);
			tr.append(TDorden);
			tr.append(TDeditar);
			tr.append(TDeliminar);
			$("#TituloGaleriaTabla").append(tr);
			MovimientoOrden();
			LimpiarDatos();
		}else
			alert("La galeria ya se encuentra en la caja.")
	}
	
	
	function Eliminar(codigo)
	{
		if (!confirm("Esta seguro que desea eliminar la galeria seleccionada?"))
			return false;
		$("#trgaleriacod_"+codigo).remove();
	}
	
	function Editar(codigo)
	{
		$("#TituloGaleria").html($("#galeriatitulo_"+codigo).html());
		$("#multimediacod").val($("#multimediacod"+codigo).val());
		$("#galeriacod").val(codigo);		
		MostrarBtModif();
	}
	
	
	function MostrarBtModif()
	{
		$("#AgregarGaleria").hide();
		$("#ModificarGaleria").show();
		$("#CancelarGaleria").show();
	}
	function OcultarBtModif()
	{
		$("#AgregarGaleria").show();
		$("#ModificarGaleria").hide();
		$("#CancelarGaleria").hide();
		
	}
	
	function LimpiarDatos()
	{
		$("#TituloGaleria").html("");
		$("#galeriacod").val("");
		$("#multimediacod").val("");
		$("#multimediacodthumb").html("");
	}
	
	function CancelarAccionModif()
	{
		OcultarBtModif();
		LimpiarDatos();
	}
	 
	var timeoutHnd; 
	
	function SearchGaleria(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarGalerias,500) 
	}
	
	function BuscarGalerias()
	{
		var param, url;
		$("#galeriaLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando galerias...</td></div></tr>');
		param = "galeriatitulo="+$("#galeriatitulo").val();
		param += "&multimediaconjuntocod="+$("#multimediaconjuntocod").val();
		param += "&limit=8";
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_galerias_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#galeriaLstData").html(msg);
		   }
		 });
	}
	
	
	function MovimientoOrden()
	{	
		$("#TituloGaleriaTabla").sortable(
		  { 
			tolerance: 'pointer',
			scroll: true , 
			handle: $(".orden"),
			revert: 'invalid',
			connectWith: '.orden',
			cursor: 'pointer',
			placeholder: "placeholdertablas",
			opacity: 0.6, 		
		});
	}	
	
	function CargarFotosGaleria(galeriacod)
	{
		var param, url;
			$("#combo_fotos_galeria").html('cargando...');
			param = "tipo=5&galeriacod="+galeriacod;
			$.ajax({
			   type: "POST",
			   url: "combo_ajax.php",
			   data: param,
			   dataType:"html",
			   success: function(msg){
					$("#combo_fotos_galeria").html(msg);
			   }
			 });
		
	}
	function PrevisualizarFoto()
	{
		var param, url;
		$("#multimediacodthumb").html('cargando...');
		param = "tipo=6&multimediacod="+$("#multimediacod").val();
		$.ajax({
			   type: "POST",
			   url: "combo_ajax.php",
			   data: param,
			   dataType:"html",
			   success: function(msg){
				   if (msg!="")
				   {
						$("#multimediacodthumb").html('<img id="previewfotourl" src="'+msg+'"/>');
				   }
			   }
		 })
	}
</script>