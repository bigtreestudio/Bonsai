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
$oModulos= new cCategoriasModulos($conexion);
$oCategorias = new cCategorias($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 

$ret['IsSuccess'] = false;
$msgactualizacion='';

if (!$oCategorias->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;
	
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, categoria inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	return false;
}
$datoscategoria =  $conexion->ObtenerSiguienteRegistro($resultado); 


switch($_POST['accionModulo'])
{
	
	case 1:
		$_POST['modulodata'] = json_encode($_POST);
		if ($oModulos->ActualizarInsertar($_POST,$codigoinsertado))
		{
			$msgactualizacion = "Se ha insertado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	
	case 2:
		$_POST['modulodata'] = json_encode($_POST);
		if ($oModulos->Modificar($_POST))
		{
			$msgactualizacion = "Se ha modificado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	
	case 3;
		if ($oModulos->Eliminar($_POST))
		{
			$msgactualizacion = "Se ha eliminado el modulo correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	case 4;
		if ($oModulos->ModificarOrden($_POST))
		{
			$msgactualizacion = "Se ha modificado el orden de los modulos correctamente.";
			$ret['IsSuccess'] = true;
		}
		break;	
	case 5;
		if ($oModulos->GenerarColumnas($_POST))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
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