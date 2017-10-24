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

$oGraficosColumnas = new cGraficosColumnas($conexion,"");

//print_r($_POST);
header('Content-Type: text/html; charset=iso-8859-1'); 
$datos = $_GET;
if (!$oGraficosColumnas->BuscarxGrafico ($datos,$resultado,$numfilas))
	die();

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
		
		$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarColumna('.$fila['columnacod'].')" title="Eliminar" ></a>';
		$linkedit = '<a class="editar" href="javascript:void(0)" onclick="ModificarEjeX('.$fila['columnacod'].')" title="Editar" ></a>';
		$datosmostrar = array($fila['columnacod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['columnatitulo'],ENT_QUOTES)),$linkedit,$linkdel);
		$responce->rows[$i]['columnacod'] = $fila['columnacod'];
		$responce->rows[$i]['id'] = $fila['columnacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);
?>