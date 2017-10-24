<?php  
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$noticiacod="";
$Titulo = 1;
$conTitulo=1;
$conCategoria=0;
$conAudio = 0;
$conFoto = 0;
$conHora = 0;
$BordeIzquierdo = 0;
$conFondo = 0;
$conVolanta = 0;
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
$noticiaTitulo ="";
$Texto ="";
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
if (isset($vars['zonamodulocod']))
{
	$edita=true;
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	$tab = 1;
	if (!isset($objDataModel->conTitulo))
		$conTitulo = 0;
	if (isset($objDataModel->Titulo))
		$conTituloLargo = $objDataModel->Titulo;
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
	
	$noticiacod = $objDataModel->noticiacod;
	$datosbusqueda['noticiacod'] = $noticiacod;
	$oNoticia = new cNoticias($vars['conexion']);
	if(!$oNoticia->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
		return false;
	
	$datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
	$titulonoticia = $datosnoticia['noticiatitulo'];
	$noticiacopete = $datosnoticia['noticiacopete'];
	if (isset($objDataModel->noticiaTitulo) && $objDataModel->noticiaTitulo!="")	
		$noticiaTitulo =$objDataModel->noticiaTitulo;
	else
		$noticiaTitulo = utf8_encode($datosnoticia['noticiatitulo']);

	if (isset($objDataModel->Texto) && $objDataModel->Texto!="")	
		$Texto =$objDataModel->Texto;
	else
		$Texto =$noticiacopete;
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
        <li><a href="#dataNot"><span>Datos noticia</span></a></li>
    </ul>

     <div id="dataNot">
        <div style="font-size:14px; margin-bottom:3px; clear:both;"><b>Titulo</b></div>
        <div >
            <input type="text" style="width:100%;" id="noticiaTitulo" name="noticiaTitulo" maxlength="255" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($noticiaTitulo),ENT_QUOTES)?>" />
        </div>
        <div style="font-size:14px; margin:15px 0 3px 0; clear:both;"><b>Bajada</b></div>
        <div >
            <textarea name="Texto" id="Texto"  class="textarea full rich-text" style="width:95%;" cols="30" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Texto),ENT_QUOTES)?></textarea>
        </div>
        <div style=" height:10px; clear:both;">&nbsp;</div>
        <div class="menucarga" style="text-align:left">
            <ul>
                <li>
                    <a href="javascript:void(0)" onclick="CargarOriginales()">Recargar textos de la nota original</a>
                </li>
            </ul>
        </div>  
	</div>   

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
                <div style="float:left; width:30px;">&nbsp;</div>
                <div style="clear:both; height:1px;">&nbsp;</div>

                 <div style="float:left; width:100px;margin:15px 5px;">	
                 	<input type="button" name="btn_buscar" id="btn_buscar" onclick="BuscarNoticia()" value="Buscar"/>
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
										    <input type="hidden" name="noticiacopete_<?php  echo $fila['noticiacod']?>" id="noticiacopete_<?php  echo $fila['noticiacod']?>" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_encode($fila['noticiacopete']), ENT_QUOTES); ?>" />
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
                <div id="CopeteNoticia" style="display:none"><?php  echo utf8_encode($noticiacopete)?></div>
                <div style="clear:both">&nbsp;</div>
                <input type="hidden" name="noticiacod" id="noticiacod" value="<?php  echo $noticiacod?>">
                <input type="hidden" name="Top" id="Top" value="<?php  echo $Top?>">
            </div>
            <div>
                <div style="float:left; width:430px;">
                	<div style="font-size:14px;margin-bottom:3px; clear:both;"><b>Datos Generales</b></div>
                    <?php  /*<div style="float:left; width:120px; margin-bottom:5px;"><input type="radio" name="Titulo" id="conTituloLargo" <?php  if ($Titulo==1) echo 'checked="checked"'?> ><label for="conTituloLargo"> Titulo Largo</label></div>
                     <div style="float:left; width:120px; margin-bottom:5px;"><input type="radio" name="Titulo" id="conTituloCorto" <?php  if ($Titulo==2) echo 'checked="checked"'?>><label for="conTituloCorto">Titulo Corto</label></div> */?>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conVolanta" id="conVolanta"<?php  if ($conVolanta==1) echo 'checked="checked"'?>><label for="conVolanta"> Volanta</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conCategoria" id="conCategoria"<?php  if ($conCategoria==1) echo 'checked="checked"'?>><label for="conCategoria"> Categoria</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conFecha" id="conFecha"  <?php  if ($conFecha==1) echo 'checked="checked"'?>  ><label for="conFecha"> Fecha</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conHora" id="conHora" <?php  if ($conHora==1) echo 'checked="checked"'?>><label for="conHora"> Hora</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conBajada" id="conBajada"  <?php  if ($conBajada==1) echo 'checked="checked"'?>  ><label for="conBajada"> Bajada</label></div>
                    <div style="clear:both; height:1px;"></div>
    
                	<div style="font-size:14px; margin-bottom:3px; clear:both;"><b>Configuraci&oacute;n de la noticia</b></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conFoto" id="conFoto" <?php  if ($conFoto==1) echo 'checked="checked"'?>><label for="conFoto"> Foto</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conVideo" id="conVideo"  <?php  if ($conVideo==1) echo 'checked="checked"'?>  ><label for="conVideo"> Video</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conAudio" id="conAudio" <?php  if ($conAudio==1) echo 'checked="checked"'?>><label for="conAudio"> Audio</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conRedesSociales" id="conRedesSociales" <?php  if ($conRedesSociales==1) echo 'checked="checked"'?>><label for="conRedesSociales"> Redes Soc.</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="conVinculadas"  id="conVinculadas"  <?php  if ($conVinculadas==1) echo 'checked="checked"'?>><label for="conVinculadas"> Vinculadas</label></div>
                    <div style="float:left; width:120px; margin-bottom:5px;"><input type="checkbox" name="BordeInferior" id="BordeInferior"  <?php  if ($BordeInferior==1) echo 'checked="checked"'?>  ><label for="BordeInferior"> BordeInferior</label></div>
                    <div style="float:left; width:150px; margin-bottom:5px;"><input type="checkbox" name="IconoVideo" id="IconoVideo" <?php  if ($IconoVideo==1) echo 'checked="checked"'?>><label for="IconoVideo"> &iacute;cono Video</label></div>
                    <div style="clear:both; height:10px;"></div>

                	<div style="font-size:14px; margin-bottom:3px; clear:both;"><b>Dise&ntilde;o de la noticia</b></div>
                    <div>
                    	<?php  /*
                        <div style="text-align:center; float:left; width:50px;">
                            <div style="border:1px solid #CCC;"><label for="noticiaTipo1"><img src="modulos/tap_tapas/imagenes/titulo_foto_parrafo.jpg" /></label></div>
                            <div style="text-align:center">
                                <input type="radio" <?php  if ($noticiaTipo==1) echo 'checked="checked"'?>  name="noticiaTipo" id="noticiaTipo1" value="1" />
                            </div>
                        </div> 
						*/ ?>
                        <?php  /*
                        <div style="text-align:center; float:left; width:50px; margin-left:5px;">
                            <div style="border:1px solid #CCC;"><label for="noticiaTipo5"><img src="modulos/tap_tapas/imagenes/titulo_foto_parrafo_inf.jpg" /></label></div>
                            <div style="text-align:center">
                                <input type="radio" <?php  if ($noticiaTipo==5) echo 'checked="checked"'?> name="noticiaTipo" id="noticiaTipo5" value="5" />
                            </div>
                        </div>
						
                        <div style="text-align:center; float:left; width:50px; margin-left:5px;">
                            <div style="border:1px solid #CCC;"><label for="noticiaTipo4"><img src="modulos/tap_tapas/imagenes/foto_titulo_parrafo.jpg" /></label></div>
                            <div style="text-align:center">
                                <input type="radio" <?php  if ($noticiaTipo==4) echo 'checked="checked"'?> name="noticiaTipo" id="noticiaTipo4" value="4" />
                            </div>
                        </div>
                        <div style="text-align:center; float:left; width:50px; margin-left:5px;">
                            <div style="border:1px solid #CCC;"><label for="noticiaTipo2"><img src="modulos/tap_tapas/imagenes/fotoizq_titulo_parrafo.jpg" /></label></div>
                            <div style="text-align:center">
                                <input type="radio" <?php  if ($noticiaTipo==2) echo 'checked="checked"'?> name="noticiaTipo" id="noticiaTipo2" value="2" />
                            </div>
                        </div>
						*/ ?>
                        <div style="text-align:center; float:left; width:50px; margin-left:5px;">
                            <div style="border:1px solid #CCC;"><label for="noticiaTipo3"><img src="modulos/tap_tapas/imagenes/not_destacada.jpg" /></label></div>
                            <div style="text-align:center">
                                <input type="radio" <?php  if ($noticiaTipo==3) echo 'checked="checked"'?> name="noticiaTipo" id="noticiaTipo3" value="3" />
                            </div>
                        </div>
                        <div style="clear:both;"></div>
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
	</div>

 
</div>

<div style="clear:both;"></div>
<div class="menucarga" style="text-align:right; margin:10px;">
    <ul>
        <li>
            <a href="javascript:void(0)" onclick="Validar()">Guardar y Cerrar</a>
            <?php  if ($edita) {  ?>
                <a href="javascript:void(0)" onclick="ValidarGuardarRecarga()">Guardar y Recargar</a>
            <?php  }?>
            <?php  if (!$muestroagregar) {   ?>
            <a href="javascript:void(0)" onclick="ValidarAgregar()">Guardar y Agregar otro</a>
            <?php  } ?>
        </li>
    </ul>
</div>  
<div style="text-align:left; font-size:10px;">
    <b>Recuerde</b>: En caso de elegir foto y video el sistema cargar&aacute; el video ya que tiene prioridad sobre la foto.
</div>
      

<script type="text/javascript">

	jQuery(document).ready(function(){
		$('#tabs').tabs({ selected: <?php  echo $tab?> });
		iniciarTextEditors();
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
			var Cuerpo = tinyMCE.get('Texto');
			$("#Texto").val(Cuerpo.getContent());
			saveModulo();
		}
		return true;
	}
	
	function ValidarGuardarRecarga()
	{
		if($("#noticiacod").val()=='')
		{
			alert("Debe seleccionar una noticia");
			
		}else{
			var Cuerpo = tinyMCE.get('Texto');
			$("#Texto").val(Cuerpo.getContent());
			saveModuloSinRecarga();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#noticiacod").val()=='')
		{
			alert("Debe seleccionar una noticia");
			
		}else{
			var Cuerpo = tinyMCE.get('Texto');
			$("#Texto").val(Cuerpo.getContent());
			agregarModulo();
		}
		return true;
	}
	
	function AgregarNoticia(codigo)
	{
		$("#TituloNoticia").html($("#noticiatitulo_"+codigo).html());
		$("#noticiacod").val(codigo);
		$("#noticiaTitulo").val($("#noticiatitulo_"+codigo).html());
		$("#modulonombre").val($("#noticiatitulo_"+codigo).html());  //LINEA AGREGADA
		tinymce.get('Texto').setContent($("#noticiacopete_"+codigo).val());
		$("#CopeteNoticia").html($("#noticiacopete_"+codigo).val());
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



function CargarOriginales()
{
	$("#noticiaTitulo").val($("#TituloNoticia").html());
	tinymce.get('Texto').setContent($("#CopeteNoticia").html());
}

function iniciarTextEditors()
{
	var editorConfig = {
		mode : 'specific_textareas',
        	language : "es",
		selector: '.rich-text',
		plugins : 'paste,lists,tabfocus,table,link,anchor',
		spellchecker_languages : "+Spanish=es",
		theme : 'modern',
		menubar: false,
		width : "100%",
		content_css : 'css/texteditor.css?v=1.3',
		toolbar_items_size: 'small',
		toolbar1 : 'cut,copy,paste,pasteword,|,bold,italic,underline,|,link unlink anchor,|,removeformat,|,formatselect,|,bullist,numlist,|,hr,|,justifyleft,justifycenter,justifyright,justifyfull,|,blockquote,cite,|,styleselect,|,code',
		apply_source_formatting : false,
		theme_advanced_blockformats : "h2,h3,h4,h5,h6,p,table",
		element_format : 'xhtml',
		theme_advanced_resizing : true,
		forced_root_block : 'p',
		force_p_newlines : true,
		force_br_newlines : false,
		valid_elements : 'div,br,p[style|class],a[name|href|target=_blank|title],img[*],strong/b,em/i,u,span[style=text-decoration:underline;],h2[style|class],h3,h4,h5,h6,ul,ol,li,hr,iframe[*]', 
		paste_auto_cleanup_on_paste : true,
		paste_preprocess : function(pl, o) {
			// Replace <br>s and <div>s by paragraphs and filter the html tags (except <p>s).
			o.content = o.content.replace(/<br(\s[^>]*)?\/?>/ig, '</p><p>')
			                     .replace(/<div(\s[^>]*)?\/?>/ig, '<p>')
			                     .replace(/<\/?(?!p)(\s[^>]*)?\/?>/ig, '');
		}	
	};
	tinyMCE.remove(".rich-text");
	tinyMCE.init(editorConfig);
	tinyMCE.execCommand("Texto")
}
</script>