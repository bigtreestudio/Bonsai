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
if (!isset($_POST['cajavideosexterno']))
	die();

switch($_POST['cajavideosexterno'])
{

	case "VIM":
		?>
			<iframe src="http://player.vimeo.com/video/<?php  echo $_POST['mulcodepage']?>" width="270" height="199" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
        <?php  
	break;	
	default:
		?>
        	<iframe width="270" height="199" src="http://www.youtube.com/embed/<?php  echo $_POST['mulcodepagey']?>" frameborder="0" allowfullscreen></iframe>
        <?php  
}
?>