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

$oPlantillasAreas = new cPlantillasAreas($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 
$sidx ="areaorden"; 
$sord ="ASC"; 

$datos = $_POST;
if (!$oPlantillasAreas->TraerxPlantilla ($datos,$resultado,$numfilas))
	die();


$count = $numfilas; 
$total_pages = $numfilas; 

$i = 0;

$responce =new StdClass; 
$responce->page = 1;
$responce->total = 1; 
$responce->records = $numfilas; 
$responce->rows = array();


if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarArea('.$fila['plantcod'].','.$fila['areacod'].')" title="Eliminar" >&nbsp;</a>';
		$datosmostrar = array(
			$fila['areacod'],
			utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['areahtmldesc'],ENT_QUOTES)),
			$linkdel
		);
		$responce->rows[$i]['id'] = $fila['areacod'];
		$responce->rows[$i]['cell'] = $datosmostrar;
		$i++;
	}
}
echo json_encode($responce);
?>
