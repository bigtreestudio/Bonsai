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
$oMultimedia = new cMultimedia($conexion,CARPNOTICIAS,"");

$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$msg['IsSucceed'] = false;
$datos = $_POST;

//Carga la clase correcta segun el prefijo y setea el parametro y valor del codigo recibido por POST
//El objeto de la clase se debe llamar $oMultimedia  

switch($datos['accion'])
{
	case 1:
		if($oMultimedia->SubirRelacionarPreview($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido el multimedia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 2:
		if($oMultimedia->DesAsociarMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha desasociado el multimedia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 3:
		if($oMultimedia->AsociarMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se relacionado correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	
	case 6:
		if($oMultimedia->ModificarTituloMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado el titulo correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 7:
		if($oMultimedia->ModificarDescripcionMultimedia($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado la descripcion correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 8:
		if($oMultimedia->ModificarPreview($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado la el preview correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
	case 9:
		$datos['multimediacodRelacion'] = "NULL";
		if($oMultimedia->ModificarPreview($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha eliminado la el preview correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	

	case 10:
		if($oMultimedia->SubirRelacionarPreview($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido el preview correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
		
}

if ($msg['IsSucceed'])
{
	$conexion->ManejoTransacciones("C");
}
else
{
	$conexion->ManejoTransacciones("R");
	$msg['Msg'] = utf8_encode(ob_get_contents());	
}
	
ob_clean();
echo json_encode($msg);
ob_end_flush();
?>