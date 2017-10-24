<?php 
require("./config/include.php");

$conexion->SeleccionBD(BASEDATOS);

$conexion->SetearAdmiGeneral(ADMISITE);

$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo->VerificarBloqueo($conexion);

header('Content-Type: text/html; charset=iso-8859-1');
if (isset ($_POST['page']))
	$page = $_POST['page'];
else
	$page = 1; 

	$limit = $_POST['rows'];
else
	$limit = 1;

$sord = "DESC";



	die();

	$sord = $_POST['sord'];
if (isset ($_POST['sidx']))
	$sidx = $_POST['sidx'];
$count = $numfilas;
$count = $numfilas;
if( $count >0 )
	$total_pages = ceil($count/$limit); 
else
	$total_pages = 0; 

	$page = $total_pages;

	$limit = 0;

	$start = 0;

$datos['limit'] = "LIMIT ".$start." , ".$limit;

	die();

	$responce =new StdClass; 
	$responce->page = $page; 
	$responce->total = $total_pages; 
	$responce->records = $count;
	$responce->rows = array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$linkedit = '<a class="editar" href="plan_proyectos_am.php?planproyectocod='.$fila["planproyectocod"].'" title="Editar" id="editar_'.$fila['planproyectocod'].'">&nbsp;</a>';
	$tipoactivacion = 5;
	$class = "desactivo";
	if ($fila['planproyectoestado']==ACTIVO)
	{
		$tipoactivacion = 4;
		$class = "activo";
	}
	$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['planproyectocod'].','.$tipoactivacion.')" title="Activar / Desactivar" >&nbsp;</a>';


		$fila['planproyectocod'],
		utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planproyectonombre'],ENT_QUOTES)),
		utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planobjetivocoddesc'],ENT_QUOTES)),
		utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planjurisdiccioncoddesc'],ENT_QUOTES)),
		utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planproyectoestadocoddesc'],ENT_QUOTES)),
		$linkestado,
		$linkedit,
		$linkdel
	);
	$responce->rows[$i]['planproyectocod'] = $fila['planproyectocod'];
	$responce->rows[$i]['id'] = $fila['planproyectocod'];
	$responce->rows[$i]['cell'] = $datosmostrar;
	$i++;
}

?>