<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
		
}

?>
<div class="noticiasLstHome tap_modules clearfix" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    $$Tipo='Include' Archivo='dinamicas_recomendadas.php' Parametros='tapacod=<?php  echo $vars['tapacod']?>'$$
</div>
<?php  ?>