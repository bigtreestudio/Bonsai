<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$TituloGrande="";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->TituloGrande))
		$TituloGrande = $objDataModel->TituloGrande;
}
?>
<div class="compromisos_home tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<h1><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($TituloGrande),ENT_QUOTES)?></h1>
    <div class="linea_amarilla_underline"></div>
    <div class="clearboth nada">&nbsp;</div>
</div>
<?php  