<? 
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

$oEncabezados = new cEncabezados($conexion);

$oGalerias = new cGalerias($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$msg['IsSucceed'] = false;

$conexion->ManejoTransacciones("B");
$error = false;

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";

switch ($_POST['accion'])
{
	case 1:
		if ($oGalerias->InsertarGaleria($_POST,$codigoinsertado))
		{
			$msg['Msg'] = "Se ha agregado correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['IsSucceed'] = true;	
			$msg['galeriacod'] = $codigoinsertado; 
			$msg['header'] = "gal_galerias_am.php?galeriacod=".$codigoinsertado; 
		}
		break;
	case 2:
		if ($oGalerias->ModificarGaleria($_POST))
		{
			
			$msg['Msg'] = "Se ha modificado correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['IsSucceed'] = true;	
		}
		break;
	case 3:
		if ($oGalerias->EliminarGaleria($_POST))
		{
			$msg['IsSucceed'] = true;	
			$msg['Msg'] = "Se ha eliminado la galeria correctamente.";
			
		}
		break;
		
	case 4:
		if ($oGalerias->DesActivarGaleria($_POST))
		{
			$msg['IsSucceed'] = true;	
			$msg['Msg'] = "Se ha desactivado la galeria correctamente.";
			
		}
		break;

	case 5:
		if ($oGalerias->ActivarGaleria($_POST))
		{
			$msg['IsSucceed'] = true;	
			$msg['Msg'] = "Se ha activado la galeria correctamente.";
			
		}
		break;
	
	case 6:
		
		if($oGalerias->ModificarOrden($_POST))
		{
			$msg['IsSucceed'] = true;	
			$msg['Msg'] = "Se ha modificado el orden de las galerias correctamente."; 
		}
		
		break;		

	
}


if ($msg['IsSucceed'])
{	
	$conexion->ManejoTransacciones("C");
}
else
{
	$msg['Msg'] = utf8_encode(ob_get_contents());
	$conexion->ManejoTransacciones("R");

}


ob_clean();
echo json_encode($msg); 
ob_end_flush();
?>