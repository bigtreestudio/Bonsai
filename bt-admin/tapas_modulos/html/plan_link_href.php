<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$IdLink="";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->IdLink))
		$IdLink  = $objDataModel->IdLink;
}
?>
<div class="tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<a id="<? echo $IdLink?>"></a>
    <? if (trim($vars['mouseaction'])!=""){?>
    	<div style="background-color:#999; color:#FFF; padding:20px 0; text-align:center;">
        	#<? echo $IdLink?>
        </div>
	<? }?>
</div>
<?php  