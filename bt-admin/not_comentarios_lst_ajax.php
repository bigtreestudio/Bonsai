<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oObjeto = new cNoticiasComentarios($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1');
if (isset ($_POST['page']))
	$page = $_POST['page'];
else
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows'];
else
	$limit = 1;

$sidx = "comentariocod";
$sord = "DESC";

$datos = $_POST;

if(!$oObjeto->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();
if (isset ($_POST['sord']))
	$sord = $_POST['sord'];
if (isset ($_POST['sidx']))
	$sidx = $_POST['sidx'];
$count = $numfilas;
$count = $numfilas;
if( $count >0 )
	$total_pages = ceil($count/$limit); 
else
	$total_pages = 0; 

if( $page > $total_pages )
	$page = $total_pages;

if( $limit<0 )
	$limit = 0;

$start = $limit*$page - $limit;if( $start<0 )
	$start = 0;

$datos['orderby'] = $sidx." ".$sord;
$datos['limit'] = "LIMIT ".$start." , ".$limit;

if(!$oObjeto->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

$i = 0;
$responce =new StdClass; 
$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count;
$responce->rows = array();

while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$tipoactivacion = 5;
	$class = "desactivo";
	if ($fila['comentarioestado']==ACTIVO)
	{
		$tipoactivacion = 4;
		$class = "activo";
	}
	$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['comentariocod'].','.$tipoactivacion.')" title="Activar / Desactivar" >&nbsp;</a>';

	$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="Eliminar('.$fila['comentariocod'].')" title="Eliminar" >&nbsp;</a>';
	$linkedit = '<a class="editar" href="not_comentarios_am.php?comentariocod='.$fila["comentariocod"].'" title="Editar Banner" id="editar_'.$fila['comentariocod'].'">&nbsp;</a>';
	$falta = FuncionesPHPLocal::ConvertirFecha($fila['comentariofalta'],"aaaa-mm-dd","dd/mm/aaaa");

	$datosmostrar = array(
		$fila['comentariocod'],
		utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['comentarionombre'],ENT_QUOTES)),
		utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['comentarioemail'],ENT_QUOTES)),
		utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)),
		$falta,
		$linkestado,
		$linkedit,
		$linkdel
	);
	$responce->rows[$i]['comentariocod'] = $fila['comentariocod'];
	$responce->rows[$i]['id'] = $fila['comentariocod'];
	$responce->rows[$i]['cell'] = $datosmostrar;
	$i++;
}

echo json_encode($responce);
?>