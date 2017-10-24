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

$oPlantillas=new cPlantillasHtml($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;
$ret['md5recarga'] = "";
$ret['md5upd'] = "";

$error = false;

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";

$datos = $_POST;

switch ($datos['accion'])
{
	case 1:
		if ($oPlantillas->Insertar($datos,$planthtmlcod))
		{
			$msgactualizacion = "Se ha agregado la plantilla html correctamente.";
			$ret['IsSuccess'] = true;	
			FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_am.php",array("planthtmlcod"=>$planthtmlcod),$get,$md5);
			FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_upd.php",array("planthtmlcod"=>$planthtmlcod),$getupd,$md5upd);
			$ret['planthtmlcod'] = $planthtmlcod;
			$ret['md5recarga'] = $md5;
			$ret['md5upd'] = $md5upd;
		}
		break;
	case 2:
		if ($oPlantillas->Modificar($datos,false))
		{
			
			$planthtmlcod = $_POST['planthtmlcod'];
			$msgactualizacion = "Se ha modificado la plantilla html correctamente.";
			$ret['IsSuccess'] = true;	
			FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_am.php",array("planthtmlcod"=>$planthtmlcod),$get,$md5);
			$ret['planthtmlcod'] = $planthtmlcod;
			$ret['md5recarga'] = $md5;
		}
		break;
	case 3:
		if ($oPlantillas->Eliminar($datos))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado la plantilla html correctamente.";
			$texto="";
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