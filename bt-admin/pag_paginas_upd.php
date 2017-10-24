<? 
ob_start();
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);



$oPaginas = new cPaginas($conexion,"","");


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;
$error = false;

$datos=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);
$datos['rolcod'] = $_SESSION['rolcod'];

$texto = "";
$conexion->ManejoTransacciones("B");
switch ($_POST['accion'])
{
	case 1:
		if ($oPaginas->InsertarPagina($datos, $pagcod, $datosnuevos))
		{
			$msgactualizacion = "Se ha agregado la página correctamente.";
			$ret['IsSuccess'] = true;	
			FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_am.php",array("pagcod"=>$pagcod),$get,$md5);
			$ret['pagcod'] = $pagcod;
			$ret['md5recarga'] = $md5;
			$ret['pagestadodesc'] = utf8_encode($datosnuevos['pagestadodesc']); 
			$ret['cambioestado'] = $datosnuevos['cambioestado']; 
			$ret['pagestadocod'] = $datosnuevos['pagestadocod']; 
			
			$datosacciones['rolcod'] = $_SESSION['rolcod'];
			$datosacciones['pagestadocod'] = $datosnuevos['pagestadocod'];
			$oPaginasWorkflow = new cPaginasWorkflowRoles($conexion);
			if(!$oPaginasWorkflow->ObtenerAccionesRol($datosacciones,$resultadoacciones,$numfilasacciones))
				return false;
				
			$ret['tienepermisos'] = false; 
			if ($numfilasacciones>0)
				$ret['tienepermisos'] = true; 
		}
		break;
	case 2:
		if ($oPaginas->ModificarPagina($datos,$datosnuevos))
		{
			$msgactualizacion = "Se ha guardado correctamente a las ".date("H").":".date("i")."Hs"; 
			$ret['IsSuccess'] = true;	
			$ret['pagcod'] = $_POST['pagcod']; 
			$ret['pagestadodesc'] = utf8_encode($datosnuevos['pagestadodesc']); 
			$ret['cambioestado'] = $datosnuevos['cambioestado']; 
			$ret['pagestadocod'] = $datosnuevos['pagestadocod']; 
			
			$datosacciones['rolcod'] = $_SESSION['rolcod'];
			$datosacciones['pagestadocod'] = $datosnuevos['pagestadocod'];
			$oPaginasWorkflow = new cPaginasWorkflowRoles($conexion);
			if(!$oPaginasWorkflow->ObtenerAccionesRol($datosacciones,$resultadoacciones,$numfilasacciones))
				return false;
				
			$ret['tienepermisos'] = false; 
			if ($numfilasacciones>0)
				$ret['tienepermisos'] = true; 
		}
		break;	
	case 3:
		if ($oPaginas->EliminarPaginaCompleta($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado la página correctamente.";
		}
		break;
	case 4:
		if($oPaginas->ModificarOrden($_POST))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = "Se ha modificado la página correctamente."; 
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
echo $ret['Msg'];
ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>