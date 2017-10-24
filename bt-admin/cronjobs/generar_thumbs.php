<?php  
set_time_limit ( 360 );
header('Content-Type: text/html; charset=iso-8859-1'); 
//error_reporting(0);
error_reporting(E_ALL);

include('../config/include.php');
include('../Librerias/Sitemap.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);



$pisaExistentes = false;
if (isset($_GET['pisaexistentes']) && $_GET['pisaexistentes']==1)
	$pisaExistentes = true;
	

$formatocarpeta = "Thumbs";
$carpetaFecha = date("Ym")."/";
$carpetadestino = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."noticias/".$formatocarpeta."/";
$carpetalocal = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."noticias/"."N/";

$entries = "";
$arreglotapas=array();
$i=0;

$oImagen = new cFuncionesMultimedia();

$oNoticias = new cNoticiasPublicacion($conexion);
$oMultimedia = new cMultimedia($conexion,"noticias/");
$datosNoticia["multimediaconjuntocod"]=1;
$datosNoticia["orderby"]="multimediacod asc";
if(!$oMultimedia->BusquedaAvanzada($datosNoticia,$resultadoNoticias,$numfilas))
	return false;

while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoNoticias))
{
	$fecha = substr($fila["multimediaubic"],0,6);
	print_r($fecha);
	echo "<br>";
	
	if (!is_dir ($carpetadestino.$fecha))
	{
		if (!mkdir($carpetadestino.$fecha,0777))
		{
			echo "Error al crear la carpeta en el servidor de multimedia (".$datoscategoria['multimediacatcarpeta']."".$datos['formatocarpeta'].") ";
			die();
		}
	}else
		chmod($carpetadestino.$fecha,0777);
	

	echo "<br>Archivo: ".$fila["multimediaubic"]."<br>";
	
	if (file_exists($carpetalocal.$fila["multimediaubic"]) && (!file_exists($carpetadestino.$fila["multimediaubic"]) || $pisaExistentes==true))
	{
		$nombrearchivo=$fila["multimediaubic"];
		echo $nombrearchivo;
		echo "<br>";
		
		
		//if ($filaFormato['formatocrop']==1)
		{
			if(!$oImagen->CropearImagen($nombrearchivo,$carpetalocal,$carpetadestino,TAMANIOTHUMB,TAMANIOTHUMB))
			{
				echo "error1";
			}
		}/*else
		{
			if(!$oImagen->RedimensionarImagen($nombrearchivo,$carpetalocal,$carpetadestino,$filaFormato['formatoancho'],$filaFormato['formatoalto']))
			{
				echo "error2";
			}
		}*/
		chmod($carpetadestino.$fila["multimediaubic"],0777);
		
	}
}



?>