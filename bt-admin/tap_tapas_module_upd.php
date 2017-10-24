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
$oTapasZonasModulos = new cTapasZonasModulos($conexion);
$oTapasZonasModulosTmp = new cTapasZonasModulosTmp($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 

$ret['IsSuccess'] = false;
$msgactualizacion='';
switch($_POST['accionModulo'])
{
	
	case 1:
		
		if ($oTapasZonasModulosTmp->Insertar($_POST))
		{
			$msgactualizacion = "Se ha insertado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	
	case 2:
		if ($oTapasZonasModulos->Modificar($_POST))
		{
			$msgactualizacion = "Se ha modificado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	
	case 3;
		if ($oTapasZonasModulos->Eliminar($_POST))
		{
			$msgactualizacion = "Se ha eliminado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	case 4;
		if ($oTapasZonasModulos->InsertarModuloEnZona($_POST,$codigoinsertado))
		{
			$msgactualizacion = "Se ha insertado el modulo en la zona correctamente.";
			$ret['IsSuccess'] = true;
			$ret['zonamodulocod'] = $codigoinsertado;
		}
		break;		
	case 5;
		if ($oTapasZonasModulos->ModificarOrdenZona($_POST))
		{
			$msgactualizacion = "Se ha modificado el orden de las zonas correctamente.";
			$ret['IsSuccess'] = true;
		}
		break;		
	case 6;
		if ($oTapasZonasModulosTmp->Eliminar($_POST))
		{
			$msgactualizacion = "Se ha eliminado el modulo temporal correctamente.";
			$ret['IsSuccess'] = true;
		}
		break;			
	case 7:
		if ($oTapasZonasModulos->ModificarAgregarParametrosExtras($_POST))
		{
			$msgactualizacion = "Se ha modificado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	case 8:
		if ($oTapasZonasModulos->ModificarBloqueoZona($_POST))
		{
			$msgactualizacion = "Se ha modificado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
}
if ($msgactualizacion != '')
	echo $msgactualizacion;


$ret['Msg'] = ob_get_contents();

ob_clean();
echo json_encode($ret); 
ob_end_flush();


?>