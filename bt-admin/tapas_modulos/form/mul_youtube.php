<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$Codigo = "";
$Titulo = "";
$Width = "";
$Height = "";
$ColorTitulo="#000000";
$Texto="";
$Tamanio = 18;
$muestroagregar=true;
$TitleSubrayado = false;
$TitleNegrita = false;
$TitleItalica = false;

if (isset($vars['zonamodulocod']))
{
	$muestroagregar=false;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->Codigo))
		$Codigo = $objDataModel->Codigo;
	if (isset($objDataModel->Titulo))
		$Titulo = $objDataModel->Titulo;
	if (isset($objDataModel->Width))
		$Width = $objDataModel->Width;
	if (isset($objDataModel->Height))
		$Height = $objDataModel->Height;
	if (isset($objDataModel->ColorTitulo))
		$ColorTitulo = $objDataModel->ColorTitulo;
	if (isset($objDataModel->Texto))
		$Texto = $objDataModel->Texto;
	if (isset($objDataModel->Tamanio))
		$Tamanio  = $objDataModel->Tamanio;
	if (isset($objDataModel->TitleNegrita) && $objDataModel->TitleNegrita=="1")
		$TitleNegrita = true;
	if (isset($objDataModel->TitleSubrayado) && $objDataModel->TitleSubrayado=="1")
		$TitleSubrayado = true;
	if (isset($objDataModel->TitleItalica) && $objDataModel->TitleItalica=="1")
		$TitleItalica = true;
		
}
?>
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>


<script type="text/javascript">

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
}

$(document).ready( function() {
	$("#ColorTitulo").miniColors();
	iniciarTextEditors();
});
</script>

<div style="text-align:left;">
	<div style="float:left; width:15%">
    	<label>Titulo Video:</label>
    </div>
	<div style="float:left; width:85%;">
    	<input type="text" name="Titulo" id="Titulo" value="<?php  echo $Titulo?>" style="width:95%;"  />
    </div>
    <div class="clearboth brisa_vertical">&nbsp;</div>
    <div style="float:left; width:15%">
    	<label>Color T&iacute;tulo:</label>
    </div>
    <div style="float:left; width:35%;">
        <input type="text" value="<?php  echo $ColorTitulo?>" id="ColorTitulo" name="ColorTitulo" maxlength="7" size="10" />(Hex.)
    </div>
    <div style="float:left; width:15%">
    	<label>Color Tama&ntilde;o:</label>
    </div>
	<div style="float:left; width:35%;">
    	<select name="Tamanio" id="Tamanio">
        	<?php  for ($i=22; $i<=60; $i++){?>
            	<option value="<?php  echo $i?>" <?php  if ($Tamanio==$i) echo 'selected="selected"'?> ><?php  echo $i?>px</option>
            <?php  }?>
        </select>
    </div>
    <? /*
    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:15%">
    	&nbsp;
    </div>
	<div style="float:left;">
    	<input type="checkbox" value="1" name="TitleSubrayado" <?php  if ($TitleSubrayado) echo 'checked="checked"'?>  id="TitleSubrayado" />
    </div>
	<div style="float:left; width:10%; margin-left:5px;">
    	<label for="TitleSubrayado">Subrayado</label>
    </div>
	<div style="float:left;">
    	<input type="checkbox" value="1" name="TitleNegrita" <?php  if ($TitleNegrita) echo 'checked="checked"'?>  id="TitleNegrita" />
    </div>
	<div style="float:left; width:10%; margin-left:5px;">
    	<label for="TitleNegrita">Negrita</label>
    </div>
	<div style="float:left;">
    	<input type="checkbox" value="1" name="TitleItalica" <?php  if ($TitleItalica) echo 'checked="checked"'?>  id="TitleItalica" />
    </div>
	<div style="float:left; width:10%; margin-left:5px;">
    	<label for="TitleItalica">Italica</label>
    </div>
    */?>
     <div class="clearboth brisa_vertical">&nbsp;</div>

	<div style="float:left; width:15%">
    	<label>C&oacute;digo Youtube:</label>
    </div>
	<div style="float:left; width:35%;">
    	<input type="text" name="Codigo" id="Codigo" value="<?php  echo $Codigo?>" size="20" />
    </div>
    <div style="float:left; width:15%;">
    	<label>Alto:</label>
    </div>
	<div style="float:left; width:35%;">
    	<input type="text" name="Height" id="Height" value="<?php  echo $Height?>" style="width:50px;"  />&nbsp;px
    </div>
    <? /*
	<div style="float:left; width:50px;">
    	<label>Ancho:</label>
    </div>
	<div style="float:left; width:15%;">
    	<input type="text" name="Width" id="Width" value="<?php  echo $Width?>" style="width:50px;"  />&nbsp;px
    </div>
	<div style="float:left; width:40px;">
    	<label>Alto:</label>
    </div>
	<div style="float:left; width:15%;">
    	<input type="text" name="Height" id="Height" value="<?php  echo $Height?>" style="width:50px;"  />&nbsp;px
    </div>
    */?>
    <div style="clear:both; height:1px;">&nbsp;</div>
	<div style="float:left; width:15%">
    	&nbsp;
    </div>
	<div style="float:left; width:80%;">
        <div style="font-size:10px;">
            <div>Ej: http://youtu.be/<b>W3MFwIrfnZc</b></div>
            <div>Ej: http://www.youtube.com/watch?v=<b>W3MFwIrfnZc</b>&feature=context-vrec</div>
        </div>
    </div>
    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:15%">
    	<label>Texto:</label>
    </div>
	<div style="float:left; width:75%;">
    	<textarea name="Texto" id="Texto"  class="textarea full rich-text" style="width:95%;" cols="30" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Texto),ENT_QUOTES)?></textarea>
    </div>
    <div style="clear:both">&nbsp;</div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="ModificaryCerrar()">Guardar y Cerrar</a>
        	<?php  if ($muestroagregar) {   ?>                                                            
                <a href="javascript:void(0)" onclick="ModificaryAgregar()">Guardar y Agregar otro</a>
             <?php  } ?>
            </li>
        </ul>
    </div> 
</div>
 <script type="text/javascript">
   
	function ModificaryCerrar()
	{
		var Cuerpo = tinyMCE.get('Texto');
		$("#Texto").val(Cuerpo.getContent());

		saveModulo()
	}

	function ModificaryAgregar()
	{
		var Cuerpo = tinyMCE.get('Texto');
		$("#Texto").val(Cuerpo.getContent());
		agregarModulo()
	}
</script>