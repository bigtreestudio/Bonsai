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
$oPaginasModulos = new cPaginasModulos($conexion);
$oPaginasWorkflow = new cPaginasWorkflowRoles($conexion);
$oPaginas = new cPaginas($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 

$ret['IsSuccess'] = false;
$msgactualizacion='';

if (!$oPaginas->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;
	
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, pagina inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	return false;
}
$datospagina =  $conexion->ObtenerSiguienteRegistro($resultado); 

$datos['rolcod'] = $_SESSION['rolcod'];
$datos['pagestadocod'] = $datospagina['pagestadocod'];
if(!$oPaginasWorkflow->ObtenerAccionesRol($datos,$resultadoacciones,$numfilasacciones))
	return false;


if ($numfilasacciones==0)
{
	echo "No tiene permisos para realizar dicha accion";
	$ret['IsSuccess'] = false;	
	$ret['Msg'] = ob_get_contents();
	ob_clean();
	echo json_encode($ret); 
	ob_end_flush();
	die();
}


switch($_POST['accionModulo'])
{
	
	case 1:
		$_POST['modulodata'] = json_encode($_POST);
		if ($oPaginasModulos->ActualizarInsertar($_POST,$codigoinsertado))
		{
			$msgactualizacion = "Se ha insertado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	
	case 2:
		$_POST['modulodata'] = json_encode($_POST);
		if ($oPaginasModulos->Modificar($_POST))
		{
			$msgactualizacion = "Se ha modificado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	
	case 3;
		if ($oPaginasModulos->Eliminar($_POST))
		{
			$msgactualizacion = "Se ha eliminado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	case 4;
		if ($oPaginasModulos->ModificarOrden($_POST))
		{
			$msgactualizacion = "Se ha modificado el orden de los modulos correctamente.";
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