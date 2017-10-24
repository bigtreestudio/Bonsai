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

$oTop= new cTop($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 



$msg = array();
$msg['success'] = false;
$datos = $_GET;

switch ($datos['accion'])
{
	case 1:
		if (isset($_FILES['qqfile']))
			$archivo = $_FILES['qqfile']['tmp_name'];
		else
			$archivo = "php://input";
		   
		$nombrearchivo = $_GET['qqfile'];
		$pathinfo = pathinfo($nombrearchivo);
		$extension = strtolower($pathinfo['extension']);
		$datos['topcod'] = $_GET['topcod'];
		$datos['ubicacionfisica'] = $archivo;
		$datos['toparchubic'] = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;
		$datos['toparchnombre'] = $nombrearchivo;
		if ($oTop->InsertarArchivo($datos))
		{
			$msg['success'] = true;
			$msgactualizacion = "Se ha subido el archivo del top correctamente.";
			$texto="";
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