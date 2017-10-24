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

$oTapas= new cTapas($conexion);
header('Content-Type: text/html; charset=iso-8859-1'); 

$oModulosTapa= new cTapasZonasModulos($conexion);
if(!$oModulosTapa->BuscarModuloxCodigo($_POST,$resultado,$numfilas))
	return false;
$datosModulo = $conexion->ObtenerSiguienteRegistro($resultado);	
$datosModulo['conexion'] = $conexion;	
?>
<form name="form_tap_modules" id="form_tap_modules" method="post" action="javascript:void(0)">
	<?php  	
	    echo FuncionesPHPLocal::RenderFile("tapas_modulos/form/".$datosModulo['moduloarchivo'],$datosModulo);
    ?>
    <input type="hidden" name="accionModulo" id="accionModulo" value="2" />
    <input type="hidden" name="zonamodulocod" id="zonamodulocod" value="<?php  echo $datosModulo['zonamodulocod']?>" />
    <input type="hidden" id="modulocod" name="modulocod" value="<?php  echo $datosModulo['modulocod']?>"> 
    <input type="hidden" id="modulonombre" name="modulonombre" value="">
</form>
<?php  
ob_end_flush();
?>