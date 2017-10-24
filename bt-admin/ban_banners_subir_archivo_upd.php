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

$oEncabezados = new cEncabezados($conexion);

$oBanners = new cBanners($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 



$msg = array();
$msg['success'] = false;
$datos = $_GET;

switch ($datos['accion'])
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
		$sizeLimit = TAMANIOARCHIVOSAUDIO;
		if ($realSize > 0 && $realSize <= $sizeLimit) 
		{
			$pathinfo = pathinfo($nombrearchivo);
			$extension = strtolower($pathinfo['extension']);
			$datos['bannercod'] = $_GET['bannercod'];
			$datos['ubicacionfisica'] = $archivo;
			$datos['bannerarchubic'] = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;
			$datos['bannerarchnombre'] = $nombrearchivo;
			if ($oBanners->InsertarArchivo($datos))
			{
				$msg['success'] = true;
				$msgactualizacion = "Se ha subido el archivo del banner correctamente.";
				$texto="";
			}
		}else
		{
				$msgactualizacion = "Error, El maximo del archivo es de ".TAMANIOMB."MB";
		}
		break;
}


if ($msg['success'])
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$texto));
	$conexion->ManejoTransacciones("C");
}
else
	$conexion->ManejoTransacciones("R");

	
$msg['Msg'] = utf8_encode(ob_get_contents());

ob_clean();
echo json_encode($msg); 
ob_end_flush();
?>