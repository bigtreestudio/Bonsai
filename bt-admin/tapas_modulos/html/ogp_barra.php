<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
}
?>
<div class="barra_ogp tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	&nbsp;
</div>
<?php  