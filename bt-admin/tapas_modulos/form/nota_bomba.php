<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$noticiacod="";
$Titulo = "";
$conCategoria=0;
$conAudio = 0;
$conFoto = 0;
$conHora = 0;
$BordeIzquierdo = 0;
$conFondo = 0;
$conVolanta = 0;
$conPais = 0;
$conBajada = 0;
$conCuerpo = 0;
$conFecha = 0;
$conVinculadas = 0;
$titulonoticia = "";
$BordeInferior=0;
$Top=0;
$conRedesSociales=0;
$noticiaTipo=4;
$FondoGris=0;
$FondoCeleste=0;
$Multimediacod=0;
/*
$ColorTitulo= "";
$TamTitulo= "";*/
$FondoTitulo="fondoclaro";
$datosbusqueda['usuariocod '] = $_SESSION['usuariocod'];

$conVideo=0;
$IconoVideo=0;


?>
<script type="text/javascript">
function Validar(titulonoticia)
{
		if (titulonoticia.value=="")
	{
		alert("Debe ingresar el nombre del archivo");
		
		return false;
	}
}
</script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<?php 


function CargarCategorias($arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option value="<?php  echo $fila['catcod']?>"><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php  
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}
$oCategorias = new cCategorias($vars['conexion']);
if(!$oCategorias->ArmarArbolCategorias("",$arbol))
	die();

$muestroagregar=false;
$edita = false;
$tab = 0;
$oMultimedia = new cMultimedia($conexion,"");

if (isset($vars['zonamodulocod']))
{
	$edita=true;
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	$tab = 1;

	if (isset($objDataModel->Top))
		$Top = $objDataModel->Top;
	if (isset($objDataModel->conCategoria))
		$conCategoria = 1;
	if (isset($objDataModel->conAudio))
		$conAudio = 1;
	if (isset($objDataModel->conFoto))
		$conFoto = 1;
	if (isset($objDataModel->conVolanta))
		$conVolanta = 1;

	if (isset($objDataModel->conBajada))
		$conBajada = 1;
	if (isset($objDataModel->conFondo))
		$conFondo = 1;
	if (isset($objDataModel->conCuerpo))
		$conCuerpo = 1;		
	if (isset($objDataModel->conHora))
		$conHora = 1;		
	if (isset($objDataModel->conVinculadas))	
		$conVinculadas = 1;
	
	if (isset($objDataModel->BordeInferior))	
		$BordeInferior = 1;
	
	if (isset($objDataModel->conFoto))	
		$conFoto = 1;

	if (isset($objDataModel->conRedesSociales))	
		$conRedesSociales = 1;
	
	if (isset($objDataModel->noticiaTipo))	
		$noticiaTipo = $objDataModel->noticiaTipo;

	if (isset($objDataModel->multimediacod))	
		$Multimediacod = $objDataModel->multimediacod;

	if (isset($objDataModel->conFecha))	
		$conFecha = 1;

	if (isset($objDataModel->conVideo))	
		$conVideo = 1;
	if (isset($objDataModel->IconoVideo))	
		$IconoVideo = 1;
	
	/*
	if (isset($objDataModel->ColorTitulo))
		$ColorTitulo = $objDataModel->ColorTitulo;	
	
	if (isset($objDataModel->TamTitulo))
		$TamTitulo = $objDataModel->TamTitulo;
	*/
	
	if (isset($objDataModel->FondoTitulo))
		$FondoTitulo = $objDataModel->FondoTitulo;	
	
	$noticiacod = $objDataModel->noticiacod;
	$datosbusqueda['noticiacod'] = $noticiacod;
	$oNoticia = new cNoticias($vars['conexion']);
	if(!$oNoticia->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
		return false;
	
	$datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
	$titulonoticia = $datosnoticia['noticiatitulo'];

	if (isset($objDataModel->Titulo) && $objDataModel->Titulo!="")
		$Titulo = utf8_decode($objDataModel->Titulo);
	else
		$Titulo = $titulonoticia;
}

$oNoticias= new cNoticias($vars['conexion']);
$datos=array(); 
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['orderby'] = "noticiacod desc";
$datos['noticiaestadocod'] = NOTPUBLICADA;
$datos['limit'] = "LIMIT 0,20";

if(!$oNoticias->BusquedaAvanzada($datos,$resultadonoticias,$numfilas)) {
	$error = true;
}


?>
<div id="tabs">
    <ul>
        <li><a href="#lstNoticiatab"><span>Buscador de noticia</span></a></li>
        <li><a href="#dataTab"><span>Noticia Seleccionada</span></a></li>
    </ul>


    <div id="lstNoticiatab">
        <div style="min-width:700px;">
            <div style="text-align:left;">
                <h2 class="titulopopup">Listado de Noticias</h2>
            </div>
            <div style="padding-top:10px">
                <div style="float:left; width:180px; text-align:left;">
                	<label>T&iacute;tulo</label><input type="text" style="width:100%;" id="noticiatitulodescnotSimple" name="noticiatitulodescnotSimple" >
                </div>
                <div style="float:left; width:200px; margin-left:55px; margin-top: 12px;">
                    <select name="catcod" id="catcod" class="chzn-select-categorias">
                        <option value="">Seleccionar una categoria...</option>
                        <?php 
                        foreach($arbol as $fila)
                        {
                            ?>
                            <option value="<?php  echo $fila['catcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                            <?php  
                            if (isset($fila['subarbol']))
                            {
                                $nivel = "---";
                                CargarCategorias($fila['subarbol'],$nivel);
                            }
                        }
                        ?>
                     </select>
                    
                </div>
                
                 <div style="float:left; width:100px;margin-left:10px;">	
                 	<input type="button" name="btn_buscar" id="btn_buscar" onclick="SearchNotice(arguments[0]||event)" value="Buscar"/>
                 </div>
                <div style="clear:both; height:1px;">&nbsp;</div>
                <div style="height:280px; overflow-y:auto; margin-top:5px;">
                    <table style="width:95%" class="tableseleccion">
                    
                             <tr>
                                <th width="10%">C&oacute;digo</th>
                                <th width="60%">Titulo</th>
                                <th width="20%">Agregar</th>
                             </tr> 
                             <tbody id="noticiasLstData">
                  <?php   if ($numfilas>0)
                    {
                        
                        while ($fila = $conexion->ObtenerSiguienteRegistro($resultadonoticias))
                        {
                    ?>
                                <tr>
                                        <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiacod'],ENT_QUOTES); ?></td>
                                        <td style="text-align:left" id="noticiatitulo_<?php  echo $fila['noticiacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES); ?></td>
                                        <td style="text-align:center; font-weight:bold;">
                                           <a class="left add_noticia" style="margin-left:15px" href="javascript:void(0)" onclick="AgregarNoticia(<?php  echo $fila['noticiacod']?>)">&nbsp;</a>
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
    <div id="dataTab" class="select">
    
        <div style="text-align:left; margin-top:5px;">
            <div>
                <div id="TituloNoticia" style=" font-size:16px; ext-align:left; font-weight:bold"><?php  echo $titulonoticia?></div>
                <div style="clear:both">&nbsp;</div>
                <input type="hidden" name="noticiacod" id="noticiacod" value="<?php  echo $noticiacod?>">
                <input type="hidden" name="Top" id="Top" value="<?php  echo $Top?>">
            </div>
            <div>
                <div style="float:left; width:430px;">
                	<div style="font-size:14px;margin-bottom:3px; clear:both;"><b>Datos Generales</b></div>
                    <div style="float:left; width:400px; margin-bottom:5px;">
	                    <label for="Titulo"> Titulo</label>: <input type="text" name="Titulo" id="Titulo" value="<?php  echo $Titulo?>" size="50"></div>
                    
                    <div style="clear:both; height:10px;"></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conVolanta" id="conVolanta"<?php  if ($conVolanta==1) echo 'checked="checked"'?>><label for="conVolanta"> Volanta</label></div>
                    <?php  /*<div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conBajada" id="conBajada"  <?php  if ($conBajada==1) echo 'checked="checked"'?>  ><label for="conBajada"> Bajada</label></div>*/?>
                      <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conCategoria" id="conCategoria"<?php  if ($conCategoria==1) echo 'checked="checked"'?>><label for="conCategoria"> Categoria</label></div>
                    <div style="clear:both; height:1px;"></div>
    
                	<div style="font-size:14px; margin-bottom:3px; clear:both;"><b>Configuraci&oacute;n de la noticia</b></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conFoto" id="conFoto" <?php  if ($conFoto==1) echo 'checked="checked"'?>><label for="conFoto"> Foto</label></div>
                    <?php  /*
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conVideo" id="conVideo"  <?php  if ($conVideo==1) echo 'checked="checked"'?>  ><label for="conVideo"> Video</label></div> */ ?>
                     <div style="float:left; width:150px; margin-bottom:5px;"><input type="checkbox" name="IconoVideo" id="IconoVideo" <?php  if ($IconoVideo==1) echo 'checked="checked"'?>><label for="IconoVideo"> &iacute;cono Video en Foto</label></div>
                    <?php  /*<div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conAudio" id="conAudio" <?php  if ($conAudio==1) echo 'checked="checked"'?>><label for="conAudio"> Audio</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conRedesSociales" id="conRedesSociales" <?php  if ($conRedesSociales==1) echo 'checked="checked"'?>><label for="conRedesSociales"> Redes Sociales</label></div>*/?>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conVinculadas"  id="conVinculadas"  <?php  if ($conVinculadas==1) echo 'checked="checked"'?>><label for="conVinculadas"> Vinculadas</label></div>
                   
                    <div style="clear:both; height:10px;"></div>
    
                	<div style="font-size:14px; margin-bottom:3px; clear:both;"><b>Configuraci&oacute;n de la noticia</b></div>
                    <?php  /*
                    <div style="float:left; width:30%">
                        <label>Color T&iacute;tulo:</label>
                    </div>
                    <div style="float:left; width:70%;">
                        <select id="ColorTitulo" name="ColorTitulo" >
                        	<option value="" <?php  if ($ColorTitulo=="") echo 'selected="selected"'?> >Default</option>
                            <option value="#cc0079" <?php  if ($ColorTitulo=="#cc0079") echo 'selected="selected"'?> >Magenta</option>
                            <option value="#583E67" <?php  if ($ColorTitulo=="#583E67") echo 'selected="selected"'?> >Violeta</option>
                            <option value="#2082BC" <?php  if ($ColorTitulo=="#2082BC") echo 'selected="selected"'?> >Cyan</option>
                        </select>
                    </div>
                    
                    <div style="clear:both; height:10px;"></div>
                    
                    <div style="float:left; width:30%">
                        <label>Tama&ntilde;o T&iacute;tulo:</label>
                    </div>
                    <div style="float:left; width:70%;">
                        <select id="TamTitulo" name="TamTitulo" >
                        	<option value="" <?php  if ($TamTitulo=="") echo 'selected="selected"'?> >Default</option>
                            <option value="1.5em" <?php  if ($TamTitulo=="1.5em") echo 'selected="selected"'?> >Grande</option>
                            <option value="2em" <?php  if ($TamTitulo=="2em") echo 'selected="selected"'?> >Muy Grande</option>
                        </select>
                    </div>
                    */?>
                    <div style="clear:both; height:10px;"></div>

                    <div style="float:left; width:30%">
                        <label>Color Fondo Texto:</label>
                    </div>
                    <div style="float:left; width:70%;">
                        <select id="FondoTitulo" name="FondoTitulo" >
                            <option value="fondoclaro" <?php  if ($FondoTitulo=="fondoclaro") echo 'selected="selected"'?> >Claro</option>
                            <option value="fondooscuro" <?php  if ($FondoTitulo=="fondooscuro") echo 'selected="selected"'?> >Oscuro</option>
                        </select>
                    </div>
                    

                </div>
                <div style="float:left; width:20px;">&nbsp;</div>
                <div style="float:left; width:250px;" id="MultimediaPreview">
                    <?php  if ($edita){?>
                    <?php  }else{?>
                        &nbsp;
                    <?php  }?>
                </div>
                
            <div style="clear:both;"></div>
        </div>


        <div style="clear:both;"></div>
        <div class="menucarga" style="text-align:left">
            <ul>
                <li>
                    <a href="javascript:void(0)" onclick="Validar()">Guardar y Cerrar</a>
                    <?php  if (!$muestroagregar) {   ?>
                    <a href="javascript:void(0)" onclick="ValidarAgregar()">Guardar y Agregar otro</a>
                    <?php  } ?>
                </li>
            </ul>
        </div>  
        <div style="text-align:left; font-size:10px;">
        	<b>Recuerde</b>: En caso de elegir foto y video el sistema cargar&aacute; el video ya que tiene prioridad sobre la foto.
        </div>
          
	</div>
    
</div>


<script type="text/javascript">

	jQuery(document).ready(function(){
		$('#tabs').tabs({ selected: <?php  echo $tab?> });
	});


	<?php  if ($edita && $Multimediacod!=""){?>
		jQuery(document).ready(function(){
			CargarMultimediaSeleccionado();
		});
	<?php  }elseif($edita){?>
		BuscarMultimedia()
	<?php  }?>
	function Validar()
	{
		if($("#noticiacod").val()=='')
		{
			alert("Debe seleccionar una noticia");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#noticiacod").val()=='')
		{
			alert("Debe seleccionar una noticia");
			
		}else{
			agregarModulo();
		}
		return true;
	}
	
	function AgregarNoticia(codigo)
	{
		$("#TituloNoticia").html($("#noticiatitulo_"+codigo).html());
		$("#Titulo").val($("#noticiatitulo_"+codigo).html());
		$("#modulonombre").val($("#noticiatitulo_"+codigo).html());  //LINEA AGREGADA
		$("#noticiacod").val(codigo);
		BuscarMultimedia();
		$('#tabs').tabs('select', 1);
	}
	 
	var timeoutHnd; 
	function SearchNotice(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarNoticia,500) 
	}
	
	function BuscarNoticia()
	{
		var param, url;
		$("#noticiasLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando noticias...</td></div></tr>');
		param = "noticiatitulodesc="+$("#noticiatitulodescnotSimple").val();
		param += "&catcod="+$("#catcod").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_noticias_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#noticiasLstData").html(msg);
		   }
		 });
	}


	function BuscarMultimedia()
	{
		var param, url;
		$("#MultimediaPreview").html('<img src="images/cargando.gif" />&nbsp;Cargando imagenes...');
		param = "noticiacod="+$("#noticiacod").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_carga_multimedia_noticias_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#MultimediaPreview").html(msg);
		   }
		 });
	}


	<?php  if ($Multimediacod!=""){?>
	function CargarMultimediaSeleccionado()
	{
		var param, url;
		$("#MultimediaPreview").html('<img src="images/cargando.gif" />&nbsp;Cargando imagenes...');
		param = "noticiacod="+$("#noticiacod").val();
		param += "&multimediacod="+<?php  echo $Multimediacod?>;
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_carga_multimedia_noticias_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#MultimediaPreview").html(msg);
		   }
		 });
	}
	<?php  }?>


</script>