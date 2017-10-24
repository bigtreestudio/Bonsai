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

$oProvincias = new cProvincias($conexion,"");

//print_r($_POST);
header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="provinciadesc"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

if (!$oProvincias->BusquedaAvanzada($datos,$resultado,$numfilas))
	die();

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

	
if (!$oProvincias->BusquedaAvanzada($datos,$resultado,$numfilas))
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
		
		if ($fila['provinciaestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
			
		
		if($oProvincias->PuedoEliminarProvincia($fila))
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarProvincia('.$fila['provinciacod'].')" title="Eliminar" ></a>';
		else
			$linkdel ="";
		
		FuncionesPHPLocal::ArmarLinkMD5("ciudades.php",array("provinciacod"=>$fila['provinciacod']),$get,$md5);
		$link = '<div>'.utf8_encode($fila['provinciadesc']).'</div><div>&nbsp;</div><div><a class="link" style="color:#000099" href="ciudades.php?provinciacod='.$fila['provinciacod'].'&md5='.$md5.'" title="Ingresar a las Ciudades de '.utf8_encode($fila['provinciadesc']).'">Ingresar a las Ciudades de '.utf8_encode($fila['provinciadesc']).'</a></div><div>&nbsp;</div>';
		$linkedit = '<a class="editar" href="javascript:void(0)" onclick="EditarProvincia('.$fila['provinciacod'].')" title="Editar" ></a>';
		$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['provinciacod'].','.$tipoactivacion.')" title="Activar / Desactivar" >&nbsp;</a>';
		
		$datosmostrar = array($fila['provinciacod'],$link,$linkestado,$linkedit,$linkdel);
		$responce->rows[$i]['provinciacod'] = $fila['provinciacod'];
		$responce->rows[$i]['id'] = $fila['provinciacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
