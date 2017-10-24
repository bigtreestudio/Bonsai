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


header('Content-Type: text/html; charset=iso-8859-1'); 

$oModulos= new cCategoriasModulos($conexion);
if(!$oModulos->BuscarModuloxCodigo($_POST,$resultado,$numfilas))
	return false;
$datosModulo = $conexion->ObtenerSiguienteRegistro($resultado);	
$datosModulo['conexion'] = $conexion;
$datosModulo['zonamodulocod'] = $datosModulo['catmodulocod'];
?>
<form name="form_tap_modules" id="form_tap_modules" method="post" action="javascript:void(0)">
	<?php  	
	    echo FuncionesPHPLocal::RenderFile("tapas_modulos/form/".$datosModulo['moduloarchivo'],$datosModulo);
    ?>
    <input type="hidden" name="accionModulo" id="accionModulo" value="2" />
    <input type="hidden" name="catmodulocod" id="catmodulocod" value="<?php  echo $datosModulo['catmodulocod']?>" />
    <input type="hidden" id="modulocod" name="modulocod" value="<?php  echo $datosModulo['modulocod']?>"> 
</form>
<?php  
ob_end_flush();
?>