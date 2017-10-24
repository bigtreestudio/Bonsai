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

$oNoticias = new cNoticias($conexion,"");
$oNoticiasAcciones = new cNoticiasWorkflowRoles($conexion,"");

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
		if($oNoticias->Insertar($datos,$noticiacod,$datosnuevos))
		{
			FuncionesPHPLocal::ArmarLinkMD5("not_noticias_am.php",array("noticiacod"=>$noticiacod),$get,$md5);
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha guardado en borrador correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['noticiacod'] = $noticiacod; 
			$msg['md5'] = $md5; 
			$msg['noticiaestadodesc'] = "En Borrador"; 
			
			$msg['noticiaestadodesc'] = utf8_encode($datosnuevos['noticiaestadodesc']); 
			$msg['cambioestado'] = $datosnuevos['cambioestado']; 
			$msg['noticiaestadocod'] = $datosnuevos['noticiaestadocod']; 
			
			$datosacciones['rolcod'] = $_SESSION['rolcod'];
			$datosacciones['noticiacod'] = $noticiacod;
			$datosacciones['noticiaestadocod'] = $datosnuevos['noticiaestadocod'];
			if(!$oNoticiasAcciones->ObtenerAccionesRol($datosacciones,$resultadoacciones,$numfilasacciones))
				return false;
				
			$msg['tienepermisos'] = false; 
			if ($numfilasacciones>0)
				$msg['tienepermisos'] = true; 

		}	
		break;	

	case 2:
		if($oNoticias->Modificar($datos,$datosnuevos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha guardado correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['noticiacod'] = $datos['noticiacod']; 
			$msg['noticiaestadodesc'] = utf8_encode($datosnuevos['noticiaestadodesc']); 
			$msg['cambioestado'] = $datosnuevos['cambioestado']; 
			$msg['noticiaestadocod'] = $datosnuevos['noticiaestadocod']; 
			
			$datosacciones['rolcod'] = $_SESSION['rolcod'];
			$datosacciones['noticiaestadocod'] = $datosnuevos['noticiaestadocod'];
			if(!$oNoticiasAcciones->ObtenerAccionesRol($datosacciones,$resultadoacciones,$numfilasacciones))
				return false;
				
			$msg['tienepermisos'] = false; 
			if ($numfilasacciones>0)
				$msg['tienepermisos'] = true; 
					
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
//ob_end_flush();
?>