<?php  
ini_set("memory_limit", "128M");
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

$oTapas= new cTapas($conexion);
header('Content-Type: text/html; charset=iso-8859-1'); 
$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;

$oUsuariosModulosAcciones = new cUsuariosModulosAcciones($conexion);
$puedePublicar = $oUsuariosModulosAcciones->TienePermisosAccion("000611");
if ($puedePublicar)
{	
	//----------------------------------------------------------------------------------------- 	
	// Inicio de pantalla
	$datos = $_POST;
	if(!$oTapas->BuscarxCodigo($datos,$resultado,$numfilas))
		return false;
	
	$datostapa = $conexion->ObtenerSiguienteRegistro($resultado);
	
	$html_generado ="";
	$oProcesarHTML = new cTapasProcesarHTML($conexion);
	$oTapasTipos = new cTapasTipos($conexion);
	$oProcesarHTML->SetearPublicar();
	if($oProcesarHTML->Procesar($datostapa,$html_generado,$arreglozonas))
	{
		
	
		if(!$oTapasTipos->ModificarTapaPublicada($datostapa))
		{
			$msgactualizacion = "No se ha podido actualizar la Portada.";
			$ret['IsSuccess'] = false;
			
		}
	
		if(FuncionesPHPLocal::GuardarArchivo(PUBLICA,$html_generado,$datostapa['tapatipoarchivo']))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
		
	}

}else{
	echo "No tiene permisos para publicar";
	$ret['IsSuccess'] = false;
}
 
$ret['Msg'] = ob_get_contents();
ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>