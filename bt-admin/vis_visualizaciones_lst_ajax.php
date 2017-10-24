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

$oVisualizaciones = new cVisualizaciones($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="visualizaciondesc"; 
$sord ="DESC"; 

$datos = $_POST;


$datos['orderby'] = $sidx." ".$sord;

if (!$oVisualizaciones->BusquedaAvanzada ($datos,$resultado,$numfilas))
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

if (!$oVisualizaciones->BusquedaAvanzada ($datos,$resultado,$numfilas))
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
			$tipoactivacion = 5;
			$class = "desactivo";
			
			if ($fila['visualizacionestado']==ACTIVO)
			{
				$tipoactivacion = 4;
				$class = "activo";
				
			}
			//print_r($fila);
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarVisualizacion('.$fila['visualizacioncod'].')" title="Eliminar" ></a>';
			$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['visualizacioncod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		    $linkedit = '<a class="editar" href="javascript:void(0)" onclick="EditVisualizacion('.$fila['visualizacioncod'].')" title="Editar" >&nbsp;</a>';
			$datosmostrar = array(
				$fila['visualizacioncod'],
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['visualizaciondesc'],ENT_QUOTES)),
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['visualizacionnombre'],ENT_QUOTES)),
				$linkestado,
				$linkedit,
				$linkdel
			);
			$responce->rows[$i]['visualizacioncod'] = $fila['visualizacioncod'];
			$responce->rows[$i]['id'] = $fila['visualizacioncod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
		
	}
}
echo json_encode($responce);
?>
