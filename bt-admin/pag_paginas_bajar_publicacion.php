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

$oPaginasPublicacion = new cPaginasPublicacion($conexion,"");

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);


$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$msg['IsSucceed'] = false;

if (!isset($_GET['pagcod']) || $_GET['pagcod']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['pagcod'],"NumericoEntero"))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("pagcod"=>$_GET['pagcod'],"paginaworkflowcod"=>$_GET['paginaworkflowcod'],"accion"=>$_GET['accion']),$get,$md5);
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
		if($oPaginasPublicacion->BajarPaginaaEdicion($datos,$paginacodnueva))
		{
			$msg['IsSucceed'] = true;
			$conexion->ManejoTransacciones("C");
			ob_clean();
			FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_am.php",array("pagcod"=>$paginacodnueva),$get,$md5);
			header("Location:pag_paginas_am.php?pagcod=".$paginacodnueva."&md5=".$md5); 
			die();
		}	
		break;	
	case 2:
		if($oPaginasPublicacion->DescartarCambiosPublicacion($datos,$resultado,$numfilas))
		{
			$msg['IsSucceed'] = true;
			$conexion->ManejoTransacciones("C");
			ob_clean();
			header("Location:pag_paginas.php"); 
			die();
		}	
		break;	
				
}


$conexion->ManejoTransacciones("R");
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>