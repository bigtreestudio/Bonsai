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
// Inicio de pantalla


if(isset($_GET["usuariocod"]) && $_GET["usuariocod"]!="")
{
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_GET['usuariocod'],"act"=>$_GET['act']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$usuariocod = $_POST['usuariocod'];
}else
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Debe seleccionar un usuario a bloquear / desbloquear.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}



if (isset($_GET['act']) && $_GET['act']==2)
{
	$conexion->ManejoTransacciones("B");
	$result=true;
	
	if(!$usuarios->BorrarUsuario($_GET))
		$result=false;
		
	if($result)
		$_SESSION['msgactualizacion'] = "Se ha bloqueado al usuario.";
}

if (isset($_GET['act']) && $_GET['act']==1)
{
	$conexion->ManejoTransacciones("B");
	$result=true;
	
	if(!$usuarios->RehabilitaUsuario($_GET))
		$result=false;
		
	if($result)
		$_SESSION['msgactualizacion'] = "Se ha rehabilitado al usuario.";
}


if($result)
{
	$conexion->ManejoTransacciones("C");
	ob_end_clean();
	header("Location:usuario_lst.php");
	die();
}


$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>