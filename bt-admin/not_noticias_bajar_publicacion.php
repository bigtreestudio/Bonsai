<?php  
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

$oNoticiasPublicacion = new cNoticiasPublicacion($conexion,"");

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);


$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$msg['IsSucceed'] = false;

if (!isset($_GET['noticiacod']) || $_GET['noticiacod']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['noticiacod'],"NumericoEntero"))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("noticiacod"=>$_GET['noticiacod'],"noticiaworkflowcod"=>$_GET['noticiaworkflowcod'],"accion"=>$_GET['accion']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$datos = $_GET;
$datos['rolcod'] = $_SESSION['rolcod'];

switch($datos['accion'])
{
	case 1:
		if($oNoticiasPublicacion->BajarNoticiaaEdicion($datos,$noticiacodnueva))
		{
			$msg['IsSucceed'] = true;
			$conexion->ManejoTransacciones("C");
			ob_clean();
			FuncionesPHPLocal::ArmarLinkMD5("not_noticias_am.php",array("noticiacod"=>$noticiacodnueva),$get,$md5);
			header("Location:not_noticias_am.php?noticiacod=".$noticiacodnueva."&md5=".$md5); 
			die();
		}	
		break;	
	case 2:
		if($oNoticiasPublicacion->DescartarCambiosPublicacion($datos,$resultado,$numfilas))
		{
			$msg['IsSucceed'] = true;
			$conexion->ManejoTransacciones("C");
			ob_clean();
			header("Location:not_noticias.php"); 
			die();
		}	
		break;	
				
}


$conexion->ManejoTransacciones("R");
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>