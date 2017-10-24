<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$style="";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	$MargenDerIzq = 0;
	$MargenSupInf = 0;
	if (isset($objDataModel->MargenDerIzq))
		$MargenDerIzq  = $objDataModel->MargenDerIzq;
	if (isset($objDataModel->MargenSupInf))
		$MargenSupInf  = $objDataModel->MargenSupInf;
	
	$style= 'style="margin:'.$MargenSupInf.'px '.$MargenDerIzq .'px"';
}
?>
<div class="borde_separador tap_modules" <?php  echo $style?> id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	&nbsp;
</div>
<?php  