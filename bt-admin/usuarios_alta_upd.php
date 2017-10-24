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
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$usuarios=new cUsuarios($conexion);


$_SESSION['datosusuario'] = $_POST;
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

	$conexion->ManejoTransacciones("B");
	$result=true;
	
	
	if(!$usuarios->AltaUsuarioInterno($_POST,$codigoinsertado))
		$result=false;
		
	if($result)
	{
		$usuariocod = $codigoinsertado;
		FuncionesPHPLocal::ArmarLinkMD5("usuarios_modificar_datos.php",array("usuariocod"=>$usuariocod),$get_post,$md5_post);
		$conexion->ManejoTransacciones("C");
		$_SESSION['msgactualizacion'] = "El usuario ha sido dado de alta.";
		$_SESSION['datosusuario'] = array();
		ob_end_clean();
		header("Location:usuarios_modificar_datos.php?usuariocod=".$usuariocod."&md5=".$md5_post);
	}
	else
	{
		$conexion->ManejoTransacciones("R");
?>		
		<br /><br /><br /><div align="center"><a href="usuarios_alta.php" class="linkfondoblanco">Volver</a></div>
<?php 
	}

$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>

