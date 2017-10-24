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

$oFormulariosEnvios= new cFormulariosEnvios($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 
$page = 1; 

$sidx ="enviocod"; 
$sord ="ASC"; 
$datos = $_POST;
$datos['orderby'] = $sidx." ".$sord;
if (!$oFormulariosEnvios->BusquedaAvanzada($datos,$resultado,$numfilas))
	die();


$responce =new StdClass; 
$responce->page = 1;
$responce->total = 1; 
$responce->records = $numfilas; 
$responce->rows = array();

$i=0;
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		//FuncionesPHPLocal::ArmarLinkMD5("tap_tapas_confeccionar.php",array("tapacod"=>$fila['tapacod']),$getconf,$md5conf);
		//FuncionesPHPLocal::ArmarLinkMD5("tap_tapas.php",array("tapacod"=>$fila['tapacod']),$get,$md5);
		
		$opcionndel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarEmail('.$fila['enviocod'].')" title="Eliminar" >&nbsp;</a>';
		$datosmostrar = array($fila['enviocod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['enviomail'],ENT_QUOTES)),utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['enviotipodesc'],ENT_QUOTES)),$opcionndel);
		
	
		$responce->rows[$i]['enviocod'] = $fila['enviocod'];
		$responce->rows[$i]['id'] = $fila['enviocod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
