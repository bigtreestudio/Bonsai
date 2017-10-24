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

$oNoticias=new cNoticias($conexion,"");



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
	
$sidx ="n.noticiafalta"; 
$sord ="DESC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['estadonoticiacopiacod'] = 0;
$datos['estadonoticiacopiacodorig'] = 1;

if (!$oNoticias->BusquedaAvanzada ($datos,$resultado,$numfilas))
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

	
if (!$oNoticias->BusquedaAvanzada ($datos,$resultado,$numfilas))
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
		$link = '<a class="aceptar" href="javascript:void(0)" onclick="InsertarNoticiaRelacionada(\''.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)).'\','.$fila['noticiacod'].')" title="Seleccionar Noticia" >Seleccionar</a>';
		$linktitulo = '<div style="font-weight:bold;">'.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiavolanta'],ENT_QUOTES)).'</div>';
		$linktitulo .= '<div style="white-space: normal !important;">'.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)).'</div>';
		$fechaAlta=substr($fila['noticiafecha'],0,10);
        $fechaAlta=FuncionesPHPLocal::ConvertirFecha($fechaAlta,'aaaa-mm-dd','dd/mm/aaaa');
		
		$datosmostrar = array(
		$fila['noticiacod'],
		$linktitulo, 
		utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)),
		$fechaAlta, 
		$link);
		
		
		$responce->rows[$i]['noticiacod'] = $fila['noticiacod'];
		$responce->rows[$i]['id'] = $fila['noticiacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
