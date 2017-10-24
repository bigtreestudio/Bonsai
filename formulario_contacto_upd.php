<?php  
ob_start();
session_start();

include("./config/include.php");
include(DIR_LIBRERIAS."class.phpmailer.php");
include(DIR_LIBRERIAS."autoload.php");
require_once(DIR_CLASES."cFormularios.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));
$lang = 'es';

$msg['IsSuccess']=false;

if (!isset($_POST['formulariocod']) || $_POST['formulariocod']=='' || strlen($_POST['formulariocod'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['formulariocod'],"NumericoEntero"))
{	
	header("Location:".DOMINIORAIZSITE."errormsg/404.php");
	die();
}

$datos = FuncionesPHPLocal::ConvertiraUtf8($_POST);
$oFormulariosService=new cFormularios($conexion);
$oFormularios = $oFormulariosService->BuscarFormulario($datos);
if ($oFormularios===false)
{	
	header("Location:".DOMINIORAIZSITE."errormsg/404.php");
	die();
}
$dominio = $oFormularios->getDominio();

$conexion->ManejoTransacciones("B");	
if (ValidarPHP($datos))
{
	if(!isset($datos['formulariotelefono']) || $datos['formulariotelefono']=="")
		$datos['formulariotelefono']="NULL";
	if(!isset($datos['formularioempresa']) || $datos['formularioempresa']=="")
		$datos['formularioempresa']="NULL";
				
	if(!isset($datos['formularioubic']) || $datos['formularioubic']=="")
		$datos['formularioubic']="NULL";
	if(!isset($datos['provinciacod']) || $datos['provinciacod']=="")
		$datos['provinciacod']="NULL";
	if(!isset($datos['departamentocod']) || $datos['departamentocod']=="")
		$datos['departamentocod']="NULL";
	if(!isset($datos['formulariodatosjson']) || $datos['formulariodatosjson']=="")
		$datos['formulariodatosjson']="NULL";

	if (!$oFormulariosService->InsertarFormulario($datos,$codigoinsertado))
	{
		$conexion->ManejoTransacciones("R");
	}
	else
	{
		$datos['formularioubic']="";
		$datos['provinciacod']="";
		$datos['departamentocod']="";
	
		
		if(!$oFormulariosService->EnviarMailFormulario($datos,$oFormularios))
			$conexion->ManejoTransacciones("R");
		else
		{
			$msg['IsSuccess'] = true;
			echo "Muchas gracias por contactarse con nosotros.<br><br>Estaremos respondiendo su solicitud a la brevedad.";
			$conexion->ManejoTransacciones("C");	
		}
	}
	
	
	$msg['Msg'] = ob_get_contents(); 
	ob_clean();
	echo json_encode($msg);
	ob_end_flush();
	die();
}
else
{
	$conexion->ManejoTransacciones("R");
}

$msg['Msg'] = ob_get_contents(); 
ob_clean();
echo json_encode($msg);
ob_end_flush();
die();

function ValidarPHP($datos)
{
	
		$arreglodatosjson = array();
		
		if (!isset($datos['g-recaptcha-response']) || $datos['g-recaptcha-response']=="")
		{
			echo "Debe ingresar un codigo verificador.";
			return false;
		}
		/*
		$objetoCurl = new \ReCaptcha\RequestMethod\CurlPost();
		$recaptcha = new \ReCaptcha\ReCaptcha(PRIVATEKEYCAPTCHA,$objetoCurl);
		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		if (!$resp->isSuccess())
		{
			 foreach ($resp->getErrorCodes() as $code) {
					echo '<tt>' , $code , '</tt> ';
				}
			echo "Debe ingresar un codigo verificador correcto.";
			return false;	
		}*/
		
	return true;
}
?>