<?php 

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
$datos = $conexion->ObtenerSiguienteRegistro($resultado);
$html = $oMultimedia->VisualizarArchivoMultimedia($datos);
?>
<div style="margin-left:15px; text-align:center">
<?php echo $html;?>
<?php if($datos['multimediaconjuntocod']==4){?>
	<div>
    <? echo '<a href="'.DOMINIO_SERVIDOR_MULTIMEDIA.$datos['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$datos['multimediaubic'].'" target="_blank"><strong>Bajar archivo &raquo;&raquo;</strong></a><div>/multimedia/'.$datos['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$datos['multimediaubic'].'</div>';?>
    </div>
<?php }?>

<div style="font-size:12px; margin-top:5px;">
	<?php echo htmlspecialchars($datos['multimediadesc'],ENT_QUOTES);?>
</div>