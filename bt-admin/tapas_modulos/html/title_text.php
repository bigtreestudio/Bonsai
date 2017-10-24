<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$Titulo = "";
$Tamanio = 14;
$Texto ="";
$ColorTitulo= "";
$ColorTexto= "";
$ColorFondo = "";
$ColorFondoTransparente = "";
$Link = "";
$Target = "";
$style= '';
$TitleAlign="left";
$TextoAlign="left";
$MargenSup = "0";
$MargenInf = "0";
$MargenIzq = "0";
$MargenDer = "0";
$PaddingIzq="0";
$PaddingDer="0";
$PaddingSup="0";
$PaddingInf="0";

$TitleSubrayado=false;
$TitleNegrita=false;
$TitleItalica=false;


if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	$MargenDerIzq = 0;
	$MargenSupInf = 0;
	if (isset($objDataModel->Titulo))
		$Titulo  = $objDataModel->Titulo;
	if (isset($objDataModel->Link))
		$Link  = $objDataModel->Link;
	if (isset($objDataModel->Target))
		$Target  = $objDataModel->Target;
	if (isset($objDataModel->Tamanio))
		$Tamanio  = $objDataModel->Tamanio;
	if (isset($objDataModel->Texto))
		$Texto  = $objDataModel->Texto;
	if (isset($objDataModel->ColorTitulo))
		$ColorTitulo  = $objDataModel->ColorTitulo;
	if (isset($objDataModel->ColorTexto))
		$ColorTexto  = $objDataModel->ColorTexto;
	if (isset($objDataModel->ColorFondo))
		$ColorFondo  = $objDataModel->ColorFondo;

	if (isset($objDataModel->ColorFondoTransparente))
		$ColorFondoTransparente  = $objDataModel->ColorFondoTransparente;


	if (isset($objDataModel->TitleAlign))
		$TitleAlign  = $objDataModel->TitleAlign;
	if (isset($objDataModel->TextoAlign))
		$TextoAlign  = $objDataModel->TextoAlign;
	if (isset($objDataModel->MargenIzq))
		$MargenIzq  = $objDataModel->MargenIzq;
	if (isset($objDataModel->MargenDer))
		$MargenDer  = $objDataModel->MargenDer;
	if (isset($objDataModel->MargenSup))
		$MargenSup  = $objDataModel->MargenSup;
	if (isset($objDataModel->MargenInf))
		$MargenInf  = $objDataModel->MargenInf;
	if (isset($objDataModel->PaddingIzq))
		$PaddingIzq  = $objDataModel->PaddingIzq;
	if (isset($objDataModel->PaddingDer))
		$PaddingDer  = $objDataModel->PaddingDer;
	if (isset($objDataModel->PaddingSup))
		$PaddingSup  = $objDataModel->PaddingSup;
	if (isset($objDataModel->PaddingInf))
		$PaddingInf  = $objDataModel->PaddingInf;

	if (isset($objDataModel->TitleNegrita) && $objDataModel->TitleNegrita=="1")
		$TitleNegrita = true;
	if (isset($objDataModel->TitleSubrayado) && $objDataModel->TitleSubrayado=="1")
		$TitleSubrayado = true;
	if (isset($objDataModel->TitleItalica) && $objDataModel->TitleItalica=="1")
		$TitleItalica = true;
	
	
}

if ($ColorFondoTransparente==1)
	$ColorFondo = "transparent";
?>
<div class="caja_texto tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<div style="background-color:<?php  echo  $ColorFondo?>; padding:<?php  echo $PaddingSup?>px <?php  echo $PaddingDer?>px <?php  echo $PaddingInf?>px <?php  echo $PaddingIzq?>px; margin:<?php  echo $MargenSup?>px <?php  echo $MargenDer?>px <?php  echo $MargenInf?>px <?php  echo $MargenIzq?>px">
        <h2 style="color:<?php  echo $ColorTitulo?>; font-size:<?php  echo $Tamanio?>px; text-align:<?php  echo $TitleAlign?>; <?php  if ($TitleSubrayado) echo "text-decoration:underline;"?> <?php  if ($TitleItalica) echo "font-style:italic;"?> <?php  if ($TitleNegrita) {echo "font-weight:bold;";} else {echo "font-weight:normal;";}?> ">
            <?php  if ($Link!=""){?>
                <a href="<?php  echo $Link?>" target="<?php  echo $Target?>" style="color:<?php  echo $ColorTitulo?>; font-size:<?php  echo $Tamanio?>px" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Titulo),ENT_QUOTES);?>">
            <?php  }?>
                <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Titulo),ENT_QUOTES);?>
            <?php  if ($Link!=""){?>
                </a>
            <?php  }?>
        </h2>
        <?php  if (trim($Texto)!=""){?>
        <p style="color:<?php  echo $ColorTexto?>; text-align:<?php  echo $TextoAlign?>"><?php  echo $Texto;?></p>
        <?php  }?>
    </div>
</div>
<?php  