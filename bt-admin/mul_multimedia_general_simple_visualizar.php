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

$oEncabezados = new cEncabezados($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 

$oMultimedia = new cMultimedia($conexion,NULL);
if(!$oMultimedia->BuscarMultimediaxCodigo($_POST,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Archivo multimedia inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datosMultimedia = $conexion->ObtenerSiguienteRegistro($resultado);
$titulo = $datosMultimedia['multimedianombre'];
if($datosMultimedia['multimediatitulo']!="")
	$titulo = $datosMultimedia['multimediatitulo'];
$ret['Multimedia'] = $oMultimedia->VisualizarArchivoSimpleMultimedia($datosMultimedia);
$ret['Titulo'] = '<div>'.utf8_encode($titulo).'</div>';

ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>