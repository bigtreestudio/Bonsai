<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
}
?>
<div class="tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<div id="doughnutChart" class="chart"></div>
<?php 
?> 
$$Tipo='Include' Archivo='plan_graficos.php' Parametros='tapacod=<?php  echo $vars['tapacod']?>'$$
</div>
