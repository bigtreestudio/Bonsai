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

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_POST['usuariocod']),$get,$md5);
if($_POST["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$usuariocod = $_POST['usuariocod'];
FuncionesPHPLocal::ArmarLinkMD5("usuarios_modificar_datos.php",array("usuariocod"=>$usuariocod),$get_post,$md5_post);
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
header('Content-Type: text/html; charset=iso-8859-1'); 

if (isset($_POST['botoneliminar']))
{
	$conexion->ManejoTransacciones("B");
	$result=true;
	
	if(!$usuarios->BloqueaUsuario($_POST))
		$result=false;
		
	if($result)
		$_SESSION['msgactualizacion'] = "Se ha bloqueado al usuario.";
}

if (isset($_POST['botonrehabilitar']))
{
	$conexion->ManejoTransacciones("B");
	$result=true;
	
	if(!$usuarios->RehabilitaUsuario($_POST))
		$result=false;
		
	if($result)
		$_SESSION['msgactualizacion'] = "Se ha rehabilitado al usuario.";
}

if (isset($_POST['botonmodif']))
{
	$conexion->ManejoTransacciones("B");
	$result=true;
	
	if(!$usuarios->ModificarUsuarioInterno($_POST))
		$result=false;
		
	if($result)
		$_SESSION['msgactualizacion'] = "Se modificaron los datos correctamente.";
}

	if($result)
	{
		$conexion->ManejoTransacciones("C");
		ob_end_clean();
		header("Location:usuarios_modificar_datos.php?usuariocod=".$usuariocod."&md5=".$md5_post);
	}


$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>