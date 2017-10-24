<?php  
//print_r($vars);
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$frasecod="";
$fraseautor="";
$FraseDescLarga="";
$muestroagregar=false;
$cargofrases=false;
$Direccion = "left";
$AutoPlay=0;
$MuestraBotonera = 1;
if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->frasecod) && count($objDataModel->frasecod)>0)
		$cargofrases=true;

	if (isset($objDataModel->Direccion))
		$Direccion = $objDataModel->Direccion;
		
	if (isset($objDataModel->AutoPlay))
		$AutoPlay = $objDataModel->AutoPlay;
		
	if (isset($objDataModel->MuestraBotonera))
		$MuestraBotonera = $objDataModel->MuestraBotonera;
		
}
$valoranchodefault = "100";
$oFrases= new cFrases($vars['conexion']);
$datos=array(); 
$datos['limit'] = "LIMIT 0,20";
if(!$oFrases->BusquedaAvanzada($datos,$resultadofrases,$numfilas)) {
	$error = true;
}

?>
<div style="float:left; width:300px">
    <div style="padding-top:10px">
        <div style="float:left; width:280px; text-align:left;"><input type="text" style="width:100%;" id="fraseautor" name="fraseautor" onkeypress="SearchFrases(arguments[0]||event)"></div>
		<div style="margin-top:5px; float:left">
            <table  class="tableseleccion">
                     <tr>
                        <th width="10%">C&oacute;d</th>
                        <th width="70%">Titulo</th>
                        <th width="20%">&nbsp;</th>
                     </tr> 
                     <tbody id="frasesLstData">
          <?php   if ($numfilas>0)
            {
                
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadofrases))
                {
            ?>
                        <tr>
                            <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['frasecod'],ENT_QUOTES); ?></td>
                            <td style="text-align:left" id="fraseautor_<?php  echo $fila['frasecod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['fraseautor'],ENT_QUOTES); ?></td>
                            <td style="text-align:center; font-weight:bold;">
                               <a class="left" href="javascript:void(0)" onclick="AgregarFrases(<?php  echo $fila['frasecod']?>)">Seleccionar</a>
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
    	<div style="font-size:16px;">Datos Generales</div>

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
        <h2 class="titulopopup">Frase Seleccionado</h2>
            <div>
                <div style="float:left; width:100px">Autor:</div>
                <div id="TituloFrase" style="float:left; width:80%; text-align:left; font-weight:bold"></div><!-- Cierre TituloBanner-->
                <div style="clear:both">&nbsp;</div>
                <input type="hidden" name="frasecod" id="frasecod" value="" />
            </div><!-- Cierre div-->
    </div><!-- Cierre div-->
    <div style="clear:both;"></div>

    
    <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" id="AgregarFrase" onclick="AgregarFraseTabla()">Agregar</a>
                <a href="javascript:void(0)" id="ModificarFrase" style="display:none" onclick="ModificarFraseTabla()">Modificar</a>
                <a href="javascript:void(0)" id="CancelarFrase" style="display:none" onclick="CancelarAccionModif()">Cancelar</a>
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
             <tbody id="TituloFraseTabla">             
				<?php  if ($cargofrases){
						foreach ($objDataModel->frasecod as $frasecod)
						{
							$datosbusqueda['frasecod'] = $frasecod;
							if(!$oFrases->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
								return false;
							
							$datosfrases = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
							?>
                            <tr id="trfrasecod_<?php  echo $frasecod?>">
                            	<td>
                                	<?php  echo $frasecod;?>
                                    <input type="hidden" id="frasecod_<?php  echo  $frasecod?>" name="frasecod[<?php  echo  $frasecod?>]" value="<?php  echo  $frasecod?>" />
                      
                        
                               </td>
                            	<td id="titulo_<?php  echo $frasecod?>">
                               		<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosfrases['fraseautor'],ENT_QUOTES)?>
                                </td>
                            	<td style="text-align:center">
                                	<a href="javascript:void(0)" class="orden" title="Ordenar">
                                    	<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                                </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Editar(<?php  echo $frasecod?>)" title="Editar">
                                    	<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />
                                    </a>
                               </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Eliminar(<?php  echo $frasecod?>)" title="Eliminar">
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


	function AgregarFrases(codigo)
	{
		CancelarAccionModif();
		$("#TituloFrase").html($("#fraseautor_"+codigo).html());
		$("#frasecod").val(codigo);
	}
	
	
	function ModificarFraseTabla()
	{
		$("#TamanioFrase_"+$("#frasecod").val()).val;;
		CancelarAccionModif();
	}
	
	
	function AgregarFraseTabla()
	{
		var html = "";
		if ($("#frasecod").val()=="")
		{
			alert("Debe seleccionar una frase")
			return false;	
		}
		var codigo = $("#frasecod").val();
		if($("#frasecod_"+codigo).length==0)
		{
			var titulo = $("#fraseautor_"+codigo).html();
			var input = $('<input>').attr({type: 'hidden',id: 'frasecod_'+codigo, name: 'frasecod['+codigo+']', value:codigo});
			
	
	
			var aEliminar = $('<a>').attr({onclick: 'Eliminar('+codigo+')'}).html("<img src='/modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Eliminar' />");
			var aEditar = $('<a>').attr({onclick: 'Editar('+codigo+')'}).html("<img src='/modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />");
			var aOrden = $('<a>').attr({class: 'orden', onclick: 'javascript:void(0)'}).html("<img src='/modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />");
	
			var TDcodigo = $('<td>').html(codigo).append(input);
			var TDtitulo = $('<td>').attr({id: 'fraseautor_'+codigo}).html(titulo);
			var TDorden = $('<td>').attr({style:'text-align: center'}).html(aOrden);
			var TDeditar = $('<td>').attr({style:'text-align: center'}).html(aEditar);
			var TDeliminar = $('<td>').attr({style:'text-align: center'}).html(aEliminar);
	
			var tr = $('<tr>').attr({id: 'trfrasecod_'+codigo});
			tr.append(TDcodigo);
			tr.append(TDtitulo);
			tr.append(TDorden);
			tr.append(TDeditar);
			tr.append(TDeliminar);
			$("#TituloFraseTabla").append(tr);
		
			MovimientoOrden();
			LimpiarDatos();
		}else
			alert("La frase ya se encuentra dentro del carrousel.")
	}
	
	
	function Eliminar(codigo)
	{
		if (!confirm("Esta seguro que desea eliminar la frase?"))
			return false;
		$("#trfrasecod_"+codigo).remove();
	}
	
	function Editar(codigo)
	{
		$("#TituloFrase").html($("#fraseautor_"+codigo).html());
		$("#frasecod").val(codigo);
		
		MostrarBtModif();
	}
	
	
	function MostrarBtModif()
	{
		$("#AgregarFrase").hide();
		$("#ModificarFrase").show();
		$("#CancelarFrase").show();
	}
	function OcultarBtModif()
	{
		$("#AgregarFrase").show();
		$("#ModificarFrase").hide();
		$("#CancelarFrase").hide();
		
	}
	
	function LimpiarDatos()
	{
		$("#TituloFrase").html("");
		$("#frasecod").val("");
	}
	
	function CancelarAccionModif()
	{
		OcultarBtModif();
		LimpiarDatos();
	}
	 
	var timeoutHnd; 
	function SearchFrases(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarFrase,500) 
	}
	
	function BuscarFrase()
	{
		var param, url;
		$("#frasesLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando Frases...</td></div></tr>');
		param = "fraseautor="+$("#fraseautor").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_frases_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#frasesLstData").html(msg);
		   }
		 });
	}
	
	
	function MovimientoOrden()
	{	
		$("#TituloFraseTabla").sortable(
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