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
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$usuarios=new cUsuarios($conexion);

//----------------------------------------------------------------------------------------- 


$datosvalidados=array();
if($usuarios->ValidarDatosElegirRol($_SESSION["usuariocod"],$_POST["rolcod"],$datosvalidados))
{
	$_SESSION['rolcod']=$datosvalidados['rolcod'];
	$_SESSION['usuariosistemacod']=$datosvalidados['usuariosistemacod'];
	header("Location:ingreso.php");
	die();
}
else
{ // error al validar los datos
	//header("Location:login.php");
	die();
}


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

	$usuarios->PantallaElegirRol($_SESSION['usuariocod']);



	ob_end_flush();

	$oEncabezados->PieMenuEmergente();

?>

