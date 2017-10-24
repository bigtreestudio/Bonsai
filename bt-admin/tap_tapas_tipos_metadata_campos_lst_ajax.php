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

$oTapasTiposMetadataCampos = new cTapasTiposMetadataCampos($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="tapatipometadatacampo"; 
$sord ="ASC"; 

$datos = $_POST;


$datos['orderby'] = $sidx." ".$sord;

if (!$oTapasTiposMetadataCampos->BusquedaAvanzada ($datos,$resultado,$numfilas))
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

if (!$oTapasTiposMetadataCampos->BusquedaAvanzada ($datos,$resultado,$numfilas))
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
			
			if ($fila['tapatipometadataestado']==ACTIVO)
			{
				$tipoactivacion = 4;
				$class = "activo";
				
			}

		//print_r($fila);	
			$linkedit ='<a class="editar" href="javascript:void(0)" onclick="EditTipoMetadataCampo('.$fila['tapatipometadatacod'].')" title="Editar" >&nbsp;</a>';
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarTipoMetadataCampo('.$fila['tapatipometadatacod'].')" title="Eliminar" >&nbsp;</a>';
			$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['tapatipometadatacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';

			$datosmostrar = array(
				$fila['tapatipometadatacod'],
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tapatipometadatacampo'],ENT_QUOTES)),
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tapatipometadatacte'],ENT_QUOTES)),
				$linkestado,$linkedit,$linkdel
			);
			$responce->rows[$i]['tapatipometadatacod'] = $fila['tapatipometadatacod'];
			$responce->rows[$i]['id'] = $fila['tapatipometadatacod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
		
	}
}
echo json_encode($responce);
?>
