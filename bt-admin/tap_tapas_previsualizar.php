<?php  
ini_set("memory_limit", "128M");
ob_start();
require('./config/include.php');


$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oTapas= new cTapas($conexion);
header('Content-Type: text/html; charset=iso-8859-1'); 
$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;

//FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("tapacod"=>$_GET['tapacod']),$get,$md5);
/*if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	ob_clean();
	header("Location:tap_tapas_confeccionar_error.php");
	die();
}*/

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$datos = $_GET;
if(!$oTapas->BuscarxCodigo($datos,$resultado,$numfilas))
	return false;

$datostapa = $conexion->ObtenerSiguienteRegistro($resultado);

$html_generado ="";
$oProcesarHTML = new cTapasProcesarHTML($conexion);
$oTapasTipos = new cTapasTipos($conexion);
$oProcesarHTML->SetearPrevisualizar();
if($oProcesarHTML->Procesar($datostapa,$html_generado,$arreglozonas))
{
	echo $html_generado;	
	
}

?>