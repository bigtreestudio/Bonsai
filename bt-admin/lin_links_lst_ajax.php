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
	
$sidx ="linkorden"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

if (!$oLinks->BuscarAvanzadaxLink($datos,$resultado,$numfilas))
	die();

$datoslink = $conexion->ObtenerSiguienteRegistro($resultado);	
		$linkcod=$datoslink['linkcod'];
		$titulo=$datoslink['linktitulo'];
		$linkdesclarga=$datoslink['linkdesclarga'];
		$linklink=$datoslink['linklink'];
		$linktarget=$datoslink['linktarget'];
		$linkarchubic=$datoslink['linkarchubic'];
		$linkarchnombre=$datoslink['linkarchnombre'];
		$linkarchsize=$datoslink['linkarchsize'];
		$linkestado=$datoslink['linkestado'];
		$linkorden=$datoslink['linkorden'];

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


if (!$oLinks->BuscarAvanzadaxLink($datos,$resultado,$numfilas))
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


		if ($fila['linkestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		
		FuncionesPHPLocal::ArmarLinkMD5("lin_links.php",array("linkcod"=>$fila['linkcod']),$get,$md5);
	
		$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarLinks('.$fila['linkcod'].')" title="Eliminar" >&nbsp;</a>';
		$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['linkcod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$linkedit = '<a class="editar" href="lin_links_am.php?linkcod='.$linkcod.'" onclick="lin_links_am.php?linkcod='.$linkcod.'" title="Editar" >&nbsp;</a>';
		 

		$datosmostrar = array($fila['linkcod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['linklink'],ENT_QUOTES)),$linkestado,$linkedit,$linkdel);
		
	
		$responce->rows[$i]['linkcod'] = $fila['linkcod'];
		$responce->rows[$i]['id'] = $fila['linkcod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
