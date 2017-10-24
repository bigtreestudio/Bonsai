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

$oNoticiasGalerias = new cNoticiasGalerias($conexion,"");

$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 
$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$msg = array();
$msg['IsSucceed'] = false;
$datos = $_POST;
$datos['rolcod'] = $_SESSION['rolcod'];

switch($datos['accion'])
{
	case 1:
		if($oNoticiasGalerias->Insertar($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha agregado la galeria relacionada correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 2:
		if($oNoticiasGalerias->Eliminar($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha eliminado la galeria relacionada correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 3:
		if($oNoticiasGalerias->ModificarOrden($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado el orden de las galeria relacionadas correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 4:
		if($oNoticiasGalerias->DestacarGaleria($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado la importancia de la galeria correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 5:
		if($oNoticiasGalerias->NoDestacarGaleria($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado la importancia de la galeria correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	
}

if ($msg['IsSucceed'])
	$conexion->ManejoTransacciones("C");
else
{
	$msg['Msg'] = ob_get_contents();
	$conexion->ManejoTransacciones("R");
}


ob_clean();
echo json_encode($msg);
ob_end_flush();
?>