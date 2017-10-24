<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$galeriacod = "";
$galeriatitulo="";
$AutoPlay = 0;
$MuestraBotonera = 1;
$MuestraPaginador = 0;
$MuestraImgGrande = 1;
$oGalerias = new cGalerias($vars['conexion']);
$muestroagregar=false;

if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	$galeriacod = $objDataModel->galeriacod;
	$AutoPlay = $objDataModel->AutoPlay;
	$MuestraBotonera = $objDataModel->MuestraBotonera;
	$MuestraPaginador = $objDataModel->MuestraPaginador;
	$MuestraImgGrande = $objDataModel->MuestraImgGrande;
	$datosbusqueda['galeriacod'] = $galeriacod;
	if (!$oGalerias->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
		return false;
	$datosgalerias =  $vars['conexion']->ObtenerSiguienteRegistro($resultado);
	$galeriatitulo = $datosgalerias['galeriatitulo'];
}


$datos = array();
if (!$oGalerias->BuscarAvanzadaxGaleria ($datos,$resultadogalerias,$numfilas))
	die();
?>
<div id="contentNews">
    <div>
        <div style="float:left; width:40%; text-align:left;">Galeria Seleccionada:</div>
        <div id="TituloGaleria" style="float:left; width:60%; text-align:left; font-weight:bold"><?php  echo $galeriatitulo?></div>
		<input type="hidden" name="galeriacod" id="galeriacod" value="<?php  echo $galeriacod?>">
        <div style="clear:both">&nbsp;</div>
    </div>
    <div id="divTitleListNews" style="text-align:left;">
        <span id="titleListNews">Listado de Galerias</span>
    </div>
    <div id="newsSearchContent">
        <div style="float:left;">Descripci&oacute;n:</div> <input type="text" style="float:left;" id="galeriadesc" name="galeriadesc" onkeypress="SearchGalerias()">
        <div style="clear:both">&nbsp;</div>
		<div style="height:200px; overflow-y:auto; margin-top:10px;">
            <table style="width:95%" class="tableseleccion">
            
                     <tr>
                        <th width="20%">C&oacute;digo</th>
                        <th width="40%">Titulo</th>
                        
                        <th width="20%">Agregar</th>
                     </tr> 
                     <tbody id="galeriaLstData">
          <?php   if ($numfilas>0)
            {
                
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadogalerias))
                {
            ?>
                        <tr>
                                <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriacod'],ENT_QUOTES); ?></td>
                                <td style="text-align:left" id="galeriatitulo_<?php  echo $fila['galeriacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriatitulo'],ENT_QUOTES); ?></td>
                                            
                                <td style="text-align:center; font-weight:bold;">
                                   <a class="left add_image" style="margin-left:15px" href="javascript:void(0)" onclick="AgregarGaleria(<?php  echo $fila['galeriacod']?>)">&nbsp;</a>
     
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
</div>

<div id="attributesContainer">
    <div id="divAttributesContainer">
        <span id="iconAttributes"></span>
        <span id="titleAttributes">Atributos</span>
    </div>
    <div>
        <div style="float:left; width:70%">
            <label>Paginador:</label>
        </div>
        <div style="float:left; width:30%;">
            <select name="MuestraPaginador" id="MuestraPaginador">
                <option value="0" <?php  if ($MuestraPaginador==0) echo 'selected="selected"'?>>No</option>
                <option value="1" <?php  if ($MuestraPaginador==1) echo 'selected="selected"'?>>Si</option>
            </select>
        </div>
        <div style="clear:both">&nbsp;</div>
        <div style="float:left; width:70%">
            <label>Botoneras:</label>
        </div>
        <div style="float:left; width:30%;">
            <select name="MuestraBotonera" id="MuestraBotonera">
                <option value="1" <?php  if ($MuestraBotonera==1) echo 'selected="selected"'?>>Si</option>
                <option value="0" <?php  if ($MuestraBotonera==0) echo 'selected="selected"'?>>No</option>
            </select>
        </div>
        <div style="clear:both">&nbsp;</div>
        <div style="float:left; width:70%">
            <label>AutoPlay:</label>
        </div>
        <div style="float:left; width:30%;">
            <select name="AutoPlay" id="AutoPlay">
                <option value="0" <?php  if ($AutoPlay==0) echo 'selected="selected"'?>>No</option>
                <option value="1" <?php  if ($AutoPlay==1) echo 'selected="selected"'?>>Si</option>
            </select>
        </div>
        <div style="clear:both">&nbsp;</div>
        <div style="float:left; width:70%">
            <label>Abre Im&aacute;gen Grande:</label>
        </div>
        <div style="float:left; width:30%;">
            <select name="MuestraImgGrande" id="MuestraImgGrande">
                <option value="1" <?php  if ($MuestraImgGrande==1) echo 'selected="selected"'?>>Si</option>
                <option value="0" <?php  if ($MuestraImgGrande==0) echo 'selected="selected"'?>>No</option>
            </select>
        </div>
        <div style="clear:both">&nbsp;</div>

    </div>
</div>
        <div style="clear:both">&nbsp;</div>

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
<script type="text/javascript">
	function Validar()
	{
		if($("#galeriacod").val()=='')
		{
			alert("Debe seleccionar una galeria");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#galeriacod").val()=='')
		{
			alert("Debe seleccionar una galeria");
			
		}else{
			agregarModulo();
		}
		return true;
	}	

	function AgregarGaleria(codigo)
	{
		$("#TituloGaleria").html($("#galeriatitulo_"+codigo).html());
		$("#galeriacod").val(codigo);
	}
	
	var timeoutHnd; 
	function SearchGalerias(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarGalerias,500) 
	}
	
	function BuscarGalerias()
	{
		var param, url;
		$("#graficoLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando graficos...</td></div></tr>');
		param = "galeriacod="+$("#galeriadesc").val();
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
</script>