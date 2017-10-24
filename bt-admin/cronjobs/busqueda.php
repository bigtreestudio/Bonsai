<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 
//error_reporting(0);
error_reporting(E_WARNING | E_ERROR);

include('../config/include.php');
include('../Librerias/Sitemap.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

$oNoticias = new cNoticiasPublicacion($conexion);
$datosNoticia['orderby'] = "noticiafecha desc";
if(!$oNoticias->BusquedaAvanzada ($datosNoticia,$resultadoNoticias,$numfilas))
	return false;

$oTapasTipos = new cTapasTipos($conexion);
$datosTapa['tapatipoestado'] = ACTIVO;
if(!$oTapasTipos->BusquedaAvanzada ($datosTapa,$resultadoTapas,$numfilas))
	return false;

$oPaginas = new cPaginasPublicacion($conexion);
$datosPagina['orderby'] = "ultmodfecha desc";
if(!$oPaginas->BusquedaAvanzada ($datosPagina,$resultadoPaginas,$numfilas))
	return false;

$entries = "";
$arreglotapas=array();
$i=0;
while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoTapas))
{
	//$entries .= utf8_encode($fila['tapaticod'])."\t";
	//$entries .= utf8_encode($fila['tapatipodesc'])." \n";
	//$i++;
}

while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoPaginas))
{
	$entries .= utf8_encode($fila['pagcod'])."\t";
	$entries .= utf8_encode($fila['pagtitulo'])." \n";
	$i++;
}
while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoNoticias))
{	
	$entries .= utf8_encode($fila['noticiacod'])."\t";
	$entries .= utf8_encode($fila['noticiatitulo'])." \n";
	$i++;
}


if (!file_put_contents(CARPETAJSON."finder.dat", $entries)){
	throw new Exception('imposible escribir: '.CARPETAJSON."finder.dat"."\n");
}



?>