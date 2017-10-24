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

$oRevistaTapasMultimedia = new cRevistaTapasMultimedia($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;

$conexion->ManejoTransacciones("B");

$texto = "";
$ret['header']="";

if(count($_GET)>0)
	$datos = $_GET;
else
	$datos = $_POST;

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
		$sizeLimit = TAMANIOARCHIVOS;
		if ($realSize > 0 && $realSize <= $sizeLimit) 
		{
			$pathinfo = pathinfo($nombrearchivo);
			$extension = strtolower($pathinfo['extension']);
			$datos['revtapamulubic'] = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;
			$datos['ubicacionfisica'] = $archivo;

			if ($oRevistaTapasMultimedia->Insertar($datos,$revtapacod))
			{
				$msgactualizacion = "Se ha agregado la tapa correctamente.";
				$ret['IsSuccess'] = true;
				$ret['success'] = true;

			}
		}else
			echo "Error, El maximo del archivo es de ".TAMANIOMB."MB";


		break;
	case 2:
		if ($oRevistaTapasMultimedia->Modificar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha modificado la tapa correctamente.";
			
		}
		break;
	case 3:
		if ($oRevistaTapasMultimedia->Eliminar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado la tapa correctamente.";
			$oRevistaTapas = new cRevistaTapas($conexion,"");
			if(!$oRevistaTapas->PublicarTapasImagenes($_POST))
				return false;
			
		}
		break;
	case 4:
		if ($oRevistaTapasMultimedia->DesActivar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha desactivado la tapa correctamente.";
			
		}
		break;

	case 5:
		if ($oRevistaTapasMultimedia->Activar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha activado la tapa correctamente.";
			
		}
		break;

	case 6:
		if ($oRevistaTapasMultimedia->ModificarOrden($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha modificado el orden correctamente.";
		}
		break;		
		
	
}

if ($ret['IsSuccess'])
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$texto));
	$conexion->ManejoTransacciones("C");

		FuncionesPHPLocal::ArmarLinkMD5("rev_tapas_am.php",array("revtapacod"=>$datos['revtapacod']),$get,$md5);
		$ret['header']="rev_tapas_am.php?".str_replace("&amp;","&",$get);
}
else
	$conexion->ManejoTransacciones("R");

	
$ret['Msg'] = utf8_encode(ob_get_contents());
ob_clean();
echo json_encode($ret);
ob_end_flush();
?>