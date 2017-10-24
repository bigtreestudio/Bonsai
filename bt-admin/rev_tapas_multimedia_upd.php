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

$oRevistaTapas= new cRevistaTapas($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$result['success'] = false;

$conexion->ManejoTransacciones("B");
$error = false;

$datos = $_GET;
		
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
	$result['revtapacod'] = $_GET['revtapacod'];
	$pathinfo = pathinfo($nombrearchivo);
	$extension = strtolower($pathinfo['extension']);
	$name = "archivo_".$result['revtapacod'].".".$extension;

	$target = fopen(CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS."/".$name, "w"); 
	fseek($temp, 0, SEEK_SET);
	stream_copy_to_stream($temp, $target);
	fclose($target);

	$result['ubicacionfisica'] = $archivo;
	$result['revtapaarchubic'] = "archivo_".$result['revtapacod'].".".$extension;
	$result['revtapaarchnombre'] = $nombrearchivo;
	$result['revtapaarchsize'] = $realSize;
	$result['archivo'] = utf8_encode('<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA."/tapas/N/".$name.'?rand='.rand(0,1000000000000000).'" title="imagen" />');

	$oImagen = new cFuncionesMultimedia();
	$carpetalocal = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS."/";
	$carpetadestino = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/"."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS_THUMBS."/";
	
	if (CROPEATHUMBTAPA)
	{
		if(!$oImagen->CropearImagen($result['revtapaarchubic'],$carpetalocal,$carpetadestino,TAPAMAXANCHOTHUMB,TAPAMAXALTOTHUMB))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar las imagenes.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	}else
	{
		if(!$oImagen->RedimensionarImagen($result['revtapaarchubic'],$carpetalocal,$carpetadestino,TAPAMAXANCHOTHUMB,TAPAMAXALTOTHUMB))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar las imagenes.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	}


	if ($oRevistaTapas->InsertarImagen($result))
	{
		$result['success'] = true;
		$msgactualizacion = "Se ha subido la tapa correctamente.";
		$texto="";
	}
	
}else
{
		$msgactualizacion = "Error, El maximo del archivo es de ".TAMANIOMB."MB";
}
if ($result['success'])
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$texto));
	$conexion->ManejoTransacciones("C");
}
else
	$conexion->ManejoTransacciones("R");

	
$result['Msg'] = ob_get_contents();


ob_clean();
echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(json_encode($result), ENT_NOQUOTES); 
ob_end_flush();
?>