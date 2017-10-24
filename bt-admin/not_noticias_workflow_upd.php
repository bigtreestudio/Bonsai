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

$oNoticiasWorkflow = new cNoticiasWorkflow($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;
/*$ret['md5recarga'] = "";
$ret['md5upd'] = "";*/
$conexion->ManejoTransacciones("B");
$error = false;

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";

switch ($_POST['accion'])
{
	case 1:
		if ($oNoticiasWorkflow->Insertar($_POST,$noticiaworkflowcod))
		{
			$msgactualizacion = "Se ha actualizado el workflow correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	case 2:
		if ($oNoticiasWorkflow->Modificar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha modificado el workflow correctamente.";
			
		}
		break;	
	case 3:
		if ($oNoticiasWorkflow->Eliminar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado el workflow correctamente.";
			
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

	
$ret['Msg'] = utf8_encode(ob_get_contents());

ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>