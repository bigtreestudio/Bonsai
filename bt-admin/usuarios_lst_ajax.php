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

$oUsuario=new cUsuarios($conexion);
$oRol = new cRoles($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="usuariocod"; 
$sord ="ASC"; 

$datos = $_POST;

	
if (!$oRol->TraerRolesActualizar($_SESSION,$resultado,$numfilas))
	return false;
$arregloroles = array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	$arregloroles[] = $fila['rolcodactualizado'];
	
$rolebuscar = implode(",",$arregloroles);


$datos['orderby'] = $sidx." ".$sord;
if(isset($_POST['rolcod']) && $_POST['rolcod']!="" )
	$datos['rolcod'] = $_POST['rolcod'];
else	
	$datos['rolcod'] = $rolebuscar;
if (!$oUsuario->BusquedaUsuariosEdicion ($datos,$resultado,$numfilas))
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

	
if (!$oUsuario->BusquedaUsuariosEdicion ($datos,$resultado,$numfilas))
	die();


$i = 0;

$responce =new StdClass; 
$responce->page = $page;
$responce->total = $total_pages; 
$responce->records = $count; 
$responce->rows = array();

$oUsuariosModulosAcciones = new cUsuariosModulosAcciones($conexion,"");
if ($numfilas>0)
{
	$agregaracciones = $oUsuariosModulosAcciones->TienePermisosAccion("000110");
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		FuncionesPHPLocal::ArmarLinkMD5("usuarios_modificar_datos.php",array("usuariocod"=>$fila['usuariocod']),$get,$md5);
		FuncionesPHPLocal::ArmarLinkMD5("usuarios_cargar_acciones.php",array("usuariocod"=>$fila['usuariocod']),$getacciones,$md5acciones);
		$link="";
		if ($fila['usuariocod']!=$_SESSION['usuariocod'] || $_SESSION['rolcod']==ADMISITE)
			$link = '<a class="btn btn-primary btn-xs" style="color:#ffffff; font-size:12px; padding:1px 5px;" href="usuarios_modificar_datos.php?'.$get.'" title="Editar Usuario" id="editar_'.$fila['usuariocod'].'"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;Editar</a>';
		if ($agregaracciones && $fila['usuariocod']!=$_SESSION['usuariocod'] || $_SESSION['rolcod']==ADMISITE)
			$link .= '&nbsp;<a class="btn btn-success btn-sm" style="color:#ffffff; font-size:12px; padding:1px 5px;" href="usuarios_cargar_acciones.php?'.$getacciones.'" title="Cargar Acciones" id="acciones_'.$fila['usuariocod'].'"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Acciones</a>';
		$estado = $oUsuario->ObtenerEstadoUsuario($fila['usuarioestado'],true);
		$datosmostrar = array(
			$fila['usuariocod'],
			utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['usuarioapellido']." ".$fila['usuarionombre'],ENT_QUOTES)),
			$fila['usuarioemail'],
			$estado,
			$fila['usuariotel'],
			$link
		);
		$responce->rows[$i]['usuariocod'] = $fila['usuariocod'];
		$responce->rows[$i]['id'] = $fila['usuariocod'];
		$responce->rows[$i]['cell'] = $datosmostrar;
		$i++;
	}
}

echo json_encode($responce);

?>
