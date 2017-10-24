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

$oPlantillas=new cPlantillasHtml($conexion);



header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="planthtmlcod"; 
$sord ="DESC"; 

$datos = $_POST;


$datos['orderby'] = $sidx." ".$sord;

if (!$oPlantillas->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

if (isset($_POST['sord']))
	$sord = $_POST['sord']; // get the direction 
if (isset($_POST['sidx']))
	$sidx = $_POST['sidx']; // get index row - i.e. user click to sort 


$count = $numfilas; 
if( $count >0 ) 
{ 
	$total_pages = ceil($count/$limit); 
} 
else 
{ $total_pages = 0; } 

if ($page > $total_pages) 
	$page=$total_pages; 
	
if ($limit<0) 
	$limit = 0; 

$start = $limit*$page - $limit; // do not put $limit*($page - 1) 

if ($start<0) 
	$start = 0;


$datos['orderby'] = $sidx." ".$sord;
$datos['limit'] = "LIMIT ".$start." , ".$limit;

if (!$oPlantillas->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();


$i = 0;

$responce =new StdClass; 
$responce->page = $page;
$responce->total = $total_pages; 
$responce->records = $count; 
$responce->rows = array();


if ($numfilas>0)
{
	
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
	
		FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_am.php",array("planthtmlcod"=>$fila['planthtmlcod']),$get,$md5);
		$linkedit = '<a class="editar" href="tap_plantillas_html_am.php?'.$get.'" title="Editar Plantilla" id="editar_'.$fila['planthtmlcod'].'">&nbsp;</a>';

		FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_editar.php",array("planthtmlcod"=>$fila['planthtmlcod']),$get,$md5);
		$linkeditarchivos = '<a class="archivos" href="tap_plantillas_html_editar.php?'.$get.'" title="Editar Archivos" id="editar_'.$fila['planthtmlcod'].'">&nbsp;</a>';
		
		$planthtmldefault = "No";
		if ($fila['planthtmldefault']==1)
			$planthtmldefault = "Si";
		
		$datosmostrar = array(
			$fila['planthtmlcod'],
			utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planthtmldesc'],ENT_QUOTES)),
			$planthtmldefault,
			$linkedit,
			$linkeditarchivos
			
		);
		$responce->rows[$i]['planthtmlcod'] = $fila['planthtmlcod'];
		$responce->rows[$i]['id'] = $fila['planthtmlcod'];
		$responce->rows[$i]['cell'] = $datosmostrar;
		$i++;
	}
}
echo json_encode($responce);
?>
