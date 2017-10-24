<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$Alto=0;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->Alto))
		$Alto  = $objDataModel->Alto;
}
?>
<div class="separador tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <link rel="stylesheet" href="/assets/mapa.css">
 	<? 
		include(DOCUMENT_ROOT."/assets/mapa_bsas.svg");
	?>   
</div>
<?php  