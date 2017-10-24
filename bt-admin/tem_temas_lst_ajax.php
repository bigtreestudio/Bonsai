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

$oTemas = new cTemas($conexion,"");

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
	
$sidx ="temacod"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

if (!$oTemas->BuscarAvanzadaxTemaSuperior ($datos,$resultado,$numfilas))
	die();

if (isset($_POST['sord']))
	$sord = $_POST['sord']; // get the direction 
if (isset($_POST['sidx']))
	$sidx = $_POST['sidx'];
	
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

	
if (!$oTemas->BuscarAvanzadaxTemaSuperior($datos,$resultado,$numfilas))
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
		//print_r($fila);
		$tipoactivacion = 5;
		$class = "desactivo";
		
		
		if ($fila['temaestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		
		if ($oTemas->PuedeEliminarTema($fila))
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarTemas('.$fila['temacod'].')" title="Eliminar" >&nbsp;</a>';
		else
			$linkdel = "";	
		
		FuncionesPHPLocal::ArmarLinkMD5("tem_temas.php",array("temacod"=>$fila['temacod']),$get,$md5);
	
		$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['temacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		//$link = '<div>'.utf8_encode($fila['tematitulo']).'</div><div>&nbsp;</div><div><a class="link" href="tem_temas.php?temacodsuperior='.$fila['temacod'].'&md5='.$md5.'" title="Ingresar a temas de '.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tematitulo'],ENT_QUOTES)).'">Ingresar a temas de '.utf8_encode($fila['tematitulo']).'</a></div><div>&nbsp;</div>';
		$link = utf8_encode($fila['tematitulo']);
		$linkedit = '<a class="editar" href="javascript:void(0)" onclick="EditarTemas('.$fila['temacod'].',\''.$fila['temacodsuperior'].'\')" title="Editar" >&nbsp;</a>';
		$linkcolor = '<span style=" height:20px; width:87px; display:block; background-color:'.$fila['temacolor'].';">&nbsp;</span>';

		$datosmostrar = array($fila['temacod'],$link,$linkcolor,$linkestado,$linkedit,$linkdel);
		$responce->rows[$i]['temacod'] = $fila['temacod'];
		$responce->rows[$i]['id'] = $fila['temacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
