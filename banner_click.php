<?php  
include("./config/include.php");
include(DIR_CLASES."cBanners.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

if (!isset($_GET['codigo']) || $_GET['codigo']=='' || strlen($_GET['codigo'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['codigo'],"NumericoEntero"))
	die();

FuncionesPHPLocal::ArmarLinkMD5Front(basename($_SERVER['PHP_SELF']),array("codigo"=>$_GET['codigo']),$getPrevisualizar,$md5Prev);
if ($_GET['codigoSecreto']!=$md5Prev)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

	
$oBanners=new cBanners($conexion);

$datos['bannercod'] = $_GET["codigo"];
if (!$oBanners->BuscarxCodigo($datos,$resultado,$numfilas) || $numfilas!=1)
{	
	FuncionesPHPLocal::Error404();
	die();
}
$datosBanner = $conexion->ObtenerSiguienteRegistro($resultado);
$oBanners->SumarCantidad($datosBanner);

header("Location:".$datosBanner['bannerurl']);
?>