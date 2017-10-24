<?php  
ob_start();
require('./config/include.php');
include(DIR_LIBRERIAS."autoload.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y borra todo

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$usuarios=new cUsuarios($conexion);
$msg="";

if (!isset($_POST['usuarioidmail']) || $_POST['usuarioidmail']=="")
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error debe ingresar un email. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	ob_end_flush();
	die();
}


$mail = $_POST['usuarioidmail'];
if (!FuncionesPHPLocal::ValidarContenido($conexion,$mail,"Email"))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error debe ingresar un email valido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	ob_end_flush();
	die();
}

//1-valido el login
if (LOGINCAPTCHA==1)
{
	if (!isset($_POST['g-recaptcha-response']) || $_POST['g-recaptcha-response']=="")
	{
		//header("Location:index.php?msg_error=3&usuarioiderror=".$_POST['usuarioid']);
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Es necesario tildar el casillero verificador. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		ob_end_flush();
		die();
	}
	
	$objetoCurl = new \ReCaptcha\RequestMethod\CurlPost();
	$recaptcha = new \ReCaptcha\ReCaptcha(PRIVATEKEYCAPTCHA,$objetoCurl);
	
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	if (!$resp->isSuccess())
	{
		foreach ($resp->getErrorCodes() as $code) {
			echo '<tt>' , $code , '</tt> ';
		}
		header("Location:index.php?msg_error=4&usuarioiderror=".$_POST['usuarioid']);
		die();
	}
}

$conexion->ManejoTransacciones("B");
$datos['usuarioemail'] = $mail;
if($usuarios->ReenviarContrasenia($datos))
{
	$msg="Se envió su nueva contraseña al mail indicado. El envío puede demorar unos minutos.";
	$conexion->ManejoTransacciones("C");
	ob_clean();?>
	<script>window.location="index.php?msg_accion=1";</script><?php
	//header("Location:index.php?msg_accion=1");
	
	die();
}else
	$conexion->ManejoTransacciones("R");
?>

<?php  

//$oEncabezados->PieMenuEmergenteLogin();
ob_end_flush();
?>
