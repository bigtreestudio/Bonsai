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

$oLinks = new cLinks($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;

$conexion->ManejoTransacciones("B");
$error = false;


$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";

$datos['datoscontenido']= $_POST;
//$datos['datoscontenido']['post_foto']=$_FILES['foto'];

switch ($_POST['accion'])
{
	case 1:
		if ($oLinks->InsertarLink($datos['datoscontenido'],$linkcod))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha agregado el link correctamente.";
			$ret['linkcod'] = $linkcod;	
		}
		break;
	case 2:
		if ($oLinks->ModificarLink($datos['datoscontenido']))
		{
			
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado el link correctamente.";
			
		}
		break;
	case 3:
		if ($oLinks->EliminarLink($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado el link correctamente.";
			
		}
		break;
		
	case 4:
		if ($oLinks->DesActivarLink($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha desactivado el link correctamente.";
			
			
		}
		break;

	case 5:
		if ($oLinks->ActivarLink($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha activado el link correctamente.";
			
		}
		break;
	
	case 6:
		
		if($oLinks->ModificarOrden($_POST))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado el orden del link correctamente."; 
			
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