<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$Numero = "";
$Icono = "";
$Texto ="";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->Numero))
		$Numero = $objDataModel->Numero;

	if (isset($objDataModel->Texto))
		$Texto = $objDataModel->Texto;
		
	if (isset($objDataModel->Icono))
		$Icono = $objDataModel->Icono;
	
	switch($Icono){
		case "tilde":
			$Icono="fa fa-check";
			break;
		case "mas":
			$Icono="fa fa-plus";
			break;
		default:
			$Icono="";
			break;
	}
}
?>
<div class="counter_feature counter_featureindicador_box tap_modules wow fadeInLeft" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="indicador counter">
    	<? if ($Icono!=""){?>
         <i class="<? echo $Icono?>"></i>
        <? }?>
        <h2  class="timer"><? echo $Numero?></h2>
        <h3><? echo $Texto?></h3>
    </div>
</div>
<?php  