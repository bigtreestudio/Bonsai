<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oMultimedia = new cMultimedia($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="multimediacod"; 
$sord ="DESC"; 

$datos = $_POST;
$datos['multimediaestadocod']=ACTIVO;

if (isset($_GET['multimediacatcod']) && $_GET['multimediacatcod']!="")
	$datos['multimediacatcod'] = $_GET['multimediacatcod'];	
if (isset($_GET['multimediaconjuntocod']) && $_GET['multimediaconjuntocod']!="")
	$datos['multimediaconjuntocod'] = $_GET['multimediaconjuntocod'];	


$datos['orderby'] = $sidx." ".$sord;

if (!$oMultimedia->BusquedaAvanzada ($datos,$resultado,$numfilas))
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

	
if (!$oMultimedia->BusquedaAvanzada($datos,$resultado,$numfilas))
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
		$imagen = '<div style="max-width:60px; max-height:60px; padding-left:25px"  id="imagenseleccionada_'.$fila['multimediacod'].'"><img src="'.$oMultimedia->DevolverDireccionImg($fila).'" style="max-width:100%; max-height:100%;" alt="Imagen" />';
		$imagen .= '<input type="hidden" name="srcurl_'.$fila['multimediacod'].'" id="srcurl_'.$fila['multimediacod'].'"  value="'.$oMultimedia->DevolverDireccionImgTamanio($fila['multimediacatcarpeta'],"S/",$fila['multimediaubic']).'"></div>';

		switch($fila['multimediaconjuntocod'])
		{
			case FOTOS:
				$js = "SeleccionarImagenMultimediaEditor";
				break;	
		}

		$linkseleccionar='<a href="javascript:void(0)" style="font-weight:bold" onclick="'.$js.'('.$fila['multimediacod'].')">Seleccionar</a>';
		$titulo = utf8_encode(trim( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES)));
		$datosmostrar = array($fila['multimediacod'],$imagen,$titulo,$linkseleccionar);
		$responce->rows[$i]['multimediacod'] = $fila['multimediacod'];
		$responce->rows[$i]['id'] = $fila['multimediacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);
?>