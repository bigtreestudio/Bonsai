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
$sizeLimit = TAMANIOARCHIVOS;
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
			
		case "doc":
			$icono = "doc-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;
			
		case "docx":
			$icono = "docx-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;			

		case "xls":
			$icono = "xls-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;	

		case "xlsx":
			$icono = "xlsx-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;	

		case "pdf":
			$icono = "pdf-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;	

		case "txt":
			$icono = "txt-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;
			
		case "ppt":
			$icono = "ppt-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;
			
		case "pptx":
			$icono = "pptx-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;	
		
		case "zip":
			$icono = "zip-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;
			
		case "rar":
			$icono = "rar-128x128.png";
			$result['archivo'] = utf8_encode('<img src="'.DOMINIOADMIN.'images/fileformat/'.$icono.'" title="archivo '.$extension.'" />');
			break;				
	
		case "swf":
			list($anchobanner, $altobanner, $tipo, $atr) = getimagesize(CARPETA_SERVIDOR_MULTIMEDIA_TMP_FISICA.$name);
			if ($anchobanner>200)
			{
				$altobanner = round(200*$altobanner/$anchobanner);
				$anchobanner = 200;
			}
			$result['archivo'] = '<object type="application/x-shockwave-flash" data="'.DOMINIO_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_TMP.$name.'" width="'.$anchobanner.'" height="'.$altobanner.'">';
			$result['archivo'] .= '<param name="movie" value="'.DOMINIO_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_TMP.$name.'" />';
			$result['archivo'] .= '<param name="quality" value="high" />';
			$result['archivo'] .= '<param name="autoSize" value="false" />';
			$result['archivo'] .= '<param name="mantainAspectRadio" value="false" />';
			$result['archivo'] .= '<param name="wmode" value="transparent" />';
			$result['archivo'] .= '</object>';
			break;

		case "mp3":
			$result['archivo'] = '<object type="application/x-shockwave-flash" data="player/player.swf" width="200" height="24">';
			$result['archivo'] .= '<param name="movie" value="player/player.swf" />';
			$result['archivo'] .= '<param name="FlashVars" value="file='.DOMINIO_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_TMP.$name.'" />';
			$result['archivo'] .= '<param name="quality" value="high" />';
			$result['archivo'] .= '<param name="autoSize" value="false" />';
			$result['archivo'] .= '<param name="mantainAspectRadio" value="false" />';
			$result['archivo'] .= '<param name="wmode" value="transparent" />';
			$result['archivo'] .= '<param name="menu" value="false" />';
			$result['archivo'] .= '</object>';
			break;
		
		case "mp4":
		case "flv":
			$result['archivo'] = '<object type="application/x-shockwave-flash" data="player/player.swf" width="200" height="150">';
			$result['archivo'] .= '<param name="movie" value="player/player.swf" />';
			$result['archivo'] .= '<param name="FlashVars" value="file='.DOMINIO_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_TMP.$name.'" />';
			$result['archivo'] .= '<param name="quality" value="high" />';
			$result['archivo'] .= '<param name="autoSize" value="false" />';
			$result['archivo'] .= '<param name="mantainAspectRadio" value="false" />';
			$result['archivo'] .= '<param name="wmode" value="transparent" />';
			$result['archivo'] .= '<param name="menu" value="false" />';
			$result['archivo'] .= '</object>';
			break;
	}
	$result['success'] = true;
}else
{
    $result['success'] = false;   
    $result['Msg'] = "Error, El maximo del archivo es de ".TAMANIOMB."MB";
}
// to pass data through iframe you will need to encode all html tags
echo FuncionesPHPLocal::HtmlspecialcharsBigtree(json_encode($result), ENT_NOQUOTES);

?>