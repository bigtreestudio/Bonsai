<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 

if (isset($_GET['ejecutarsolo']))
{
	define("DIRRAIZCRONES","");
	include(DIRRAIZCRONES."../config/include.php");
	include(DIRRAIZCRONES."../Librerias/FeedWriter.php");
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	// carga las constantes generales
	FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
	$conexion->SetearAdmiGeneral(ADMISITE);
}


include(DIRRAIZCRONES."../Librerias/Sitemap.php");

$oNoticias = new cNoticiasPublicacion($conexion);
$datosNoticia['orderby'] = "noticiafecha desc";
if(!$oNoticias->BusquedaAvanzada ($datosNoticia,$resultadoNoticias,$numfilas))
	return false;

$oTags = new cNoticiasTags($conexion);
$datosTags['limit'] = "";
if(!$oTags->BuscarTagsSitemap ($datosTags,$resultadoTags,$numfilas))
	return false;

$oTapasTipos = new cTapasTipos($conexion);
$datosTapa['tapatipoestado'] = ACTIVO;
if(!$oTapasTipos->BusquedaAvanzada ($datosTapa,$resultadoTapas,$numfilas))
	return false;

$oPaginas = new cPaginasPublicacion($conexion);
$datosPagina['orderby'] = "ultmodfecha desc";
if(!$oPaginas->BusquedaAvanzada ($datosPagina,$resultadoPaginas,$numfilas))
	return false;


$oCategorias = new cCategorias($conexion);
$datosCategoria['catestado'] = ACTIVO;
if(!$oCategorias->BuscaCategoriasxEstado($datosCategoria,$resultadoCategorias,$numfilas))
	return false;


$entries[] = new xml_sitemap_entry('', '1.00', 'always');
$arreglotapas=array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoTapas))
{
	$arreglotapas[$fila['tapatipourlfriendly']] = $fila['tapatipourlfriendly'];
	$priority = "0.90";
	$change = 'daily';
	$entries[] = new xml_sitemap_entry($fila['tapatipourlfriendly'], $priority, $change,$fila['ultmodfecha']);
}

while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoCategorias))
{
	$priority = "0.90";
	$change = 'daily';
	$dom = $fila['catdominio'];
	if (array_key_exists($fila['catdominio'],$arreglotapas))
		$dom = $fila['catdominio']."/list";
	$entries[] = new xml_sitemap_entry($dom, $priority, $change,$fila['ultmodfecha']);
}

while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoNoticias))
{
	$priority = "0.90";
	$change = 'weekly';
	$entries[] = new xml_sitemap_entry($fila['catdominio']."/".$fila['noticiadominio'], $priority, $change,$fila['ultmodfecha']);
}

while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoPaginas))
{
	$priority = "0.80";
	$change = 'weekly';
	$entries[] = new xml_sitemap_entry($fila['pagdominio'], $priority, $change,$fila['ultmodfecha']);
}

$total = 0;
$priority = 0.80;
while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoTags))
{
	if ($total!=$fila['cantidad'])
	{
		$priority = $priority - 0.10."0" ;
		$total = $fila['cantidad'];
		if ($priority<0.10)
			$priority="0.10";
	}
	$change = 'weekly';
	$entries[] = new xml_sitemap_entry("tag/".urlencode(trim($fila['noticiatag'])), $priority, $change);
}
$entries[] = new xml_sitemap_entry('agenda', '0.70', 'always');





$oSitemap = new xml_sitemap_generator_config();
$oSitemap->setDomain(DOMINIOWEB);
$oSitemap->setPath(CARPETASITEMAP."/");
$oSitemap->setFilename('sitemap.xml');
$oSitemap->setEntries($entries);
$generator = new xml_sitemap_generator($oSitemap);
$generator->write();




?>