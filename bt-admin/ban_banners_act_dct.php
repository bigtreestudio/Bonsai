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

//$usuarios=new cUsuarios($conexion);

$msg="";
$ejecutobien = 0;
$ok = 1;

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array(),$get,$md5);
if($_POST["md5"]!=$md5)
{
	$ok = 0;
	$msg = "Error, no tiene permisos.";
}


$oAccion = new cBanners($conexion,"");
echo "{\n";
echo "error:'";
if (!isset($_POST['tipo']) || $_POST['tipo']=="")
{	
	$msg = "Error al enviar el tipo de acción a realizar.";
	$ok = 0;
}
if ($ok)
{
	$conexion->ManejoTransacciones("B");
	switch($_POST['tipo'])
	{
		case 1:
			if ($oAccion->Activar($_POST))
			{
				$msg = "Se ha activado el banner correctamente.";
				$ejecutobien = 1;
			}
			break;
		case 2:
			if ($oAccion->DesActivar($_POST))
			{
				$msg = "Se ha desactivado el banner correctamente.";
				$ejecutobien = 1;
			}
		
		break;
		case 3:
			if ($oAccion->ActivarSeleccionados($_POST))
			{
				$msg = "Se han activado los banners correctamente.";
				$ejecutobien = 1;
			}
		
		break;
		case 4:
			if ($oAccion->DesActivarSeleccionados($_POST))
			{
				$msg = "Se han desactivado los banners correctamente.";
				$ejecutobien = 1;
			}
		break;
		case 5:
			if ($oAccion->Eliminar($_POST))
			{
				$msg = "Se ha eliminado el banner correctamente.";
				$ejecutobien = 1;
			}
		break;
		case 6:
			if ($oAccion->EliminarSeleccionados($_POST))
			{
				$msg = "Se han eliminado los banners correctamente.";
				$ejecutobien = 1;
			}
		break;
	}
	if ($ejecutobien)
		$conexion->ManejoTransacciones("C");
	else
		$conexion->ManejoTransacciones("R");	
}

echo "',\n";
echo				"msg: '" . $msg . "',\n";
echo				"ok: '" . $ejecutobien . "'\n";
echo "}";
?>
