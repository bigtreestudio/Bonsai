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

header('Content-Type: text/html; charset=iso-8859-1'); 


$oNoticias=new cNoticias($conexion,"");
$datos = array();
$datos['orderby'] = "noticiatitulo";
$datos['noticiatitulo'] = utf8_decode(trim($_GET["term"]));
$datos['limit'] = "LIMIT 0,10";
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['estadonoticiacopiacod'] = 0;
$datos['estadonoticiacopiacodorig'] = 1;

if (!$oNoticias->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

$i = 0;
$arreglo = array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$arreglo[$i]['label'] =  utf8_encode($fila['noticiatitulo']);
	$arreglo[$i]['noticiacod'] = $fila['noticiacod'];
	$i++;
}
echo json_encode($arreglo);
?>