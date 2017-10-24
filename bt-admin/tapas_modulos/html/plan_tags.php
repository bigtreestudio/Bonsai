<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$Alto=0;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->Alto))
		$Alto  = $objDataModel->Alto;
}


$sql = "SELECT t.* , tc.`plantagcatnombre`, e.`planejeconstante`,e.`planejenombre`, e.`planejecolor`
FROM `plan_tags` t
INNER JOIN plan_tags_categorias tc
ON tc.`plantagcatcod`=t.`plantagcatcod`
LEFT JOIN `plan_ejes` e
ON e.`planejecod`=t.`planejecod`
WHERE t.`plantagestado`=10 order by plantagorden ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$cantidadEncontrados = $conexion->ObtenerCantidadDeRegistros($resultado);
?>
<div class="separador tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<?
	if ($cantidadEncontrados>0){
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado)){
			?>
            <div class="col-md-2 col-sm-3 col-xs-6 tag">
                <a class="shortcut" href="#tag_<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['plantagcod'],ENT_QUOTES)?>" data-id="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['plantagcod'],ENT_QUOTES)?>">
                   <span class="shortcut-tag">
                         <img src="<? echo DOMINIOWEB ?>/public/plandegobierno/imagenes/tags/<? echo $fila['plantagclass']?>.png"/>
                   </span>
                   <h3><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['plantagnombre'],ENT_QUOTES)?></h3>
                </a>
            </div>
            <?
		}
	}
	?>
</div>
<div class="clearboth"></div>