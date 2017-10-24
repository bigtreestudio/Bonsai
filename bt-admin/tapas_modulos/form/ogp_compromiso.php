<?php  
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$compromisocod="";
$Titulo = 1;
$Multimediacod=0;
$compromisoTitulo="";
$compromisobajada="";
$Texto ="";
?>
<script type="text/javascript">
function Validar(tituloCompromiso)
{
	if (tituloCompromiso.value=="")
	{
		alert("Debe ingresar el nombre del archivo");
		
		return false;
	}
}
</script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<?php 

$oTemas = new cOgpTemas($vars['conexion']);
$datos["temaestado"]="10";
if(!$oTemas->BusquedaAvanzada($datos,$resultadoT, $numfilasT))
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
	
	if (isset($objDataModel->multimediacod))	
		$Multimediacod = $objDataModel->multimediacod;

	
	$compromisocod = $objDataModel->compromisocod;
	$datosbusqueda['compromisocod'] = $compromisocod;
	$oCompromiso = new cOgpCompromisos($vars['conexion']);
	if(!$oCompromiso->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
		return false;
	
	$datosCompromiso = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
	$tituloCompromiso = $datosCompromiso['compromisotitulo'];
	$compromisobajada = $datosCompromiso['compromisobajada'];
	if (isset($objDataModel->compromisoTitulo) && $objDataModel->compromisoTitulo!="")	
		$compromisoTitulo =$objDataModel->compromisoTitulo;
	else
		$compromisoTitulo = utf8_encode($datosCompromiso['compromisotitulo']);

	if (isset($objDataModel->Texto) && $objDataModel->Texto!="")	
		$Texto =$objDataModel->Texto;
	else
		$Texto =$compromisobajada;
}

$oCompromisos= new cOgpCompromisos($vars['conexion']);
$datos=array(); 
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['orderby'] = "compromisocod desc";
$datos['compromisoestado'] = 10;
$datos['limit'] = "LIMIT 0,20";

if(!$oCompromisos->BusquedaAvanzada($datos,$resultadoCompromisos,$numfilas)) {
	$error = true;
}


?>
<div id="tabs">
    <ul>
        <li><a href="#lstNoticiatab"><span>Buscador de compromisos</span></a></li>
        <li><a href="#dataTab"><span>Compromiso Seleccionado</span></a></li>
        <li><a href="#dataNot"><span>Datos compromiso</span></a></li>
    </ul>

     <div id="dataNot">
        <div style="font-size:14px; margin-bottom:3px; clear:both;"><b>Titulo</b></div>
        <div >
            <input type="text" style="width:100%;" id="compromisoTitulo" name="compromisoTitulo" maxlength="255" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($compromisoTitulo),ENT_QUOTES)?>" />
        </div>
        <div style="font-size:14px; margin:15px 0 3px 0; clear:both;"><b>Bajada</b></div>
        <div >
            <textarea name="Texto" id="Texto"  class="textarea full rich-text" style="width:95%;" cols="30" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Texto),ENT_QUOTES)?></textarea>
        </div>
        <? /*
        <div style=" height:10px; clear:both;">&nbsp;</div>
        <div class="menucarga" style="text-align:left">
            <ul>
                <li>
                    <a href="javascript:void(0)" onclick="CargarOriginales()">Recargar textos de la nota original</a>
                </li>
            </ul>
        </div>  */?>
	</div>   

    <div id="lstNoticiatab">
        <div style="min-width:700px;">
            <div style="padding-top:10px">
                <div style="float:left; width:180px; text-align:left;">
                	<label>T&iacute;tulo</label>
                    <input type="text" style="width:100%;" id="compromisoTitulodescnotSimple" name="compromisoTitulodescnotSimple" >
                </div>
                <div style="float:left; width:300px; margin-left:55px; margin-top: 12px;">
                	<label>Tema</label>
                    <div style="clear:both; height:1px;">&nbsp;</div>
                    <select name="temacod" id="temacod" class="chzn-select-categorias">
                        <option value="">Seleccionar un tema...</option>
                        <?php 
                        while($fila=$conexion->obtenerSiguienteRegistro($resultadoT))
                        {
                            ?>
                            <option value="<?php  echo $fila['temacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['tematitulo']),ENT_QUOTES)?></option>
                           <?
                        }
                        ?>
                     </select>                    
                </div>
                <div style="float:left; width:30px;">
                	<div style="clear:both; height:30px;">&nbsp;</div>
                	<input type="button" name="btn_buscar" id="btn_buscar" onclick="BuscarCompromisos()" value="Buscar"/>
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
                        
                        while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoCompromisos))
                        {
                    ?>
                                <tr>
                                        <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['compromisocod'],ENT_QUOTES); ?></td>
                                        <td style="text-align:left" id="compromisoTitulo_<?php  echo $fila['compromisocod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['compromisotitulo'],ENT_QUOTES); ?></td>
                                        <td style="text-align:center; font-weight:bold;">
										    <input type="hidden" name="compromisobajada_<?php  echo $fila['compromisocod']?>" id="compromisobajada_<?php  echo $fila['compromisocod']?>" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['compromisobajada'], ENT_QUOTES); ?>" />
                                            <a class="left add_noticia" style="margin-left:15px" href="javascript:void(0)" onclick="AgregarCompromiso(<?php  echo $fila['compromisocod']?>)">&nbsp;</a>
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
            <div id="detalleCompromiso">
                <div id="TituloCompromiso" style=" font-size:16px; ext-align:left; font-weight:bold"><?php  echo $tituloCompromiso?></div>
                <div id="CopeteNoticia"><?php  echo utf8_encode($compromisobajada)?></div>
                <div style="clear:both">&nbsp;</div>
                <input type="hidden" name="compromisocod" id="compromisocod" value="<?php  echo $compromisocod?>">
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

	function Validar()
	{
		if($("#compromisocod").val()=='')
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
		if($("#compromisocod").val()=='')
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
		if($("#compromisocod").val()=='')
		{
			alert("Debe seleccionar una noticia");
			
		}else{
			var Cuerpo = tinyMCE.get('Texto');
			$("#Texto").val(Cuerpo.getContent());
			agregarModulo();
		}
		return true;
	}
	
	function AgregarCompromiso(codigo)
	{
		$("#TituloCompromiso").html($("#compromisoTitulo_"+codigo).html());
		$("#CopeteNoticia").html($("#compromisobajada_"+codigo).val());
		$("#compromisocod").val(codigo);
		$("#compromisoTitulo").val($("#compromisoTitulo_"+codigo).html());
		$("#modulonombre").val($("#compromisoTitulo_"+codigo).html());  //LINEA AGREGADA
		tinymce.get('Texto').setContent($("#compromisobajada_"+codigo).val());
		//BuscarMultimedia();
		$('#tabs').tabs('select', 1);
	}
	 
	var timeoutHnd; 
	function SearchNotice(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarCompromisos,500) 
	}
	
	function BuscarCompromisos()
	{
		var param, url;
		$("#noticiasLstData").html('<tr><td colspan="3" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando Compromisos...</td></div></tr>');
		param = "compromisotitulo="+$("#compromisoTitulodescnotSimple").val();
		param += "&temacod="+$("#temacod").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_compromisos_ajax.php",
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
		param = "compromisocod="+$("#compromisocod").val();
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
		param = "compromisocod="+$("#compromisocod").val();
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
	$("#compromisoTitulo").val($("#TituloCompromiso").html());
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