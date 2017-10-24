<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$Codigo = "";
$Titulo = "";
$Width = "";
$Height = "";
$ColorTitulo="";
$Texto="";
$TitleSubrayado=false;
$TitleNegrita=false;
$TitleItalica=false;
$Tamanio = 18;

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->Codigo))
		$Codigo  = $objDataModel->Codigo;
	if (isset($objDataModel->Titulo))
		$Titulo  = $objDataModel->Titulo;
	if (isset($objDataModel->Width))
		$Width  = $objDataModel->Width;
	if (isset($objDataModel->Height))
		$Height  = $objDataModel->Height;
	if (isset($objDataModel->Texto))
		$Texto  = $objDataModel->Texto;
	if (isset($objDataModel->ColorTitulo))
		$ColorTitulo  = $objDataModel->ColorTitulo;
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
<div class="mul_youtube tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <? if ($Titulo!=""){?>
    	<div class="col-md-4 col-sm-6 col-xs-12">
        	<h2 style="color:<?php  echo $ColorTitulo?>; font-size:<?php  echo $Tamanio?>px;">
				<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Titulo),ENT_QUOTES);?>
            </h2>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <?php  if (trim($Texto)!=""){?>
                <?php  echo $Texto;?>
            <?php  }?>
            <div class="clearboth brisa_vertical">&nbsp;</div>
        </div>
    <? }?>
    <?php  if (trim($Codigo)!=""){?>
		<div class="col-md-8 col-sm-6 col-xs-12">
            <iframe style=" <? if ($Height!=""){echo "height:".$Height."px";}?>" src="http://www.youtube.com/embed/<?php  echo $Codigo?>" frameborder="0" allowfullscreen>
            	<p>Su navegador no soporta iframe, <a href="http://www.youtube.com/embed/<?php  echo $Codigo?>" title="Visualiza este contenido">Visualiza este contenido.</a></p>
            </iframe>
        </div>
    <?php  }?>
    <div class="clearboth">&nbsp;</div>
</div>
<?php  