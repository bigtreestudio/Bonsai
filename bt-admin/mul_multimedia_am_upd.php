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

$oMultimediaGeneral = new cMultimediaGeneral($conexion);
$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$msg['IsSucceed'] = false;
$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);
$datos = $_POST;

switch($datos['accion'])
{
	case 1:
		if($oMultimediaGeneral->InsertarImagen($datos,$multimediacod))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido la imagen correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['multimediacod'] = $multimediacod; 
			$oMultimedia =  new cMultimedia($conexion,"noticias/","");
			$oMultimedia->BuscarMultimediaxCodigo($msg,$resultado,$numfilas);
			$datosMultimedia = $conexion->ObtenerSiguienteRegistro($resultado);
			$msg['multimediaubic'] = $oMultimedia->DevolverDireccionImg($datosMultimedia);
		}	
		break;	
	case 2:
		if($oMultimediaGeneral->InsertarVideo($datos,$multimediacod))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido el video correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['multimediacod'] = $multimediacod; 
		}	
		break;	
	case 3:
		if($oMultimediaGeneral->InsertarAudio($datos,$multimediacod))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido el audio correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['multimediacod'] = $multimediacod; 
		}	
		break;	
	case 4:
		if($oMultimediaGeneral->InsertarArchivo($datos,$multimediacod))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha subido el archivo correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['multimediacod'] = $multimediacod; 
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