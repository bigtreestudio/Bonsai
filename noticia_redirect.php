<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cNoticias.class.php");
include(DIR_CLASES."cNoticiasCategorias.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);
$oCategorias = new cNoticiasCategorias($conexion);

if (!isset($_GET['codigo']) || $_GET['codigo']=='' || strlen($_GET['codigo'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['codigo'],"NumericoEntero"))
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}


$datosnoticia['noticiacod'] = $_GET['codigo'];
$oNoticiaService = new cNoticias($conexion);

$oNoticia = $oNoticiaService->BuscarNoticia($datosnoticia);
if ($oNoticia===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

$dominio = DOMINIORAIZSITE.$oNoticia->getDominioCategoria()."/".$oNoticia->getDominio();

header ('HTTP/1.1 301 Moved Permanently');
header("Location:".$dominio);

die();
?>