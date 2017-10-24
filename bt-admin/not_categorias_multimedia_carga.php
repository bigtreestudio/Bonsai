<?php 
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


// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes

$result['success'] = false;

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
$oCategorias = new cCategorias($conexion,"");
if ($realSize > 0 && $realSize <= $sizeLimit) 
{
	$pathinfo = pathinfo($nombrearchivo);
	$extension = strtolower($pathinfo['extension']);
	$datos['catcod'] = $_GET['catcod'];
	$datos['ubicacionfisica'] = $archivo;
	$datos['imgubic'] = "categoria_".date("Ymdhis")."_".rand(0,10000).".".$extension;
	$datos['imgnombre'] = $nombrearchivo;
	if ($oCategorias->ModificarArchivo($datos))
	{
		$result['archivo'] = utf8_encode('<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA."categorias/".$datos['imgubic'].'" title="imagen" />');
		$result['success'] = true;
		$msgactualizacion = "Se ha subido el archivo de la categoria correctamente.";
		$texto="";
	}
}else
{
		$msgactualizacion = "Error, El maximo del archivo es de ".TAMANIOMB."MB";
}
// to pass data through iframe you will need to encode all html tags
echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(json_encode($result), ENT_NOQUOTES);

?>