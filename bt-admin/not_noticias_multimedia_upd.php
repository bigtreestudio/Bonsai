<?php  
ob_start();
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oNoticiasMultimedia = new cNoticiasMultimedia($conexion,"");

$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 
if ($_POST['accion']!=5)
	$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$msg = array();
$msg['IsSucceed'] = false;
$datos = $_POST;

switch($datos['accion'])
{
	case 1:
		if($oNoticiasMultimedia->InsertarImagen($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido la imagen correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 2:
		if($oNoticiasMultimedia->InsertarVideo($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido el video correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 3:
		if($oNoticiasMultimedia->InsertarAudio($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido el audio correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 4:
		if($oNoticiasMultimedia->Eliminar($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha eliminado la imagen de la noticia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 5:
		if($oNoticiasMultimedia->ModificarOrden($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado el orden correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 6:
		if($oNoticiasMultimedia->InsertarImagenMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha asociado la imagen de la noticia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 7:
		if($oNoticiasMultimedia->InsertarVideoMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha asociado el video de la noticia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 8:
		if($oNoticiasMultimedia->InsertarAudioMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha asociado el audio de la noticia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
	case 9:
		if($oNoticiasMultimedia->ModificarHomeMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado la imagen de home de la noticia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
		
}

if ($msg['IsSucceed'])
	$conexion->ManejoTransacciones("C");
else
	$conexion->ManejoTransacciones("R");
	
	
$msg['Msg'] = utf8_encode(ob_get_contents());	
ob_clean();
echo json_encode($msg);
ob_end_flush();
?>