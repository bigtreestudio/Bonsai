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

$oAlbums = new cAlbums($conexion,"");

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
	
$sidx ="albumorden"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

if (!$oAlbums->BuscarAvanzadaxAlbumSuperior ($datos,$resultado,$numfilas))
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

	
if (!$oAlbums->BuscarAvanzadaxAlbumSuperior($datos,$resultado,$numfilas))
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
		$linkestado="No Publicada";
		if ($fila['albumestadocod']==ACTIVO)
			$linkestado="Publicada";
			
		
		if ($oAlbums->PuedeEliminarAlbum($fila))
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarAlbum('.$fila['albumcod'].')" title="Eliminar" ></a>';
		else
			$linkdel = "";	
		
		FuncionesPHPLocal::ArmarLinkMD5("gal_albums.php",array("albumcod"=>$fila['albumcod']),$get,$md5);
		$link = '<div>'.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['albumtitulo'],ENT_QUOTES)).'</div><div>&nbsp;</div><div><a class="link" href="gal_albums.php?albumsuperior='.$fila['albumcod'].'&md5='.$md5.'" title="Ingresar al albums de '.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['albumtitulo'],ENT_QUOTES)).'">Ingresar al Albums de '.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['albumtitulo'],ENT_QUOTES)).'</a></div><div>&nbsp;</div>';
		FuncionesPHPLocal::ArmarLinkMD5("gal_albums_gal_galerias.php",array("albumcod"=>$fila['albumcod']),$getgal,$md5gal);
		$linkgal ='<a class="link" href="gal_albums_gal_galerias.php?albumcod='.$fila['albumcod'].'&md5='.$md5gal.'" title="Armar galeria" >Armar galeria</a>';
		$linkedit = '<a class="editar" href="javascript:void(0)" onclick="EditarAlbums('.$fila['albumcod'].',\''.$fila['albumsuperior'].'\')" title="Editar" ></a>';
		$linkprev = '<a class="ver" href="gal_albums_salto.php?albumcod='.$fila['albumcod'].'" title="Previsualizar" target="_blank"></a>';
		
		$datosmostrar = array($fila['albumcod'],$link,$linkestado,$linkgal,$linkprev,$linkedit,$linkdel);
		$responce->rows[$i]['albumcod'] = $fila['albumcod'];
		$responce->rows[$i]['id'] = $fila['albumcod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
