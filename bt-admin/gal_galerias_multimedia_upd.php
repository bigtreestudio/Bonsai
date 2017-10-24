<?php  

ini_set('memory_limit','128M');
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

$oGaleriasMultimedia = new cGaleriasMultimedia($conexion,"");

$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$msg['success'] = false;
if(count($_GET)>0)
{
	$datos=FuncionesPHPLocal::ConvertiraUtf8 ($_GET);
}else
{	
	$datos=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);
}


switch($datos['accion'])
{
	case 1:
		if (isset($_FILES['qqfile']))
		{
			$archivo = $_FILES['qqfile']['tmp_name'];
			$nombrearchivo =  $_FILES['qqfile']['name'];
		}
		else
		{
			$archivo = "php://input";
			$nombrearchivo = $_GET['qqfile'];
		}
		$input = fopen($archivo, "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
		$sizeLimit = TAMANIOARCHIVOS;
		if ($realSize > 0 && $realSize <= $sizeLimit) 
		{
			$pathinfo = pathinfo($nombrearchivo);
			$extension = strtolower($pathinfo['extension']);
			$datos['galeriacod'] = $_GET['galeriacod'];
			$datos['ubicacionfisica'] = $archivo;
			$datos['multimediaubic'] = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;
			$datos['multimedianombre'] = $nombrearchivo;
			if($oGaleriasMultimedia->InsertarImagen($datos))
			{
				$msg['success'] = true;
				$msg['Msg'] = "Se ha subido la imagen correctamente a las ".date("H").":".date("i")."Hs"; 
			}	
		}else
			echo "Error, El maximo del archivo es de ".TAMANIOMB."MB";

		break;	
	case 2:
		$datos=FuncionesPHPLocal::ConvertiraUtf8 ($datos);
		if($oGaleriasMultimedia->InsertarVideo($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha subido el video correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 3:
		if (isset($_FILES['qqfile']))
		{
			$archivo = $_FILES['qqfile']['tmp_name'];
			$nombrearchivo =  $_FILES['qqfile']['name'];
		}
		else
		{
			$archivo = "php://input";
			$nombrearchivo = $_GET['qqfile'];
		}

		$input = fopen($archivo, "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
		$sizeLimit = TAMANIOARCHIVOSAUDIO;
		if ($realSize > 0 && $realSize <= $sizeLimit) 
		{
			$pathinfo = pathinfo($nombrearchivo);
			$extension = strtolower($pathinfo['extension']);
			$datos['galeriacod'] = $_GET['galeriacod'];
			$datos['ubicacionfisica'] = $archivo;
			$datos['multimediaubic'] = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;
			$datos['multimedianombre'] = $nombrearchivo;
			
			if($oGaleriasMultimedia->InsertarAudio($datos))
			{
				$msg['success'] = true;
				$msg['Msg'] = "Se ha subido el audio correctamente a las ".date("H").":".date("i")."Hs"; 
			}	
		}else
			echo "Error, El maximo del archivo es de ".TAMANIOAUDIOMB."MB";	

		break;	
	case 4:
		if($oGaleriasMultimedia->Eliminar($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha eliminado la imagen de la galeria correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 5:
		if($oGaleriasMultimedia->ModificarOrden($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha modificado el orden correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 6:
		if($oGaleriasMultimedia->InsertarImagenMultimedia($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha asociado la imagen de la galeria correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 7:
		if($oGaleriasMultimedia->InsertarVideoMultimedia($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha asociado el video de la galeria correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 8:
		if($oGaleriasMultimedia->InsertarAudioMultimedia($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha asociado el audio de la galeria correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
	case 9:
		if($oGaleriasMultimedia->ModificarTituloMultimedia($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha modificado el titulo del multimedia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
	case 10:
		if($oGaleriasMultimedia->ModificarDescripcionMultimedia($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha modificado el titulo del multimedia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
	case 11:
		$datos['multimediacodRelacion'] = "NULL";
		if($oGaleriasMultimedia->ModificarPreview($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha eliminado la el preview correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
	case 12:
		if($oGaleriasMultimedia->ModificarPreview($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha subido el preview correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
		
	case 13:
		if($oGaleriasMultimedia->EliminarMultimediasSeleccionados($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha eliminado los multimedia correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	
	case 14:
		if($oGaleriasMultimedia->SubirRelacionarPreview($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha subido el preview correctamente a las ".date("H").":".date("i")."Hs"; 
		}	
		break;	
	case 15:
	
		$datos['tipovideosubir']=2;//VIDEO PROPIETARIO
		if($oGaleriasMultimedia->InsertarVideo($datos))
		{
			$msg['success'] = true;
			$msg['Msg'] = "Se ha subido la imagen correctamente a las ".date("H").":".date("i")."Hs"; 
		}	

		break;	
}

if ($msg['success'])
{
	$conexion->ManejoTransacciones("C");
	$oGalerias = new cGalerias($conexion,"");
	if(!$oGalerias->Publicar($datos)) 
		return false;
}
else
	$conexion->ManejoTransacciones("R");
	
	
$msg['Msg'] = utf8_encode(ob_get_contents());	
ob_clean();
echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(json_encode($msg), ENT_NOQUOTES);
ob_end_flush();
?>