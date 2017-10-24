<?php  
//print_r($vars);
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$noticiacod="";
$NoticiaTitulo="";
$TamanioNoticia="";
$Alineacion="";
$muestroagregar=false;
$cargonoticia=false;
$Direccion = "left";
$AutoPlay=0;
$CantidadPasaje=1;
$MuestraBotonera = 1;
$TipoCarrousel = 1;
$ColorTexto= "textonegro";
$Titulo="Noticias Especiales";


if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->noticiacod) && count($objDataModel->noticiacod)>0)
		$cargonoticia=true;

	if (isset($objDataModel->Direccion))
		$Direccion = $objDataModel->Direccion;
		
	if (isset($objDataModel->AutoPlay))
		$AutoPlay = $objDataModel->AutoPlay;
		
	if (isset($objDataModel->MuestraBotonera))
		$MuestraBotonera = $objDataModel->MuestraBotonera;

	if (isset($objDataModel->TipoCarrousel))
		$TipoCarrousel = $objDataModel->TipoCarrousel;
		
	if (isset($objDataModel->CantidadPasaje))
		$CantidadPasaje = $objDataModel->CantidadPasaje;
	
	if (isset($objDataModel->Titulo))
		$Titulo = $objDataModel->Titulo;
	
	if (isset($objDataModel->ColorTexto))
		$ColorTexto = $objDataModel->ColorTexto;
	
	if (isset($objDataModel->ColorFondo))
		$ColorFondo = $objDataModel->ColorFondo;	

	
}
$valoranchodefault = "100";
$oNoticias= new cNoticias($vars['conexion']);
$datos=array(); 
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['orderby'] = "noticiacod desc";
$datos['noticiaestadocod'] = NOTPUBLICADA;
$datos['limit'] = "LIMIT 0,20";
if(!$oNoticias->BusquedaAvanzada($datos,$resultadonoticia,$numfilas)) {
	$error = true;
}

?>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<div style="float:left; width:675px">
	<div style="float:left; width:300px">
    	<div style="float:left; width:280px; text-align:left;">Titulo: <input type="text" style="width:100%;" id="Titulo" name="Titulo"  value="<?php  echo $Titulo; ?>"></div>
    </div>
	<div style="float:left; width:375px">
    	<?php  /*
        <div style="float:left; width:30%">
            <label>Color Titulo:</label>
        </div>
        <div style="float:left; width:70%;">
            <input type="text" value="<?php  echo $ColorTitulo?>" id="ColorTitulo" name="ColorTitulo" maxlength="7" size="10" />(Hexadecimal)
        </div>*/?>
        <div style="float:left; width:50%">
            <label>Color Fondo:</label>
        	<div style="clear:both; height:1px;"></div>
        	<select id="ColorFondo" name="ColorFondo" >
                <option value="amarillo" <?php  if ($ColorFondo=="amarillo") echo 'selected="selected"'?> >Amarillo</option>
            	<option value="violeta" <?php  if ($ColorFondo=="violeta") echo 'selected="selected"'?> >Violeta</option>
            	<option value="cyan" <?php  if ($ColorFondo=="cyan") echo 'selected="selected"'?> >Cyan</option>
            </select>
        </div>
        <div style="float:left; width:50%">
            <label>Color Texto:</label>
        	<div style="clear:both; height:1px;"></div>
        	<select id="ColorTexto" name="ColorTexto" >
                <option value="textoblanco" <?php  if ($ColorTexto=="textoblanco") echo 'selected="selected"'?> >Blanco</option>
            	<option value="textonegro" <?php  if ($ColorTexto=="textonegro") echo 'selected="selected"'?> >Negro</option>
            </select>
        </div>
    </div>    
</div>
<div style="clear:both; height:1px;"></div>
<hr style="margin:5px 0;" />

<div style="float:left; width:300px">
    <div style="padding-top:10px">
        <div style="float:left; width:280px; text-align:left;">
                <label>Titulo:</label>
        <input type="text" style="width:100%;" id="NoticiaTitulo" name="NoticiaTitulo" onkeypress="SearchNoticia(arguments[0]||event)"></div>
                <div style="clear:both; height:3px;">&nbsp;</div>

		<div style="margin-top:5px; float:left">
            <table  class="tableseleccion">
                     <tr>
                        <th width="10%">C&oacute;d</th>
                        <th width="70%">Titulo</th>
                        <th width="20%">&nbsp;</th>
                     </tr> 
                     <tbody id="noticiaLstData">
          <?php   if ($numfilas>0)
            {

                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadonoticia))
                {
            ?>
                        <tr>
                            <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiacod'],ENT_QUOTES); ?></td>
                            <td style="text-align:left" id="noticiatitulo_<?php  echo $fila['noticiacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES); ?></td>
                            <td style="text-align:center; font-weight:bold;">
                               <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarNoticia(<?php  echo $fila['noticiacod']?>)">&nbsp;</a>
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

<div style="float:left; width:375px; margin-left:30px">
    <div>
    	<h2 class="titulopopup">Datos Generales</h2>
     	<label>Cuantos &iacute;tems pasa:</label>
        <select name="CantidadPasaje" id="CantidadPasaje"  class="full" style="width:60%; text-align:left;">
            <option value="1" <?php  if ($CantidadPasaje=="1") echo 'selected="selected"'?>>1</option>
            <option value="2" <?php  if ($CantidadPasaje=="2") echo 'selected="selected"'?>>2</option>
            <option value="3" <?php  if ($CantidadPasaje=="3") echo 'selected="selected"'?>>3</option>
        </select>
    </div>
    <div style="margin-top:5px;">
     	<label>Direcci&oacute;n:</label>
        <select name="Direccion" id="Direccion"  class="full" style="width:80%; text-align:left;">
            <option value="left" <?php  if ($Direccion=="left") echo 'selected="selected"'?>>Derecha - Izquierda</option>
            <option value="right" <?php  if ($Direccion=="right") echo 'selected="selected"'?>>Izquierda - Derecha</option>
            <?php  /*
            <option value="up" <?php  if ($Direccion=="up") echo 'selected="selected"'?>>Abajo - Arriba</option>
            <option value="down" <?php  if ($Direccion=="down") echo 'selected="selected"'?>>Arriba - Abajo</option>
			*/ ?>
        
        </select>
    </div>
    <div style="margin-top:5px;">
     	<label>Auto Play:</label>
        <select name="AutoPlay" id="AutoPlay"  class="full" style="width:90px; text-align:left;">
            <option value="0" <?php  if ($AutoPlay=="0") echo 'selected="selected"'?>>No</option>
            <option value="1" <?php  if ($AutoPlay=="1") echo 'selected="selected"'?>>Si</option>
        </select>
    </div>
    <div style="margin-top:5px;">
     	<label>Muestra Botonera:</label>
        <select name="MuestraBotonera" id="MuestraBotonera"  class="full" style="width:90px; text-align:left;">
            <option value="0" <?php  if ($MuestraBotonera=="0") echo 'selected="selected"'?>>No</option>
            <option value="1" <?php  if ($MuestraBotonera=="1") echo 'selected="selected"'?>>Si</option>
        </select>
    </div>
    <hr style="margin:5px 0;" />
    <div style="text-align:left; margin-top:5px;">
        <h2 class="titulopopup">Noticia Seleccionada</h2>
        <div style="margin:5px 1px 5px 0px">
            <div style="float:left; width:70px">Titulo:</div>
            <div id="TituloNoticia" style="float:left; width:71%; text-align:left; font-weight:bold"></div><!-- Cierre TituloNoticia-->
            <div style="clear:both; height:1px;">&nbsp;</div>
            <input type="hidden" name="noticiacod" id="noticiacod" value="" />
        </div><!-- Cierre div-->
    </div><!-- Cierre div-->
    
    <div style="clear:both; height:1px;"></div>
   
    <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" id="AgregarNoticia" onclick="AgregarNoticiaTabla()">Agregar</a>
                <a href="javascript:void(0)" id="ModificarNoticia" style="display:none" onclick="ModificarNoticiaTabla()">Modificar</a>
                <a href="javascript:void(0)" id="CancelarNoticia" style="display:none" onclick="CancelarAccionModif()">Cancelar</a>
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
             <tbody id="TituloNoticiaTabla">             
				<?php  
				if ($cargonoticia){
						foreach ($objDataModel->noticiacod as $noticiacod)
						{
							$datosbusqueda['noticiacod'] = $noticiacod;
							if(!$oNoticias->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
								return false;
							
							$datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
							?>
                            <tr id="trnoticiacod_<?php  echo $noticiacod?>">
                            	<td>
                                	<?php  echo $noticiacod;?>
                                    <input type="hidden" id="noticiacod_<?php  echo  $noticiacod?>" name="noticiacod[<?php  echo  $noticiacod?>]" value="<?php  echo  $noticiacod?>" />
                                    <input type="hidden" id="TamanioNoticia_<?php  echo  $noticiacod?>" name="TamanioNoticia[<?php  echo  $noticiacod?>]" value="<?php  echo  $objDataModel->TamanioNoticia->{$noticiacod}?>" />
                                    <input type="hidden" id="Alineacion_<?php  echo  $noticiacod?>" name="Alineacion[<?php  echo  $noticiacod?>]" value="<?php  echo  $objDataModel->Alineacion->{$noticiacod}?>" />
                                    <input type="hidden" id="MuestraDescripcion_<?php  echo  $noticiacod?>" name="MuestraDescripcion[<?php  echo  $noticiacod?>]" value="<?php  echo  $objDataModel->MuestraDescripcion->{$noticiacod}?>" />
                               </td>
                            	<td id="noticiadesc_<?php  echo $noticiacod?>">
                               		<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES)?>
                                </td>
                            	<td style="text-align:center">
                                	<a href="javascript:void(0)" class="orden" title="Ordenar">
                                    	<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                                </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Editar(<?php  echo $noticiacod?>)" title="Editar">
                                    	<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />
                                    </a>
                               </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Eliminar(<?php  echo $noticiacod?>)" title="Eliminar">
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


	function AgregarNoticia(codigo)
	{
		CancelarAccionModif();
		$("#TituloNoticia").html($("#noticiatitulo_"+codigo).html());
		$("#noticiacod").val(codigo);
	}
	
	
	function ModificarNoticiaTabla()
	{
		$("#TamanioNoticia_"+$("#noticiacod").val()).val($("#TamanioNoticia").val());
		$("#Alineacion_"+$("#noticiacod").val()).val($("#Alineacion").val());
		$("#MuestraDescripcion_"+$("#bannercod").val()).val($("#MuestraDescripcion").val());
		CancelarAccionModif();
	}
	
	
	function AgregarNoticiaTabla()
	{
		
		var html = "";
		if ($("#noticiacod").val()=="")
		{
			alert("Debe seleccionar una noticia")
			return false;	
		}
		var codigo = $("#noticiacod").val();
		
		if($("#noticiacod_"+codigo).length==0)
		{
			var titulo = $("#noticiatitulo_"+codigo).html();
			var input = $('<input>').attr({type: 'hidden',id: 'noticiacod_'+codigo, name: 'noticiacod['+codigo+']', value:codigo});
			var inputTamanio = $('<input>').attr({type: 'hidden',id: 'TamanioNoticia_'+codigo, name: 'TamanioNoticia['+codigo+']', value:$("#TamanioNoticia").val()});
			var inputAlineacion = $('<input>').attr({type: 'hidden',id: 'Alineacion_'+codigo, name: 'Alineacion['+codigo+']', value:$("#Alineacion").val()});
			var inputDescripcion = $('<input>').attr({type: 'hidden',id: 'MuestraDescripcion_'+codigo, name: 'MuestraDescripcion['+codigo+']', value:$("#MuestraDescripcion").val()});
	
	
			var aEliminar = $('<a>').attr({onclick: 'Eliminar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Eliminar' />");
			var aEditar = $('<a>').attr({onclick: 'Editar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />");
			var aOrden = $('<a>').attr({class: 'orden', onclick: 'javascript:void(0)'}).html("<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />");
	
			var TDcodigo = $('<td>').html(codigo).append(input).append(inputTamanio).append(inputAlineacion).append(inputDescripcion);
			var TDtitulo = $('<td>').attr({id: 'noticiadesc_'+codigo}).html(titulo);
			var TDorden = $('<td>').attr({style:'text-align: center'}).html(aOrden);
			var TDeditar = $('<td>').attr({style:'text-align: center'}).html(aEditar);
			var TDeliminar = $('<td>').attr({style:'text-align: center'}).html(aEliminar);
	
			var tr = $('<tr>').attr({id: 'trnoticiacod_'+codigo});
			tr.append(TDcodigo);
			tr.append(TDtitulo);
			tr.append(TDorden);
			tr.append(TDeditar);
			tr.append(TDeliminar);
			$("#TituloNoticiaTabla").append(tr);
			MovimientoOrden();
			LimpiarDatos();
		}else
			alert("La noticia ya se encuentra dentro del carrousel.")
	}
	
	
	function Eliminar(codigo)
	{
		if (!confirm("Esta seguro que desea eliminar la noticia?"))
			return false;
		$("#trnoticiacod_"+codigo).remove();
	}
	
	function Editar(codigo)
	{
		$("#TituloNoticia").html($("#noticiadesc_"+codigo).html());
		$("#noticiacod").val(codigo);
		$("#TamanioNoticia").val($("#TamanioNoticia_"+codigo).val());
		$("#Alineacion").val($("#Alineacion_"+codigo).val());
		$("#MuestraDescripcion").val($("#MuestraDescripcion_"+codigo).val());
		
		MostrarBtModif();
	}
	
	
	function MostrarBtModif()
	{
		$("#AgregarNoticia").hide();
		$("#ModificarNoticia").show();
		$("#CancelarNoticia").show();
	}
	function OcultarBtModif()
	{
		$("#AgregarNoticia").show();
		$("#ModificarNoticia").hide();
		$("#CancelarNoticia").hide();
		
	}
	
	function LimpiarDatos()
	{
		$("#TituloNoticia").html("");
		$("#noticiacod").val("");
		$("#TamanioNoticia").val("<?php  echo $valoranchodefault?>");
		$("#Alineacion").val("left");
		$("#MuestraDescripcion").val("1");
	}
	
	function CancelarAccionModif()
	{
		OcultarBtModif();
		LimpiarDatos();
	}
	 
	var timeoutHnd; 
	function SearchNoticia(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarNoticia,500) 
	}
	
	function BuscarNoticia()
	{
		var param, url;
		$("#noticiaLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando Banners...</td></div></tr>');
		param = "noticiatitulodesc="+$("#NoticiaTitulo").val();
		param += "&limit=5";
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_noticias_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#noticiaLstData").html(msg);
		   }
		 });
	}
	
	
	function MovimientoOrden()
	{	
		$("#TituloNoticiaTabla").sortable(
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