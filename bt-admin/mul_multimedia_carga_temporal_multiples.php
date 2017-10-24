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
$oMultimediaTipos = new cMultimediaTipos($conexion,"");

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
			$datos['ubicacionfisica'] = $archivo;
			$datos['multimediaubic'] = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;
			$datos['multimedianombre'] = $nombrearchivo;
			$datos['multimedia_titulo'] = $nombrearchivo;
			$datos['multimedia_desc'] = "";
			$datos['multimediadesc'] = "";
			$datos['mul_multimedia_file'] = $nombrearchivo;
			$datos['mul_multimedia_name'] = $nombrearchivo;
			$datos['extension'] = $extension;
			$datos['multimediacatcod'] = "1";

		
			if($oMultimedia->InsertarImagen($datos,$multimediacod))
			{
				$msg['success'] = true;
				$msg['Msg'] = "Se ha subido la imagen correctamente a las ".date("H").":".date("i")."Hs"; 
			}	
		}else
			echo "Error, El maximo del archivo es de ".TAMANIOMB."MB";

		break;	
}

if ($msg['success'])
	$conexion->ManejoTransacciones("C");
else
	$conexion->ManejoTransacciones("R");
	
	
$msg['Msg'] = utf8_encode(ob_get_contents());	
ob_clean();
echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(json_encode($msg), ENT_NOQUOTES);
ob_end_flush();
?>