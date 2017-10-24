<?php 
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>'si'));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);


// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 8 * 1024 * 1024;
$result['success'] = true;

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


$pathinfo = pathinfo($nombrearchivo);
$extension = strtolower($pathinfo['extension']);
$name = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;
 
$datos['galeriacod'] = $_GET['galeriacod'];
$datos['ubicacionfisica'] = $archivo;
$datos['multimediaubic'] = $name;
$datos['multimedianombre'] = $nombrearchivo;
$result['success'] = true;
$oGaleriaMultimedia = new cGaleriasMultimedia($conexion,"");
if(!$oGaleriaMultimedia->InsertarImagen($datos))
{    
	$result['success'] = false;   
    $result['Msg'] = "Error, al insertar la imagen.";
}


echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(json_encode($result), ENT_NOQUOTES);
	
?>