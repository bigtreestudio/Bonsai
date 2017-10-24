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
   
   
$input = fopen($archivo, "r");
$temp = tmpfile();
$realSize = stream_copy_to_stream($input, $temp);
fclose($input);

$pathinfo = pathinfo($nombrearchivo);
$extension = strtolower($pathinfo['extension']);
$name = "archivo_".date("Ymdhis")."_".rand(0,10000).".".$extension;

$sizeLimit = TAMANIOARCHIVOS;
$tamaniodisp = TAMANIOMB;
if ($extension=="mp3")
{
	$sizeLimit = TAMANIOARCHIVOSAUDIO;
	$tamaniodisp = TAMANIOAUDIOMB;
}
if ($realSize > 0 && $realSize <= $sizeLimit) {
	
	$target = fopen(CARPETA_SERVIDOR_MULTIMEDIA_TMP_FISICA.$name, "w");        
	fseek($temp, 0, SEEK_SET);
	stream_copy_to_stream($temp, $target);
	fclose($target);
    $result['nombrearchivotmp'] = $name;
    $result['nombrearchivo'] = $nombrearchivo;
    $result['size'] = $realSize;

	$tipo = "";
	switch($extension)
	{
		case "jpg":
		case "gif":
		case "png":
			$result['archivo'] = utf8_encode('<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_TMP.$name.'" title="imagen" />');
			break;
	
		

		
	}
	$result['success'] = true;
}else
{
    $result['success'] = false;   
    $result['Msg'] = "Error, El maximo del archivo es de ".$tamaniodisp."MB";
}
// to pass data through iframe you will need to encode all html tags
echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(json_encode($result), ENT_NOQUOTES);

?>