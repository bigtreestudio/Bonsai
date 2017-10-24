<?php  


FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));


$Titulo = "";
$Tamanio = 18;
$Texto ="";
$ColorTitulo= "#000000";
$ColorFondo = "#FFFFFF";
$ColorFondoTransparente = "0";
$Link="";
$Target="_blank";
$muestroagregar=false;
$TitleAlign="";
$TextoAlign="";
$MargenSup = "0";
$MargenInf = "0";
$MargenIzq = "0";
$MargenDer = "0";
$PaddingSup="0";
$PaddingInf="0";
$PaddingIzq="0";
$PaddingDer="0";

$TitleSubrayado = false;
$TitleNegrita = false;
$TitleItalica = false;


if (isset($vars['pagmodulocod']) || isset($vars['catmodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->Titulo))
		$Titulo = $objDataModel->Titulo;
	
	if (isset($objDataModel->Link))
		$Link = $objDataModel->Link;

	if (isset($objDataModel->Target))
		$Target = $objDataModel->Target;

	if (isset($objDataModel->Texto))
		$Texto = $objDataModel->Texto;
		
	if (isset($objDataModel->Tamanio))
		$Tamanio = $objDataModel->Tamanio;
	
	if (isset($objDataModel->ColorTitulo))
		$ColorTitulo = $objDataModel->ColorTitulo;

	if (isset($objDataModel->ColorTexto))
		$ColorTexto = $objDataModel->ColorTexto;

	if (isset($objDataModel->ColorFondo))
		$ColorFondo = $objDataModel->ColorFondo;
		
	if (isset($objDataModel->ColorFondoTransparente))
		$ColorFondoTransparente = $objDataModel->ColorFondoTransparente;

	if (isset($objDataModel->TitleAlign))
		$TitleAlign = $objDataModel->TitleAlign;

	if (isset($objDataModel->TextoAlign))
		$TextoAlign = $objDataModel->TextoAlign;

	if (isset($objDataModel->MargenIzq))
		$MargenIzq = $objDataModel->MargenIzq;

	if (isset($objDataModel->MargenDer))
		$MargenDer = $objDataModel->MargenDer;

	if (isset($objDataModel->MargenSup))
		$MargenSup = $objDataModel->MargenSup;

	if (isset($objDataModel->MargenInf))
		$MargenInf = $objDataModel->MargenInf;

	if (isset($objDataModel->PaddingIzq))
		$PaddingIzq = $objDataModel->PaddingIzq;

	if (isset($objDataModel->PaddingDer))
		$PaddingDer = $objDataModel->PaddingDer;

	if (isset($objDataModel->PaddingSup))
		$PaddingSup = $objDataModel->PaddingSup;

	if (isset($objDataModel->PaddingInf))
		$PaddingInf = $objDataModel->PaddingInf;

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
	$("#ColorTitulo, #ColorTexto, #ColorFondo").miniColors();
	iniciarTextEditors();
});
</script>
<div style="text-align:left;">
	<div style="float:left; width:10%">
    	<label>Titulo:</label>
    </div>
	<div style="float:left; width:50%;">
    	<input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Titulo),ENT_QUOTES)?>" id="Titulo" name="Titulo" maxlength="150" size="50" />
    </div>
	<div style="float:left; width:20%;">
    	<select name="Tamanio" id="Tamanio">
        	<?php  for ($i=10; $i<26; $i++){?>
            	<option value="<?php  echo $i?>" <?php  if ($Tamanio==$i) echo 'selected="selected"'?> ><?php  echo $i?>px</option>
            <?php  }?>
        </select>
    </div>
    
	<div style="float:left; width:20%;">
        <select name="TitleAlign" id="TitleAlign">
            <option value="left" <?php  if ($TitleAlign=="left") echo 'selected="selected"'?> >Izquierda</option>
            <option value="center" <?php  if ($TitleAlign=="center") echo 'selected="selected"'?>>Centro</option>
            <option value="right" <?php  if ($TitleAlign=="right") echo 'selected="selected"'?>>Derecha</option>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:10%">
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

    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:10%">
    	<label>Link:</label>
    </div>
	<div style="float:left; width:50%;">
    	<input type="text" value="<?php  echo utf8_decode($Link)?>" id="Link" name="Link" maxlength="150" size="50" />
    </div>
	<div style="float:left; width:40%;">
        <select name="Target" id="Target">
            <option value="_blank" <?php  if ($Target=="_blank") echo 'selected="selected"'?> >Abrir en otra ventana</option>
            <option value="_self" <?php  if ($Target=="_self") echo 'selected="selected"'?>>Abir en la misma ventana</option>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
	<div style="float:left; width:10%">
    	<label>Texto:</label>
    </div>
	<div style="float:left; width:75%;">
    	<textarea name="Texto" id="Texto"  class="textarea full rich-text" style="width:95%;" cols="30" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Texto),ENT_QUOTES)?></textarea>
    </div>
	<div style="float:left; width:15%;">
        <select name="TextoAlign" id="TextoAlign">
            <option value="left" <?php  if ($TextoAlign=="left") echo 'selected="selected"'?> >Izquierda</option>
            <option value="center" <?php  if ($TextoAlign=="center") echo 'selected="selected"'?>>Centro</option>
            <option value="right" <?php  if ($TextoAlign=="right") echo 'selected="selected"'?>>Derecha</option>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>
    <div style="clear:both">&nbsp;</div>
    <div style="float:left; width:40%;">
        <div style="float:left; width:30%">
            <label>Color Titulo:</label>
        </div>
        <div style="float:left; width:70%;">
            <input type="text" value="<?php  echo $ColorTitulo?>" id="ColorTitulo" name="ColorTitulo" maxlength="7" size="10" />(Hexadecimal)
        </div>
        <div style="clear:both">&nbsp;</div>
        <div style="float:left; width:30%">
            <label>Color Texto:</label>
        </div>
        <div style="float:left; width:70%;">
            <input type="text" value="<?php  echo $ColorTexto?>"  id="ColorTexto" name="ColorTexto" maxlength="7" size="10" />(Hexadecimal)
        </div>
        <div style="clear:both">&nbsp;</div>
        <div style="float:left; width:30%">
            <label>Color Fondo:</label>
        </div>
        <div style="float:left; width:70%;">
            <input type="text" value="<?php  echo $ColorFondo?>" id="ColorFondo" name="ColorFondo" maxlength="7" size="10" />(Hexadecimal)
        </div>
        <div style="clear:both">&nbsp;</div>
        <div style="float:left; width:50%">
            <label>Fondo Transparente:</label>
        </div>
        <div style="float:left; width:50%;">
            <input type="radio" <?php  if ($ColorFondoTransparente==1) echo 'checked="checked"'?>  name="ColorFondoTransparente" id="ColorFondoTransparenteSi" value="1" /> <label for="ColorFondoTransparenteSi">Si</label>
            <input type="radio" <?php  if ($ColorFondoTransparente==0) echo 'checked="checked"'?> name="ColorFondoTransparente" id="ColorFondoTransparenteNo" value="0" />  <label for="ColorFondoTransparenteNo">No</label>
        </div>
        <div style="clear:both">&nbsp;</div>
     </div>
     <div style="text-align:left; float:left; width:60%;">
        <div style="float:left; width:50%;">
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Superior:</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $MargenSup?>" id="MargenSup" name="MargenSup" maxlength="4" size="5" />px
            </div>
            <div style="clear:both">&nbsp;</div>
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Inferior:</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $MargenInf?>" id="MargenInf" name="MargenInf" maxlength="4" size="5" />px
            </div>
            <div style="clear:both">&nbsp;</div>
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Izquierdo:</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $MargenIzq?>" id="MargenIzq" name="MargenIzq" maxlength="4" size="5" />px
            </div>
            <div style="clear:both">&nbsp;</div>
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Derecho:</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $MargenDer?>" id="MargenDer" name="MargenDer" maxlength="4" size="5" />px
            </div>
	        <div style="clear:both">&nbsp;</div>
		</div>
        <div style="float:left; width:50%;">
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Superior:<br />(Interno)</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $PaddingSup?>" id="PaddingSup" name="PaddingSup" maxlength="4" size="5" />px
            </div>
            <div style="clear:both">&nbsp;</div>
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Inferior:<br />(Interno)</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $PaddingInf?>" id="PaddingInf" name="PaddingInf" maxlength="4" size="5" />px
            </div>
            <div style="clear:both">&nbsp;</div>
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Izquierdo:<br />(Interno)</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $PaddingIzq?>" id="PaddingIzq" name="PaddingIzq" maxlength="4" size="5" />px
            </div>
            <div style="clear:both">&nbsp;</div>
            <div style="float:left; width:50%">
                <label>M&aacute;rgen Derecho:<br />(Interno)</label>
            </div>
            <div style="float:left; width:50%;">
                <input type="text" value="<?php  echo $PaddingDer?>" id="PaddingDer" name="PaddingDer" maxlength="4" size="5" />px 
            </div>
	        <div style="clear:both">&nbsp;</div>
         </div>   
         <div style="clear:both">&nbsp;</div>
     </div>   
     
     <div style="clear:both">&nbsp;</div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="ModificaryCerrar()">Guardar y Cerrar</a>
        	<?php  if (!$muestroagregar) {   ?>                                                            
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