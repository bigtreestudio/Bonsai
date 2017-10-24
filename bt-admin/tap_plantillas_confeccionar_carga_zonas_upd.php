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

$oPlantillasMacros= new cPlantillasMacros($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1'); 


$texto = "";
$ret['IsSuccess'] = false;
$ret['Msg'] = "";
switch ($_POST['accion'])
{
	case 1:
		if ($oPlantillasMacros->Insertar($_POST,$plantmacrocod))
		{
			$msgactualizacion = "Se ha agregado el macro correctamente.";
			$ret['IsSuccess'] = true;	
			$ret['plantmacrocod'] = $plantmacrocod;
			$datosmacro['plantmacrocod'] = $plantmacrocod;
			$oPlantillasProcesarHTML= new cPlantillasProcesarHTML($conexion,"");
			$oPlantillasProcesarHTML->RecargarMacro($datosmacro,$html);
			$ret['htmlgenerado'] = $html;
			
		}
		break;
	case 2:
		if ($oPlantillasMacros->Modificar($_POST))
		{
			$msgactualizacion = "Se ha modificado el macro correctamente.";
			$ret['IsSuccess'] = true;	
			$plantmacrocod = $ret['plantmacrocod'] = $_POST['plantmacrocod'];
			$datosmacro['plantmacrocod'] = $plantmacrocod;
			$oPlantillasProcesarHTML= new cPlantillasProcesarHTML($conexion,"");
			$oPlantillasProcesarHTML->RecargarMacro($datosmacro,$html);
			$ret['htmlgenerado'] = $html;
		}
		break;
	case 3:
		if ($oPlantillasMacros->Eliminar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado el macro correctamente.";
			
		}
		break;
	case 4:
		if($oPlantillasMacros->ModificarOrden($_POST))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado el orden de los macros correctamente."; 
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