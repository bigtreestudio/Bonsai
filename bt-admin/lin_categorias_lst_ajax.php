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

$oLinks = new cLinks($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="catorden"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

	if (!$oLinks->BuscarListadoxCategoria($datos,$resultado,$numfilas))
		return false;

	if ($numfilas<=0)
	{
		FuncionesPHPLocal::MostrarMensaje($conexon,MSG_ERRGRAVE,"Error al buscar las categorias. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}

	$datoscategorias = $conexion->ObtenerSiguienteRegistro($resultado);	
		$catcod=$datoscategorias['catcod'];
		$catnom=$datoscategorias['catnom'];
		$catdesc=$datoscategorias['catdesc'];
		$catsuperior=$datoscategorias['catsuperior'];
		$catorden=$datoscategorias['catorden'];
		$catestado=$datoscategorias['catestado'];	

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


if (!$oLinks->BuscarListadoxCategoria($datos,$resultado,$numfilas))
		return false;

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


		if ($fila['catestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		
		FuncionesPHPLocal::ArmarLinkMD5("lin_categorias.php",array("catcod"=>$fila['catcod']),$get,$md5);
	
		$catdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarCategoria('.$fila['catcod'].')" title="Eliminar" >&nbsp;</a>';
		$catestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['catcod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$catedit = '<a class="editar" href="javascript:void(0)" onclick="EditarCategorias('.$fila['catcod'].')" title="Editar" >&nbsp;</a>';
		$catlinks = '<a class="link" href="lin_links.php?catcod='.$fila['catcod'].'" onclick="lin_links.php?catcod='.$fila['catcod'].'" title="Links" ></a>';

		$datosmostrar = array($fila['catcod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)),$catlinks,$catestado,$catedit,$catdel);
		
	
		$responce->rows[$i]['catcod'] = $fila['catcod'];
		$responce->rows[$i]['id'] = $fila['catcod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
