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

$oEncabezados = new cEncabezados($conexion);

$oUsuariosModulosAcciones=new cUsuariosModulosAcciones($conexion);

if (!isset($_POST['usuariocod']))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_POST['usuariocod']),$get,$md5);
if($_POST["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$ret['IsSuccess'] = false;


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
header('Content-Type: text/html; charset=iso-8859-1'); 

$conexion->ManejoTransacciones("B");
$result=true;

if($oUsuariosModulosAcciones->Actualizar($_POST))
{
	$conexion->ManejoTransacciones("C");
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_INF,"Se han actualizado las acciones del usuario correctamente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	$ret['IsSuccess'] = true;	
}else
{
	$conexion->ManejoTransacciones("R");	
}


$ret['Msg'] = utf8_encode(ob_get_contents());

ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>