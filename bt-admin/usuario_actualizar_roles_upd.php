<?php  
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

$oUsuario=new cUsuarios($conexion);
$oRol = new cRoles($conexion);

?>
<br />
<div align="center" class="textonombreclave" id="AltaModifLabel">
	  Actualizar Roles de Usuario
</div>
<br />
<?php 
//----------------------------------------------------------------------------------------- 
function Validar($conexion,&$datosvalidados) {	

	$datosvalidados=array();
	if ( !($_POST['operacion'] == "I" || $_POST['operacion'] == "B" || $_POST['operacion'] == "M") ) {
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la operación. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}

	if ($_POST['usuarioCUIT']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['usuarioCUIT'],"CUIT"))	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en CUIT/CUIL/CDI. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosvalidados["usuariocuit"]=$_POST["usuarioCUIT"];

	// Se valida antes de actualizar.
	$datosvalidados["rolcod"]=$_POST["rolcod"];

	return true;
}
//----------------------------------------------------------------------------------------- 
$result=true;

if(!Validar($conexion,$datosvalidados)) { 	
?> 
	<br /><br /><div align="center"><a href="usuario_actualizar_roles.php?usuariocod=<?php  echo $_POST['usuariocod']; ?>" class="linkFondoBlanco"> Volver </a></div>
<?php 
} else {
	switch ($_POST["operacion"]) {		

	case "I" :
		$ArregloDatos['usuariocod']=$_POST['usuariocod'];	
		if(!$oUsuario->BuscarUsuarios($ArregloDatos,$numfilasusuarios,$resultadousuarios,$error,$errormail) || $numfilasusuarios!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error seleccionando usuarios. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			die();
		}
		$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
	
		if(!$oRol->TienePermisoAsignarRol($_SESSION["rolcod"],$datosvalidados["rolcod"])) {
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error de permisos de acceso al rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		} else {
			$conexion->ManejoTransacciones("B");
			if($oRol->AsignarRol($_SESSION["rolcod"],$filausuario['usuariocod'],$datosvalidados,$error,$errormail)) {
				FuncionesPHPLocal::RegistrarAcceso($conexion,"034101","usuariocod=".$filausuario['usuariocod']." - rolcod=".$datosvalidados["rolcod"],$_SESSION['usuariocod']);
				$conexion->ManejoTransacciones("C");
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se agregó el rol al usuario",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			} else {
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO),$errormail);
				$conexion->ManejoTransacciones("R");
			}
		}
		break;

	case "B" :

		$ArregloDatos['usuariocod']=$_POST['usuariocod'];
		if(!$oUsuario->BuscarUsuarios($ArregloDatos,$numfilasusuarios,$resultadousuarios,$error,$errormail) || $numfilasusuarios!=1)	{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error seleccionando usuarios. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			die();
		}
		$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
	
		if(!$oRol->TienePermisoDesasignarRol($_SESSION["rolcod"],$datosvalidados["rolcod"])) {
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error de permisos de acceso al rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		} else {
			$conexion->ManejoTransacciones("B");
			if($oRol->DesasignarRol($_SESSION["rolcod"],$filausuario['usuariocod'],$datosvalidados["rolcod"],$error,$errormail))	{
				FuncionesPHPLocal::RegistrarAcceso($conexion,"034102","usuariocod=".$filausuario['usuariocod']." - rolcod=".$datosvalidados["rolcod"],$_SESSION['usuariocod']);
				$conexion->ManejoTransacciones("C");
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado el rol del usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			} else {
				$conexion->ManejoTransacciones("R");		
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO),$errormail);
			}	
		}	
		break;

	case "M" :

		$ArregloDatos['usuariocod']=$_POST['usuariocod'];
		if(!$oUsuario->BuscarUsuarios($ArregloDatos,$numfilasusuarios,$resultadousuarios,$error,$errormail) || $numfilasusuarios!=1)	{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error seleccionando usuarios. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			die();
		}
		$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
	
		if(!$oRol->TienePermisoModificarAsignacionRol($_SESSION["rolcod"],$datosvalidados["rolcod"])) {
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error de permisos de acceso al rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		} else {
			$conexion->ManejoTransacciones("B");
			if($oRol->ModificarAsignacionRol($_SESSION["rolcod"],$filausuario['usuariocod'],$datosvalidados["rolcod"],$datosvalidados,$error,$errormail))	{
				FuncionesPHPLocal::RegistrarAcceso($conexion,"034103","usuariocod=".$filausuario['usuariocod']." - rolcod=".$datosvalidados["rolcod"],$_SESSION['usuariocod']);
				$conexion->ManejoTransacciones("C");
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha modificado el rol del usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			} else 
				$conexion->ManejoTransacciones("R");		
		}			
		break;
	default:
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"No esta definida la operación.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	}
?>	
   <br /><br /><div align="center"><a href="usuario_actualizar_roles.php?usuariocod=<?php  echo $_POST['usuariocod']; ?>" class="linkFondoBlanco"> Volver </a></div>
<?php 
}  // Se valido ok
$oEncabezados->PieMenuEmergente(); 
?>