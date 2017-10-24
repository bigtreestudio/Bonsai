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

$oMacrosEstructuras = new cMacrosEstructuras($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1'); 

/*
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("tapacod"=>$_GET['tapacod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	ob_clean();
	header("Location:tap_tapas_confeccionar_error.php");
	die();
}
*/
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$datos = $_POST;
if(!$oMacrosEstructuras->BuscarxMacro($datos,$resultadozonas,$numfilas))
	return false;

$html_generado = '<div style="font-size:11px; color:#000000; margin-bottom:10px;">Mantenga presionada el macro y arrastrelo hacia la posici&oacute;n deseada</div>';
$html_generado .= '<div style="width:100%;" class="selmacro">';

$html_generado .= '<div class="zonascargadas clearfix " id="plantmacrocod_0" rel="macro_'.$datos['macrocod'].'" style="position:relative">';
while ($datoszona = $conexion->ObtenerSiguienteRegistro($resultadozonas))
{
	$html_generado .= '<div class="zona '.$datoszona['estructuraclass'].'">';
		$html_generado .= '<div style="text-align:center;background-color:#d2d2d2;">'.$datoszona['estructuradesc']."</div>";
	$html_generado .= '</div>';
}
$html_generado .= '</div>';

$html_generado .= '</div>';
echo $html_generado;


?>