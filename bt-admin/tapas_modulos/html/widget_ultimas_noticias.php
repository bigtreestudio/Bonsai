<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);
$Titulo = "";
$CantidadTotal= 5;
if (isset($vars['pagmodulocod']) || isset($vars['catmodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->CantidadTotal) && is_int((int)$objDataModel->CantidadTotal))
		$CantidadTotal = $objDataModel->CantidadTotal;
		
	if (isset($objDataModel->Titulo))
		$Titulo  = $objDataModel->Titulo;	
			
	if (isset($objDataModel->catcod))
		$catcod = $objDataModel->catcod;		
						
}


?>
<div class="UltimasNoticiasInternas">
	<h2><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Titulo),ENT_QUOTES);?></h2>
	<div class="ultimasnoticias">
	    $$Tipo='cNoticiasDinamicas' SubTipo='UltimasNoticias' Parametros='catcod=<?php  echo $catcod?>||CantidadTotal=<?php  echo $CantidadTotal?>'$$
    </div>
</div>
<?php  ?>