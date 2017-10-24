<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$Texto ="";
$FondoCaja= "";


if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->Texto))
		$Texto  = $objDataModel->Texto;
	if (isset($objDataModel->FondoCaja))
		$FondoCaja  = $objDataModel->FondoCaja;
	
}

?>
<div class="tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<div class="<?php  echo $FondoCaja?> txt_destacado">
        <h2>
                <?php  echo nl2br( FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Texto),ENT_QUOTES));?>
        </h2>
    </div>
</div>
<?php  