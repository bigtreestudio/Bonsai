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

header('Content-Type: text/html; charset=iso-8859-1'); 
$oUsuarios = new cUsuarios($conexion);
$oUsuariosModulosAcciones = new cUsuariosModulosAcciones($conexion);
$puedeBloquear = $oUsuariosModulosAcciones->TienePermisosAccion("000610");

$oProcesarHTML = new cTapasProcesarHTML($conexion,true);
if ($puedeBloquear)
	$oProcesarHTML->SetearPuedeBloquear();

if(!$oProcesarHTML->RecargarModulo($_POST,$html_generado))
	return false;
	
echo $html_generado;

?>