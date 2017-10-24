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

$oGoogle = new cGoogle($conexion,"");
$datos = $_POST;
$ret['IsSuccess'] = false;

switch ($datos['accion'])
{
	case 1:
		if ($oGoogle->ModificarDatos($datos))
		{
			$msgactualizacion = "Se ha modificado los datos correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
		
	case 2:
			if ($oGoogle->ModificarProfiles($datos))
		{
			$msgactualizacion = "Se ha modificado los perfiles correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
		
}


if ($ret['IsSuccess'])
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	$conexion->ManejoTransacciones("C");
}
else
	$conexion->ManejoTransacciones("R");

	
$ret['Msg'] = utf8_encode(ob_get_contents());

ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>