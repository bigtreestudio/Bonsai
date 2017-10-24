<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);

$Tamanio = 14;
$Titulo = "";
$CantidadTotal = 15;
$ColorTitulo= "";
$CantidadCarrousel = 5;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->CantidadTotal) && is_int((int)$objDataModel->CantidadTotal))
		$CantidadTotal = $objDataModel->CantidadTotal;
		
	if (isset($objDataModel->Titulo))
		$Titulo  = $objDataModel->Titulo;	
	
	if (isset($objDataModel->CantidadCarrousel) && is_int((int)$objDataModel->CantidadCarrousel))
		$CantidadCarrousel = $objDataModel->CantidadCarrousel;
		
	if (isset($objDataModel->ColorTitulo))
		$ColorTitulo  = $objDataModel->ColorTitulo;
		
	if (isset($objDataModel->ColorTitulo))
		$ColorTitulo  = $objDataModel->ColorTitulo;		

	if (isset($objDataModel->Tamanio))
		$Tamanio  = $objDataModel->Tamanio;
	
	if (isset($objDataModel->catcod))
		$catcod = $objDataModel->catcod;		
						
}

$datosbusqueda['catcod']= $catcod ;
$datosbusqueda['orderby'] = "noticiacod desc";
$datosbusqueda['limit'] = "LIMIT 0,".($CantidadCarrousel);
if(!$oNoticias->BuscarNoticiasPublicadas($datosbusqueda,$resultadonoticias,$numfilas)) {
	$error = true;
}
?>
<div class="modulootrasnoticias tap_modules clearfix" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<div class="titulootrasnoticias">
     <h2 style="color:<?php  echo $ColorTitulo?>; font-size:<?php  echo $Tamanio?>px; font-weight:bold;">
         <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Titulo),ENT_QUOTES);?>
     </h2>
    </div>
    <div class="otrasnoticiasmodulo clearfix" id="otrasnoticiasmodulo_<?php  echo $vars['zonamodulocod']?>">
    	<ul>
        	<?php  while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadonoticias)){
				
				?>
            	<li>
                    <div class="info">
                        <div>
                            <a href="/<?php  echo  $fila['catdominio']?>/<?php  echo  $fila['noticiadominio']?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulocorto'],ENT_QUOTES)?>">
                                <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulocorto'],ENT_QUOTES)?>
                            </a>
                        </div>
                    </div>
            	</li>
            <?php  }?>
        </ul>
    </div>
</div>
<?php  ?>