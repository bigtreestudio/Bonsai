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

$oPlantillasMacrosZonasColumnas= new cPlantillasMacrosZonasColumnas($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1'); 


$texto = "";
$ret['IsSuccess'] = false;
$ret['Msg'] = "";
$conexion->ManejoTransacciones("B");
switch ($_POST['accion'])
{
	case 1:
		if ($oPlantillasMacrosZonasColumnas->Insertar($_POST,$plantmacrocolumnacod))
		{
			$msgactualizacion = "Se ha agregado la columna al macro correctamente.";
			$ret['IsSuccess'] = true;	
			$ret['plantmacrocolumnacod'] = $plantmacrocolumnacod;
			$datosmacro['plantmacrocolumnacod'] = $plantmacrocolumnacod;
			
		}
		break;
	case 2:
		$oPlantillasZonas = new cPlantillasZonas($conexion,"");
		if ($oPlantillasZonas->Modificar($_POST))
		{
			$msgactualizacion = "Se ha modificado la columna correctamente.";
			$plantmacrocolumnacod  = $_POST['plantmacrocolumnacod'];
			$macrozonacod  = $_POST['macrozonacod'];
			$ret['IsSuccess'] = true;	
			$ret['plantmacrocolumnacod'] = $plantmacrocolumnacod;
			$ret['macrozonacod'] = $macrozonacod;
			
		}
		break;
	case 3:
		if ($oPlantillasMacrosZonasColumnas->Eliminar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado la columna del macro correctamente.";
			
		}
		break;
	case 4:
		if($oPlantillasMacrosZonasColumnas->ModificarOrden($_POST))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado el orden de las columnas correctamente."; 
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

ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>