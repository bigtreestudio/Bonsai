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

$oPlantillas = new cPlantillas($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="plantcod"; 
$sord ="ASC"; 

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
			
			$linkdel ="";
			$datos['plantcod']= $fila['plantcod'];
			
			$linkedit = '';
			FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_confeccionar.php",array("plantcod"=>$fila['plantcod']),$getmacrocol,$md5macrocol);
			$linkcol ='<a class="link" target="_blank" href="tap_plantillas_confeccionar.php?plantcod='.$fila['plantcod'].'&md5='.$md5macrocol.'" title="Confeccionar" >Macros</a>';

			$linkArea ='<a class="link" target="_blank" href="javascript:void(0)" onclick="return AreasPlantilla('.$fila['plantcod'].')" title="Confeccionar" >Areas</a>';

			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarPlantilla('.$fila['plantcod'].')" title="Eliminar" ></a>';
			$linkedit = '<a class="editar" href="javascript:void(0)" onclick="EditarPlantilla('.$fila['plantcod'].')" title="Editar" >&nbsp;</a>';

			$datosmostrar = array(
				$fila['plantcod'],
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['plantdesc'],ENT_QUOTES)),
				$linkArea,
				$linkcol,
				$linkedit,
				$linkdel
			);
			$responce->rows[$i]['plantcod'] = $fila['plantcod'];
			$responce->rows[$i]['id'] = $fila['plantcod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
		
	}
}
echo json_encode($responce);
?>
