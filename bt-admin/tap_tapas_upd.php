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

$oTapas= new cTapas($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;

$conexion->ManejoTransacciones("B");
$error = false;


$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";

switch ($_POST['accion'])
{
	case 1:
		if ($oTapas->InsertarTapa($_POST,$tapacod))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha agregado la tapa correctamente.";
		}
		break;
	case 2:

		if ($oTapas->ModificarTapa($_POST))
		{
						

			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado la tapa correctamente.";
		}
		break;
	case 3:
		if ($oTapas->EliminarTapa($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado la tapa correctamente.";
		}
		break;
		
	case 4:
		if ($oTapas->DesActivarTapa($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha desactivado la tapa correctamente.";
		}
		break;

	case 5:
		if ($oTapas->ActivarTapa($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha activado la tapa correctamente.";
		}
		break;
	
	case 6:
		
		if($oTapas->ModificarMetadata($_POST))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado la metadata de la tapa."; 
		}
		
		break;		
	
}

if ($ret['IsSuccess'])
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$texto));
	$conexion->ManejoTransacciones("C");
}
else
{
	$conexion->ManejoTransacciones("R");
}
	
$ret['Msg'] = ob_get_contents();

ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>