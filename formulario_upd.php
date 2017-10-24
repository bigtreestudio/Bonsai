<?php  
ob_start();
session_start();
require_once("./config/include.php");
require_once(DIR_CLASES."cProvincias.class.php");
require_once(DIR_CLASES."cDepartamentos.class.php");
require_once(DIR_CLASES."cFormularios.class.php");
require_once(DIR_LIBRERIAS."recaptchalib.php");
require_once(DIR_LIBRERIAS."class.phpmailer.php");


$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

$_SESSION['error']="";
$_SESSION['accionmsg']="";

if (!isset($_POST['formulariocod']) || $_POST['formulariocod']=='' || strlen($_POST['formulariocod'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['formulariocod'],"NumericoEntero"))
{	
	header("Location:".DOMINIORAIZSITE."errormsg/404.php");
	die();
}

$datos = $_POST;
$oFormulariosService=new cFormularios($conexion);
$oFormularios = $oFormulariosService->BuscarFormulario($datos);
if ($oFormularios===false)
{	
	header("Location:".DOMINIORAIZSITE."errormsg/404.php");
	die();
}



$dominio = $oFormularios->getDominio();
if (ValidarPHP($conexion,$datos,$datosjson))
{
	$datos['formulariodatosjson'] = $datosjson;
	$conexion->ManejoTransacciones("B");
	
	if(!isset($datos['formularioubic']) || $datos['formularioubic']=="")
		$datos['formularioubic']="NULL";
	if(!isset($datos['provinciacod']) || $datos['provinciacod']=="")
		$datos['provinciacod']="NULL";
	if(!isset($datos['departamentocod']) || $datos['departamentocod']=="")
		$datos['departamentocod']="NULL";
	
	
	if($oFormulariosService->InsertarFormulario($datos,$codigoinsertado))
	{
		$datos['formularioubic']="";
		$datos['provinciacod']="";
		$datos['departamentocod']="";
		
		if(!$oFormulariosService->EnviarMailFormulario($datos,$oFormularios))
			$conexion->ManejoTransacciones("R");
		else
			$conexion->ManejoTransacciones("C");	
			
	}else
	{
		$_SESSION['formulario']=$datos;
		$_SESSION['accionmsg'] = "Error al enviar en e-mail, por favor aguarde unos instantes y reintente nuevamente.";
		$_SESSION['error'] = 8;
		header("Location:".DOMINIORAIZSITE.$dominio);
		die();
	}
}else
{
	$_SESSION['formulario']=$datos;
	header("Location:".DOMINIORAIZSITE.$dominio);
	die();
	
}

/*VALIDAR DATOS*/
/*ENVIAR EMAIL*/

function ValidarPHP($conexion,$datos,&$datosjson)
{
	$arreglodatosjson = array();
	if (!isset($datos['formularionombre']) || $datos['formularionombre']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar un nombre.";
		$_SESSION['error'] = 1;
		return false;	
	}
	if (!isset($datos['formulariomail']) || $datos['formulariomail']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar un email.";
		$_SESSION['error'] = 2;
		return false;	
	}
	if (!FuncionesPHPLocal::ValidarContenido($conexion,$datos['formulariomail'],"Email"))
	{
		$_SESSION['accionmsg'] = "Debe ingresar un email valido.";
		$_SESSION['error'] = 2;
		return false;	
	}
	/*if (!isset($datos['formularioubic']) || $datos['formularioubic']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar una ubicacion.";
		$_SESSION['error'] = 4;
		return false;	
	}*/
	/*
	/*if (!isset($datos['provinciacod']) || $datos['provinciacod']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar una provincia.";
		$_SESSION['error'] = 3;
		return false;	
	}

	if (file_exists("formulario/formulario_".$datos['formulariocod']."_upd.php"))
	{
		include("formulario/formulario_".$datos['formulariocod']."_upd.php");
		if (!ValidarDatosParticulares($datos,$arreglodatosjson))
			return  false;
	}*/
	//$datosjson = json_encode($arreglodatosjson);
	if (!isset($datos['formulariocomentario']) || $datos['formulariocomentario']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar un comentario.";
		$_SESSION['error'] = 5;
		return false;	
	}

	if (!isset($datos['recaptcha_challenge_field']) || $datos['recaptcha_challenge_field']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar un codigo verificador.";
		$_SESSION['error'] = 7;
		return false;	
	}

	 $resp = recaptcha_check_answer (PRIVATEKEYCAPTCHA,$_SERVER["REMOTE_ADDR"],
                                        $datos["recaptcha_challenge_field"],
                                        $datos["recaptcha_response_field"]);
	
				
	if ($resp->is_valid)
		return true;
	else
	{
		$_SESSION['accionmsg'] = "Debe ingresar un codigo verificador correcto.";
		$_SESSION['error'] = 7;
		return false;	
	}		

	return false;
}


ob_end_clean();
header("Location:".DOMINIORAIZSITE.$dominio."/ok");
die();

?>