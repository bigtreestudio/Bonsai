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
if (!isset($_POST['cajasonidoexterno']))
	die();

switch($_POST['cajasonidoexterno'])
{

	case "GOEA":
		?>
        	<iframe width="580" height="115" src="http://www.goear.com/embed/sound/<?php  echo $_POST['multcodgoearaudio']?>" marginheight="0" align="top" scrolling="no" frameborder="0" hspace="0" vspace="0" allowfullscreen></iframe>
        <?php  
	break;	
	default:

}
?>