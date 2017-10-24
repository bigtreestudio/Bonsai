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

$oEncuestas= new cEncuestas($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="encuestacod"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;


if (!$oEncuestas->BusquedaAvanzada($datos,$resultado,$numfilas))
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


if (!$oEncuestas->BusquedaAvanzada($datos,$resultado,$numfilas))
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
		$classmul = "";
		if ($fila['encuestaestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
		}
		
		$encuestaopciones = '<a class="opciones" href="javascript:void(0)" onclick="EncuestaOpciones('.$fila['encuestacod'].')" title="Opciones" >&nbsp;</a>';	
		$encuestadel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarEncuesta('.$fila['encuestacod'].')" title="Eliminar" >&nbsp;</a>';
		$encuestaestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['encuestacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$encuestaedit = '<a class="editar" href="javascript:void(0)" onclick="EditarEncuestas('.$fila['encuestacod'].')" title="Editar" >&nbsp;</a>';		
	
		FuncionesPHPLocal::ArmarLinkMD5("enc_encuestas_respuestas.php",array("encuestacod"=>$fila['encuestacod']),$getrespuesta,$md5);
		$encuestaresp = '<a class="respuestas" href="enc_encuestas_respuestas.php?'.$getrespuesta.'" title="Respuestas" >&nbsp;</a>';		
		
		$datosmostrar = array($fila['encuestacod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['encuestapregunta'],ENT_QUOTES)),$encuestaresp,$encuestaopciones,$encuestaestado,$encuestaedit,$encuestadel);
		
	
		$responce->rows[$i]['encuestacod'] = $fila['encuestacod'];
		$responce->rows[$i]['id'] = $fila['encuestacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
