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

$oRevistaTapas= new cRevistaTapas($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="revtapafecha"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;
if (!$oRevistaTapas->BusquedaAvanzada($datos,$resultado,$numfilas))
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


if (!$oRevistaTapas->BusquedaAvanzada($datos,$resultado,$numfilas))
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


		if ($fila['revtapaestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		

		FuncionesPHPLocal::ArmarLinkMD5("rev_tapas_am.php",array("revtapacod"=>$fila['revtapacod']),$getrevtapa,$md5revtapa);
	
		$revtapadel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarRevTapa('.$fila['revtapacod'].')" title="Eliminar" >&nbsp;</a>';
		$revtapaestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['revtapacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$revtapaedit = '<a class="editar" href="rev_tapas_am.php?'.$getrevtapa.'" title="Editar" >&nbsp;</a>';
		
		$datosmostrar = array($fila['revtapacod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['revtapatitulo'],ENT_QUOTES)), utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['revtapatiponombre'],ENT_QUOTES)),  $fila['revtapanumero'],date("d/m/Y",strtotime($fila['revtapafecha'])), $revtapaestado,$revtapaedit,$revtapadel);
		
	
		$responce->rows[$i]['revtapacod'] = $fila['revtapacod'];
		$responce->rows[$i]['id'] = $fila['revtapacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
