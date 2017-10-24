<? 
//print_r($vars);
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$bannercod="";
$BannerDesc="";
$BannerTitulo="";
$BannerTexto="";
$Alineacion="";
$muestroagregar=false;
$cargobanners=false;
$Direccion = "left";
$AutoPlay=0;
$MuestraBotonera = 1;
$TipoCarrousel = 1;
if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->bannercod) && count($objDataModel->bannercod)>0)
		$cargobanners=true;

	if (isset($objDataModel->Direccion))
		$Direccion = $objDataModel->Direccion;
		
	if (isset($objDataModel->AutoPlay))
		$AutoPlay = $objDataModel->AutoPlay;
		
	if (isset($objDataModel->MuestraBotonera))
		$MuestraBotonera = $objDataModel->MuestraBotonera;

	if (isset($objDataModel->TipoCarrousel))
		$TipoCarrousel = $objDataModel->TipoCarrousel;
		
}
$oBanner= new cBanners($vars['conexion']);
$datos=array(); 
$datos['bannertipocod']="2";
$datos['orderby'] = "bannercod desc";
$datos['limit'] = "LIMIT 0,20";
if(!$oBanner->BusquedaAvanzada($datos,$resultadobanners,$numfilas)) {
	$error = true;
}

?>
<div style="float:left; width:300px">
    <div style="padding-top:10px">
        <div style="float:left; width:280px; text-align:left;"><input type="text" style="width:100%;" id="BannerDesc" name="BannerDesc" onkeypress="SearchBanner(arguments[0]||event)"></div>
		<div style="height:250px; overflow-y:auto; margin-top:5px; float:left">
            <table  class="tableseleccion">
                     <tr>
                        <th width="10%">C&oacute;d</th>
                        <th width="50%">Titulo</th>
                        <th width="20%">Im&aacute;gen</th>
                        <th width="20%">Agregar</th>
                     </tr> 
                     <tbody id="bannerLstData">
          <?  if ($numfilas>0)
            {
                
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadobanners))
                {
            ?>
                        <tr>
                            <td style="text-align:center"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannercod'],ENT_QUOTES); ?></td>
                            <td style="text-align:left" id="bannerdesc_<? echo $fila['bannercod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannerdesc'],ENT_QUOTES); ?></td>
                             <td style="text-align:left" id="bannerdesc_<? echo $fila['bannercod']?>"><img style="width:50px; height:30px" src="<? echo  DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$fila['bannerarchubic']?>"/> </img> &nbsp;</td>                               
                            <td style="text-align:center; font-weight:bold;">
                               <a class="add_image"  style="text-align:center; margin-left:15px" href="javascript:void(0)" onclick="AgregarBanner(<? echo $fila['bannercod']?>)">&nbsp;</a>
                            </td>
                        </tr> 
            <?
                }
            }
            ?>
            		</tbody>            
            </table> 
  	    </div><!-- Cierre div-->
    </div><!-- Cierre div-->
</div><!-- Cierre div columna izq-->

<div style="float:left; width:375px; margin-left:30px">
    <div style="margin-top:5px;">
     	<label>Direcci&oacute;n:</label>
        <select name="Direccion" id="Direccion"  class="full" style="width:80%; text-align:left;">
            <option value="left" <? if ($Direccion=="left") echo 'selected="selected"'?>>Derecha - Izquierda</option>
            <option value="right" <? if ($Direccion=="right") echo 'selected="selected"'?>>Izquierda - Derecha</option>
            <option value="up" <? if ($Direccion=="up") echo 'selected="selected"'?>>Abajo - Arriba</option>
            <option value="down" <? if ($Direccion=="down") echo 'selected="selected"'?>>Arriba - Abajo</option>
        
        </select>
    </div>
    <div style="margin-top:5px;">
     	<label>Auto Play:</label>
        <select name="AutoPlay" id="AutoPlay"  class="full" style="width:90px; text-align:left;">
            <option value="0" <? if ($AutoPlay=="0") echo 'selected="selected"'?>>No</option>
            <option value="1" <? if ($AutoPlay=="1") echo 'selected="selected"'?>>Si</option>
        </select>
    </div>
    <div style="margin-top:5px;">
     	<label>Muestra Botonera:</label>
        <select name="MuestraBotonera" id="MuestraBotonera"  class="full" style="width:90px; text-align:left;">
            <option value="0" <? if ($MuestraBotonera=="0") echo 'selected="selected"'?>>No</option>
            <option value="1" <? if ($MuestraBotonera=="1") echo 'selected="selected"'?>>Si</option>
        </select>
    </div>
    <hr style="margin:5px 0;" />
    <div style="text-align:left; margin-top:5px;">
        <h2 class="titulopopup">Banner Seleccionado</h2>
        <div style="margin:5px 1px 5px 0px">
            <div style="float:left; width:70px">Nombre del banner:</div>
            <div id="TituloBanner" style="float:left; width:71%; text-align:left; font-weight:bold"></div><!-- Cierre TituloBanner-->
            <div style="clear:both; height:1px;">&nbsp;</div>
            <input type="hidden" name="bannercod" id="bannercod" value="" />
        </div><!-- Cierre div-->
    </div><!-- Cierre div-->
    <div style="clear:both; height:1px;"></div>
    <div style="margin:5px 1px 5px 0px">
     	<label>Alinear Texto:</label>
        <select name="Alineacion" id="Alineacion"  class="full" style="width:70%; text-align:left;">
            <option value="leftar" selected="selected" >Izquierda Arriba</option>
            <option value="rightar">Derecha Arriba</option>
            <option value="leftab">Izquierda Abajo</option>
            <option value="rightab">Derecha Abajo</option>
        </select>
    </div><!-- Cierre div alinear-->
	 <div style="margin:5px 1px 5px 0px">
     	<label>Muestra Descripci&oacute;n:</label>
        <select name="MuestraDescripcion" id="MuestraDescripcion"  class="full" style="width:90px; text-align:left;">
            <option value="1" selected="selected" >Si</option>
            <option value="0">No</option>
        </select>
    </div><!-- Cierre div alinear-->
	<div style="margin:5px 0px 5px 0px">
        <label>T&iacute;tulo:</label>
        <input type="text" value="" id="BannerTitulo" name="BannerTitulo" maxlength="100" size="50" />
    </div><!-- Cierre div ancho-->
    <div style="margin:5px 0px 5px 0px">
        <label>Texto:</label>
       <textarea id="BannerTexto" name="BannerTexto" cols="45" rows="3"></textarea>
    </div><!-- Cierre div ancho-->
    <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" id="AgregarBanner" onclick="AgregarBannerTabla()">Agregar</a>
                <a href="javascript:void(0)" id="ModificarBanner" style="display:none" onclick="ModificarBannerTabla()">Modificar</a>
                <a href="javascript:void(0)" id="CancelarBanner" style="display:none" onclick="CancelarAccionModif()">Cancelar</a>
            </li>
        </ul>
    </div><!-- Cierre menucarga-->
    
</div> <!-- Cierre columna derecha-->

<div style="clear:both">&nbsp;</div>
<div>
    <table class="tableseleccion">
             <tr>
                <th width="5%">C&oacute;d</th>
                <th width="50%">Titulo</th>
                <th width="15%" style="text-align:center">Orden</th>
                <th width="15%" style="text-align:center">Editar</th>
                <th width="15%" style="text-align:center">Eliminar</th>
             </tr> 
             <tbody id="TituloBannerTabla">             
				<? if ($cargobanners){
						foreach ($objDataModel->bannercod as $bannercod)
						{
							$datosbusqueda['bannercod'] = $bannercod;
							if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
								return false;
							
							$datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
							?>
                            <tr id="trbannercod_<? echo $bannercod?>">
                            	<td>
                                	<? echo $bannercod;?>
                                    <input type="hidden" id="bannercod_<? echo  $bannercod?>" name="bannercod[<? echo  $bannercod?>]" value="<? echo  $bannercod?>" />
                                    <input type="hidden" id="TituloBanner_<? echo  $bannercod?>" name="TituloBanner[<? echo  $bannercod?>]" value="<? echo  $objDataModel->TituloBanner->{$bannercod}?>" />
                                    <input type="hidden" id="Alineacion_<? echo  $bannercod?>" name="Alineacion[<? echo  $bannercod?>]" value="<? echo  $objDataModel->Alineacion->{$bannercod}?>" />
                                    <input type="hidden" id="MuestraDescripcion_<? echo  $bannercod?>" name="MuestraDescripcion[<? echo  $bannercod?>]" value="<? echo  $objDataModel->MuestraDescripcion->{$bannercod}?>" />
                                     <input type="hidden" id="BannerTitulo_<? echo  $bannercod?>" name="BannerTitulo[<? echo  $bannercod?>]" value="<? echo  $objDataModel->BannerTitulo->{$bannercod}?>" />
                                      <input type="hidden" id="BannerTexto_<? echo  $bannercod?>" name="BannerTexto[<? echo  $bannercod?>]" value="<? echo  $objDataModel->BannerTexto->{$bannercod}?>" />
                               </td>
                            	<td id="titulo_<? echo $bannercod?>">
                               		<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES)?>
                                </td>
                            	<td style="text-align:center">
                                	<a href="javascript:void(0)" class="orden" title="Ordenar">
                                    	<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                                </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Editar(<? echo $bannercod?>)" title="Editar">
                                    	<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />
                                    </a>
                               </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Eliminar(<? echo $bannercod?>)" title="Eliminar">
                                    	<img src='modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                               </td>
                            </tr>
                            <? 
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
            <? if (!$muestroagregar) {   ?>
            <a href="javascript:void(0)" onclick="agregarModulo()">Guardar y Agregar otro</a>
            <? } ?>
        </li>
    </ul>
</div>           




<script type="text/javascript">
	
	jQuery(document).ready(function(){
		MovimientoOrden();
	});


	function AgregarBanner(codigo)
	{
		CancelarAccionModif();
		$("#TituloBanner").html($("#bannerdesc_"+codigo).html());
		$("#bannercod").val(codigo);
	}
	
	
	function ModificarBannerTabla()
	{
		$("#TituloBanner_"+$("#bannercod").val()).val($("#TituloBanner").val());
		$("#Alineacion_"+$("#bannercod").val()).val($("#Alineacion").val());
		$("#MuestraDescripcion_"+$("#bannercod").val()).val($("#MuestraDescripcion").val());
		$("#BannerTitulo_"+$("#bannercod").val()).val($("#BannerTitulo").val());
		$("#BannerTexto_"+$("#bannercod").val()).val($("#BannerTexto").val());
		CancelarAccionModif();
	}
	
	
	function AgregarBannerTabla()
	{
		var html = "";
		if ($("#bannercod").val()=="")
		{
			alert("Debe seleccionar un banner")
			return false;	
		}
		var codigo = $("#bannercod").val();
		if($("#bannercod_"+codigo).length==0)
		{
			var titulo = $("#bannerdesc_"+codigo).html();
			var input = $('<input>').attr({type: 'hidden',id: 'bannercod_'+codigo, name: 'bannercod['+codigo+']', value:codigo});
			var inputBannerTitulo = $('<input>').attr({type: 'hidden',id: 'BannerTitulo_'+codigo, name: 'BannerTitulo['+codigo+']', value:$("#BannerTitulo").val()});
			var inputBannerTexto = $('<input>').attr({type: 'hidden',id: 'BannerTexto_'+codigo, name: 'BannerTexto['+codigo+']', value:$("#BannerTexto").val()});
			var inputAlineacion = $('<input>').attr({type: 'hidden',id: 'Alineacion_'+codigo, name: 'Alineacion['+codigo+']', value:$("#Alineacion").val()});
			var inputDescripcion = $('<input>').attr({type: 'hidden',id: 'MuestraDescripcion_'+codigo, name: 'MuestraDescripcion['+codigo+']', value:$("#MuestraDescripcion").val()});
	
	
			var aEliminar = $('<a>').attr({onclick: 'Eliminar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Eliminar' />");
			var aEditar = $('<a>').attr({onclick: 'Editar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />");
			var aOrden = $('<a>').attr({class: 'orden', onclick: 'javascript:void(0)'}).html("<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />");
	
			var TDcodigo = $('<td>').html(codigo).append(input).append(inputBannerTitulo).append(inputBannerTexto).append(inputAlineacion).append(inputDescripcion);
			var TDtitulo = $('<td>').attr({id: 'titulo_'+codigo}).html(titulo);
			var TDorden = $('<td>').attr({style:'text-align: center'}).html(aOrden);
			var TDeditar = $('<td>').attr({style:'text-align: center'}).html(aEditar);
			var TDeliminar = $('<td>').attr({style:'text-align: center'}).html(aEliminar);
	
			var tr = $('<tr>').attr({id: 'trbannercod_'+codigo});
			tr.append(TDcodigo);
			tr.append(TDtitulo);
			tr.append(TDorden);
			tr.append(TDeditar);
			tr.append(TDeliminar);
			$("#TituloBannerTabla").append(tr);
			MovimientoOrden();
			LimpiarDatos();
		}else
			alert("El banner ya se encuentra dentro del carrousel.")
	}
	
	
	function Eliminar(codigo)
	{
		if (!confirm("Esta seguro que desea eliminar el banner?"))
			return false;
		$("#trbannercod_"+codigo).remove();
	}
	
	function Editar(codigo)
	{
		$("#TituloBanner").html($("#titulo_"+codigo).html());
		$("#bannercod").val(codigo);
		$("#BannerTitulo").val($("#BannerTitulo_"+codigo).val());
		$("#BannerTexto").val($("#BannerTexto_"+codigo).val());
		$("#Alineacion").val($("#Alineacion_"+codigo).val());
		$("#MuestraDescripcion").val($("#MuestraDescripcion_"+codigo).val());
		
		MostrarBtModif();
	}
	
	
	function MostrarBtModif()
	{
		$("#AgregarBanner").hide();
		$("#ModificarBanner").show();
		$("#CancelarBanner").show();
	}
	function OcultarBtModif()
	{
		$("#AgregarBanner").show();
		$("#ModificarBanner").hide();
		$("#CancelarBanner").hide();
		
	}
	
	function LimpiarDatos()
	{
		$("#TituloBanner").html("");
		$("#bannercod").val("");
		$("#BannerTitulo").val("");
		$("#BannerTexto").val("");
		$("#Alineacion").val("left");
		$("#MuestraDescripcion").val("1");
	}
	
	function CancelarAccionModif()
	{
		OcultarBtModif();
		LimpiarDatos();
	}
	 
	var timeoutHnd; 
	function SearchBanner(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarBanner,500) 
	}
	
	function BuscarBanner()
	{
		var param, url;
		$("#bannerLstData").html('<tr><td colspan="4" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando Banners...</td></div></tr>');
		param = "bannerdesc="+$("#bannerdesc").val();
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
	
	
	function MovimientoOrden()
	{	
		$("#TituloBannerTabla").sortable(
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
</script>