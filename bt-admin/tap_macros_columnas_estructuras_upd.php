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

$oMacrosColumnasEstructuras = new cMacrosColumnasEstructuras($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;

$conexion->ManejoTransacciones("B");

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";

switch ($_POST['accion'])
{
	case 1:
		if ($oMacrosColumnasEstructuras->Insertar($_POST,$plantmacrocod))
		{
			$msgactualizacion = "Se ha agregado una estructura correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	case 2:
		if ($oMacrosColumnasEstructuras->Modificar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha modificado la estructura correctamente.";
			
		}
		break;
	case 3:
		if ($oMacrosColumnasEstructuras->Eliminar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado la estructura correctamente.";
			
		}
		break;
	
	case 4:
		if($oMacrosColumnasEstructuras->ModificarOrden($_POST))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado el orden de las estructuras correctamente."; 
		}
		
		break;		
}


if ($ret['IsSuccess'])
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$texto));
	$conexion->ManejoTransacciones("C");
}
else
	$conexion->ManejoTransacciones("R");

	
$ret['Msg'] = ob_get_contents();

//ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>