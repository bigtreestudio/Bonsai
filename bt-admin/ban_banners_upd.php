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

$oBanners = new cBanners($conexion,"");


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
		if ($oBanners->Insertar($datos,$bannercod,false))
		{
			$msgactualizacion = "Se ha agregado el banner correctamente.";
			$ret['IsSuccess'] = true;	
			FuncionesPHPLocal::ArmarLinkMD5("ban_banners_am.php",array("bannercod"=>$bannercod),$get,$md5);
			FuncionesPHPLocal::ArmarLinkMD5("ban_banners_upd.php",array("bannercod"=>$bannercod),$getupd,$md5upd);
			$ret['bannercod'] = $bannercod;
			$ret['md5recarga'] = $md5;
			$ret['md5upd'] = $md5upd;
		}
		break;
	case 2:
		if ($oBanners->Modificar($datos,false))
		{
			
			$bannercod = $_POST['bannercod'];
			$msgactualizacion = "Se ha modificado el banner correctamente.";
			$ret['IsSuccess'] = true;	
			FuncionesPHPLocal::ArmarLinkMD5("ban_banner_am.php",array("bannercod"=>$bannercod),$get,$md5);
			$ret['bannercod'] = $bannercod;
			$ret['md5recarga'] = $md5;
		}
		break;
	case 3:
		if ($oBanners->Eliminar($datos))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado el banner correctamente.";
			$texto="";
		}
		break;
	case 4:
		if ($oBanners->ActivarDesactivar($datos))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha activado el estado del banner correctamente.";
			$texto="";
		}
		break;
	case 5:
		if ($oBanners->ActivarDesactivar($datos))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha desactivado el estado del banner correctamente.";
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