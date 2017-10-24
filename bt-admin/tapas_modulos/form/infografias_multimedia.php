<? 
//print_r($vars);
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$ColorFlecha = "#000000";
$Fuente=12;
$Link = "";
$Target = "_self";
$Flecha= "fa-arrow-up";
$Alineacion = "centro";
$muestroagregar=false;
$multimediacod="";
$multimediacod2="";
$tituloImg="";
$tituloImg2="";
$Titulo1="";
$Titulo2="";
$Top = 0;
$Left = 0;
$Right = 0;
$Bottom = 0;

$Top2 = 0;
$Left2 = 0;
$Right2 = 0;
$Bottom2 = 0;
$oMultimedia = new cMultimedia($conexion,"");
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	$muestroagregar=true;
	if (isset($objDataModel->Titulo1))
		$Titulo1 = utf8_decode($objDataModel->Titulo1);
	if (isset($objDataModel->Titulo2))
		$Titulo2 = utf8_decode($objDataModel->Titulo2);
	if (isset($objDataModel->ColorFlecha))
		$ColorFlecha = utf8_decode($objDataModel->ColorFlecha);
	if (isset($objDataModel->Link))
		$Link = utf8_decode($objDataModel->Link);
	if (isset($objDataModel->Target))
		$Target = utf8_decode($objDataModel->Target);
	if (isset($objDataModel->Flecha))
		$Flecha = utf8_decode($objDataModel->Flecha);
	if (isset($objDataModel->Alineacion))
		$Alineacion = utf8_decode($objDataModel->Alineacion);
	if (isset($objDataModel->Fuente))
		$Fuente = utf8_decode($objDataModel->Fuente);
	if (isset($objDataModel->Top))
		$Top = utf8_decode($objDataModel->Top);
	if (isset($objDataModel->Left))
		$Left = utf8_decode($objDataModel->Left);
	if (isset($objDataModel->Right))
		$Right = utf8_decode($objDataModel->Right);
	if (isset($objDataModel->Bottom))
		$Bottom = utf8_decode($objDataModel->Bottom);


	if (isset($objDataModel->Top2))
		$Top2 = utf8_decode($objDataModel->Top2);
	if (isset($objDataModel->Left2))
		$Left2 = utf8_decode($objDataModel->Left2);
	if (isset($objDataModel->Right2))
		$Right2 = utf8_decode($objDataModel->Right2);
	if (isset($objDataModel->Bottom2))
		$Bottom2 = utf8_decode($objDataModel->Bottom2);
		
	if (isset($objDataModel->multimediacod))
		$multimediacod = utf8_decode($objDataModel->multimediacod);
	if (isset($objDataModel->multimediacod2))
		$multimediacod2 = utf8_decode($objDataModel->multimediacod2);

	if ($multimediacod!="")
	{
		$datos["multimediacod"]=$multimediacod;
		if (!$oMultimedia->BuscarMultimediaxCodigo($datos,$resultadoM,$numfilasM))
			die();	
		if ($numfilasM>0)
		{
			$filaM=$conexion->ObtenerSiguienteRegistro($resultadoM);
			$img = cMultimedia::DevolverDireccionImg($filaM);
			if (!isset($filaM["multimediatitulo"]) || $filaM["multimediatitulo"]=="")
				$filaM["multimediatitulo"]=$filaM["multimedianombre"];
			$tituloImg = $filaM['multimediatitulo'];
		}
	}	
		
	if ($multimediacod2!="")
	{
		$datos["multimediacod"]=$multimediacod2;
		if (!$oMultimedia->BuscarMultimediaxCodigo($datos,$resultadoM,$numfilasM))
			die();	
		if ($numfilasM>0)
		{
			$filaM=$conexion->ObtenerSiguienteRegistro($resultadoM);
			if (!isset($filaM["multimediatitulo"]) || $filaM["multimediatitulo"]=="")
				$filaM["multimediatitulo"]=$filaM["multimedianombre"];
			$img2 = cMultimedia::DevolverDireccionImg($filaM);
			$tituloImg2 = $filaM['multimediatitulo'];
		}
	}	
		
		
}

$sidx ="multimediacod"; 
$sord ="DESC";
$datos['multimediaconjunto']=1;
$datos['multimediaestadocod']=ACTIVO;
$datos['limit'] = "LIMIT 0,20";
$datos['orderby'] = $sidx." ".$sord;

if (!$oMultimedia->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

?>
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css?v=1.1" />
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>

<div id="tabs">
    <ul>
        <li><a href="#datosgenerales"><span>Datos Generales</span></a></li>
        <li><a href="#fondo"><span>Fondo</span></a></li>
        <li><a href="#logo"><span>Logo</span></a></li>
    </ul>

<div style="text-align:left" id="datosgenerales">
	<? if ($muestroagregar){?>
        <div style="float:left; width:100px;">
            <label for="Modulo" style="font-weight:bold">Modulo</label>
        </div>
        <div style="float:left; width:100px;">
            #modulo_ancla_<? echo $vars['zonamodulocod']?>
        </div>
        <div style="clear:both; margin:10px 0"></div>
	<? }?>
    <div style="float:left; width:100px">
    	<label style="font-weight:bold">Titulo:</label>
    </div>
	<div style="float:left; width:550px;">
    	<input type="text" style="width:100%;" value="<? echo $Titulo1?>" id="Titulo1" name="Titulo1" maxlength="150" size="50" />
    </div>
	<div style="clear:both; margin:2px 0;">&nbsp;</div>
    <div style="float:left; width:100px">
    	<label style="font-weight:bold">Titulo Destacado:</label>
    </div>
	<div style="float:left; width:550px;">
    	<input type="text" style="width:100%;" value="<? echo $Titulo2?>" id="Titulo2" name="Titulo2" maxlength="150" size="50" />
    </div>
	<div style="clear:both; margin:2px 0;">&nbsp;</div>
    <div>
    	<label style="font-weight:bold">Posici&oacute;n del titulo:</label>
    </div>
	<div style="clear:both;">&nbsp;</div>
    <div style="float:left; width:80px">
    	<label style="font-weight:bold">Arriba:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Top?>" id="Top" name="Top" maxlength="150" size="50" />
    </div>
    <div style="float:left; width:80px; margin-left:10px;">
    	<label style="font-weight:bold">Izquierda:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Left?>" id="Left" name="Left" maxlength="150" size="50" />
    </div>
    <div style="float:left; width:80px; margin-left:10px;">
    	<label style="font-weight:bold">Derecha:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Right?>" id="Right" name="Right" maxlength="150" size="50" />
    </div>
    <div style="float:left; width:80px; margin-left:10px;">
    	<label style="font-weight:bold">Abajo:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Bottom?>" id="Bottom" name="Bottom" maxlength="150" size="50" />
    </div>
   	<div style="clear:both; margin:2px 0;">&nbsp;</div>

    <div>
    	<label style="font-weight:bold">Posici&oacute;n del Logo:</label>
    </div>
	<div style="clear:both;">&nbsp;</div>
    <div style="float:left; width:80px">
    	<label style="font-weight:bold">Arriba:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Top2?>" id="Top2" name="Top2" maxlength="150" size="50" />
    </div>
    <div style="float:left; width:80px; margin-left:10px;">
    	<label style="font-weight:bold">Izquierda:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Left2?>" id="Left2" name="Left2" maxlength="150" size="50" />
    </div>
    <div style="float:left; width:80px; margin-left:10px;">
    	<label style="font-weight:bold">Derecha:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Right2?>" id="Right2" name="Right2" maxlength="150" size="50" />
    </div>
    <div style="float:left; width:80px; margin-left:10px;">
    	<label style="font-weight:bold">Abajo:</label>
    </div>
	<div style="float:left; width:30px;">
    	<input type="text" style="width:100%;" value="<? echo $Bottom2?>" id="Bottom2" name="Bottom2" maxlength="150" size="50" />
    </div>
   	<div style="clear:both; margin:2px 0;">&nbsp;</div>


    <div>
    	<label style="font-weight:bold">Configuraci&oacute;n Flecha:</label>
    </div>
	<div style="clear:both;">&nbsp;</div>
    <div style="float:left; width:100px">
    	<label style="font-weight:bold">Link:</label>
    </div>
	<div style="float:left; width:400px;">
    	<input type="text" style="width:100%;" value="<? echo $Link?>" id="Link" name="Link" maxlength="150" size="50" />
    </div>
	<div style="float:left; width:200px; margin-left:10px;">
        <select name="Target"  style="width:100%;" id="Target">
            <option value="_blank" <? if ($Target=="_blank") echo 'selected="selected"'?> >Abrir en otra ventana</option>
            <option value="_self" <? if ($Target=="_self") echo 'selected="selected"'?>>Abir en la misma ventana</option>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
    <div style=" float:left; width:120px;  margin-left:10px;">
        <label for="Fuente" style="font-weight:bold">Tama&ntilde;o Flecha</label>
    </div>
    <div style="float:left; width:50px">
        <select name="Fuente" id="Fuente">
            <option value="1.00" <? if ($Fuente=="1.00") echo 'selected="selected"'?>>12</option>
            <option value="1.167" <? if ($Fuente=="1.167") echo 'selected="selected"'?>>14</option>
            <option value="1.333" <? if ($Fuente=="1.333") echo 'selected="selected"'?>>16</option>
            <option value="1.500" <? if ($Fuente=="1.500") echo 'selected="selected"'?>>18</option>
            <option value="1.667" <? if ($Fuente=="1.667") echo 'selected="selected"'?>>20</option>
            <option value="1.833" <? if ($Fuente=="1.833") echo 'selected="selected"'?>>22</option>
            <option value="2.00" <? if ($Fuente=="2.00") echo 'selected="selected"'?>>24</option>
            <option value="2.167" <? if ($Fuente=="2.167") echo 'selected="selected"'?>>26</option>
            <option value="2.333" <? if ($Fuente=="2.333") echo 'selected="selected"'?>>28</option>
            <option value="2.500" <? if ($Fuente=="2.500") echo 'selected="selected"'?>>30</option>
            <option value="2.667" <? if ($Fuente=="2.667") echo 'selected="selected"'?>>32</option>
            <option value="2.833" <? if ($Fuente=="2.833") echo 'selected="selected"'?>>34</option>
            <option value="3.00" <? if ($Fuente=="3.00") echo 'selected="selected"'?>>36</option>
        </select>
    </div>
    <div style="float:left; width:100px; text-align:right;">
        <label for="ColorFlecha" style="font-weight:bold">Color Flecha</label>
    </div>
    <div style="float:left; width:100px; margin-left:10px;">
        <input type="text" maxlength="7" size="7" class="colorpicker" id="ColorFlecha" name="ColorFlecha" value="<? echo $ColorFlecha?>">
    </div>
    <div style="float:left; width:100px; text-align:right; margin-left:10px;">
        <label for="Flecha" style="font-weight:bold">Mirando hacia</label>
    </div>
    <div style="float:left; width:100px; margin-left:10px;">
        <select name="Flecha" id="Flecha">
            <option value="fa-chevron-up" <? if ($Flecha=="fa-chevron-up") echo 'selected="selected"'?>>Arriba</option>
            <option value="fa-chevron-down" <? if ($Flecha=="fa-chevron-down") echo 'selected="selected"'?>>Abajo</option>
            <option value="fa-chevron-right" <? if ($Flecha=="fa-chevron-right") echo 'selected="selected"'?>>Derecha</option>
            <option value="fa-chevron-left" <? if ($Flecha=="fa-chevron-left") echo 'selected="selected"'?>>Izquierda</option>
        </select>
    </div>
    <div style="float:left; width:100px; margin-left:10px;">
        <select name="Alineacion" id="Alineacion">
            <option value="centro" <? if ($Alineacion=="centro") echo 'selected="selected"'?>>Centro</option>
            <option value="izquierda" <? if ($Alineacion=="izquierda") echo 'selected="selected"'?>>Izquierda</option>
            <option value="derecha" <? if ($Alineacion=="derecha") echo 'selected="selected"'?>>Derecha</option>
        </select>
    </div>
    <div style="clear:both; margin:5px 0"></div>
</div>

<div style="text-align:left" id="fondo">
    <div style="margin-top:15px">
         <div style="float:left; width:100px; text-align:left;">
              <label>Titulo</label>
         </div>
         <div style="float:left; width:500px; margin-left:10px;">
              <div style="float:left; width:300px;margin-left:10px;">	
              <input type="text" name="multimediatitulo" id="multimediatitulo" style="width:100%" value="" />
              </div>
              <div style="float:left; width:100px;margin-left:10px;">	
                <input type="button" name="btn_buscar" id="btn_buscar" onclick="BuscarMultimedias('multimediaLstData','multimediatitulo')" value="Buscar"/>
             </div>
         </div>
     </div>
	<div style="clear:both"></div>    
    <div style="float:left; width:100px">
    	<label style="font-weight:bold">Im&aacute;gen:</label>
    	<input type="hidden" value="<? echo $multimediacod?>" id="multimediacod" name="multimediacod" />
    </div>
    <div style="float:left; width:500px; margin-left:10px;" id="TituloMultimedia">
        <? echo $tituloImg;?>
    </div>
    <div style="clear:both; margin:10px 0"></div>
    <div style="height:300px; overflow-y:scroll">
      <table style="width:100%" class="tableseleccion">
       <tr>
           <th width="10%">C&oacute;digo</th>
           <th width="30%">Foto</th>
           <th width="30%">Titulo</th>
           <th width="20%">Agregar</th>
       </tr> 
       <tbody id="multimediaLstData">
        <?  if ($numfilas>0)
        {
           
            while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
            {
                if (!isset($fila["multimediatitulo"]) || $fila["multimediatitulo"]=="")
                    $fila["multimediatitulo"]=$fila["multimedianombre"];
                //$imagen = '<img src="'.cMultimedia::DevolverDireccionImg($fila).'" style="max-width:60px;" width="60" alt="Imagen" />'; 
				$imagen = '<img src="'.$oMultimedia->DevolverDireccionImg($fila).'" style="max-width:60px;" width="60" alt="Imagen" />';
                ?>
                <tr>
                    <td style="text-align:center"><? echo $fila['multimediacod'] ?></td>
                    <td style="text-align:center"><? echo $imagen; ?></td>
                    <td style="text-align:left" id="multimediatitulo_<? echo $fila['multimediacod']?>"><? echo htmlspecialchars($fila['multimediatitulo'],ENT_QUOTES); ?></td>
                    <td style="text-align:center; font-weight:bold;">
                       <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarMultimedia(<? echo $fila['multimediacod'] ?>)">&nbsp;</a>
                    </td>
                </tr> 
            <?
            }
			$vars['conexion']->MoverPunteroaPosicion($resultado,0);
        }
        ?>
        </tbody>            
	 </table> 
	</div>
</div> 


<div style="text-align:left" id="logo">
    <div style="margin-top:15px">
         <div style="float:left; width:100px; text-align:left;">
              <label>Titulo</label>
         </div>
         <div style="float:left; width:500px; margin-left:10px;">
              <div style="float:left; width:300px;margin-left:10px;">	
              <input type="text" name="multimediatitulo2" id="multimediatitulo2" style="width:100%" value="" />
              </div>
              <div style="float:left; width:100px;margin-left:10px;">	
                <input type="button" name="btn_buscar2" id="btn_buscar2" onclick="BuscarMultimedias('multimediaLstData2','multimediatitulo2')" value="Buscar"/>
             </div>
         </div>
     </div>
	<div style="clear:both"></div>    
    <div style="float:left; width:100px">
    	<label style="font-weight:bold">Im&aacute;gen:</label>
    	<input type="hidden" value="<? echo $multimediacod2?>" id="multimediacod2" name="multimediacod2" />
    </div>
    <div style="float:left; width:500px; margin-left:10px;" id="TituloMultimedia2">
        <? echo $tituloImg2;?>
    </div>
    <div style="clear:both; margin:10px 0"></div>
    <div style="height:300px; overflow-y:scroll">
      <table style="width:100%" class="tableseleccion">
       <tr>
           <th width="10%">C&oacute;digo</th>
           <th width="30%">Foto</th>
           <th width="30%">Titulo</th>
           <th width="20%">Agregar</th>
       </tr> 
       <tbody id="multimediaLstData2">
        <?  if ($numfilas>0)
        {
           
            while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
            {
                if (!isset($fila["multimediatitulo"]) || $fila["multimediatitulo"]=="")
                    $fila["multimediatitulo"]=$fila["multimedianombre"];
                //$imagen = '<img src="'.cMultimedia::DevolverDireccionImg($fila).'" style="max-width:60px;" width="60" alt="Imagen" />'; 
				$imagen = '<img src="'.$oMultimedia->DevolverDireccionImg($fila).'" style="max-width:60px;" width="60" alt="Imagen" />';
                ?>
                <tr>
                    <td style="text-align:center"><? echo $fila['multimediacod'] ?></td>
                    <td style="text-align:center"><? echo $imagen; ?></td>
                    <td style="text-align:left" id="multimediatitulo_<? echo $fila['multimediacod']?>"><? echo htmlspecialchars($fila['multimediatitulo'],ENT_QUOTES); ?></td>
                    <td style="text-align:center; font-weight:bold;">
                       <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarMultimedia(<? echo $fila['multimediacod'] ?>)">&nbsp;</a>
                    </td>
                </tr> 
            <?
            }
        }
        ?>
        </tbody>            
	 </table> 
	</div>
</div> 
<div style="clear:both; height:30px;">&nbsp;</div>
<div class="menucarga" style="text-align:right">
    <ul>
        <li>
            <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            <? if ($muestroagregar) {  ?>
                <a href="javascript:void(0)" onclick="saveModuloSinRecarga()">Guardar y Recargar</a>
            <? }?>
        </li>
    </ul>
</div>           




<script type="text/javascript">
	
	jQuery(document).ready(function(){
		$('#tabs').tabs({ selected: 0 });
		$(".colorpicker").miniColors();
	});
	

function AgregarMultimedia(cod)
{
	var $tabs = $('#tabs').tabs();
	var selected = $tabs.tabs('option', 'selected');
	if (selected==1)
	{
		$("#multimediacod").val(cod);
		$("#TituloMultimedia").html($("#multimediatitulo_"+cod).html());
	}else
	{
		$("#multimediacod2").val(cod);
		$("#TituloMultimedia2").html($("#multimediatitulo_"+cod).html());
	}
}


function BuscarMultimedias(tipo,titbuscador)
{
	var param, url;
	$("#"+tipo).html('<tr><td colspan="4" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando imagenes...</td></div></tr>');
	param = "multimedianombre="+$("#"+titbuscador).val();
	param += "&multimediaconjuntocod=1";
	$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_multimedia_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#"+tipo).html(msg);
		   }
	 });
}

</script>