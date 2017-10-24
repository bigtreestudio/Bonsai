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

$oMultimedia = new cMultimedia($conexion,"");

$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 
$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$msg = array();
$msg['IsSucceed'] = false;
$datos = $_POST;


switch($datos['accion'])
{
	case 1:
		if($oMultimedia->ModificarDescripcion($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado la descripcion correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
				
	case 2:
		if($oMultimedia->ModificarTitulo($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado el Titulo correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;
		
	case 3:
		if($oMultimedia->ModificarTituloDescripcion($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado correctamente a las ".date("H").":".date("i")."Hs"; 
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