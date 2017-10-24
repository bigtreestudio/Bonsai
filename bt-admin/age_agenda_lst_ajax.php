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

$oAgenda = new cAgenda($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="agendafdesde ASC, horainicio ASC"; 
$sord =""; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;
if (!$oAgenda->BuscarAgendaBusquedaAvanzanda($datos,$resultado,$numfilas))
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


if (!$oAgenda->BuscarAgendaBusquedaAvanzanda($datos,$resultado,$numfilas))
		die();

$i = 0;
$responce =new StdClass;
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
		$classmul = "";


		if ($fila['agendaestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		
		FuncionesPHPLocal::ArmarLinkMD5("age_agenda_alta.php",array("agendacod"=>$fila['agendacod']),$getagenda,$md5agenda);
	
		$fila['agendafdesde'] = FuncionesPHPLocal::ConvertirFecha($fila['agendafdesde'],"aaaa-mm-dd","dd/mm/aaaa");
		$fila['agendafhasta'] = FuncionesPHPLocal::ConvertirFecha($fila['agendafhasta'],"aaaa-mm-dd","dd/mm/aaaa");

		$agendadel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarEvento('.$fila['agendacod'].')" title="Eliminar" >&nbsp;</a>';
		$agendaestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['agendacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$agendaedit = '<a class="editar" href="age_agenda_alta.php?'.$getagenda.'" title="Editar" >&nbsp;</a>';


		$datosmostrar = array($fila['agendacod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['agendatitulo'],ENT_QUOTES)),utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['agendafdesde']." - ".$fila['agendafhasta'],ENT_QUOTES)),utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['horainicio']." - ".$fila['horafin'],ENT_QUOTES)),$agendaestado,$agendaedit,$agendadel);
		
	
		$responce->rows[$i]['agendacod'] = $fila['agendacod'];
		$responce->rows[$i]['id'] = $fila['agendacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
