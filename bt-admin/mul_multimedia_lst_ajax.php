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

$error = false;
$datos['orderby'] = $sidx." ".$sord;


if(!$oMultimedia->BusquedaAvanzada($datos,$resultado,$numfilas)) {
	$error = true;
}

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

if(!$oMultimedia->BusquedaAvanzada($datos,$resultado,$numfilas)) {
	$error = true;
}


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
		$imagen = '<div id="multimedia_prev_'.$fila['multimediacod'].'"><img src="'.$oMultimedia->DevolverDireccionImg($fila).'" style="max-width:60px;" width="60px" alt="Imagen" /></div>';
		$fila['multimediatitulo'] = utf8_encode($fila['multimediatitulo']);				

		$class = "desactivo";
		$classmul = "";
		$tipoactivacion = 3;

		if ($fila['multimediaestadocod']==MULTACTIVO)
		{
			$tipoactivacion = 2;
			$class = "activo";
			
		}
		$div="";
		if ($fila['multimediaidexterno']=="")
		$div='</div>';
		
		$multimedianombre = '<div style="float: left; margin-top: -33px;">'.utf8_encode($fila['multimedianombre']).$div;
		$multimediatipoarchivo = utf8_encode($fila['multimediatipoarchivo']);
		$multimediaconjuntodesc = utf8_encode($fila['multimediaconjuntodesc']);		
		$multimediacatnom = utf8_encode($fila['multimediacatnom']);	
		$multimediacatcarpeta = utf8_encode($fila['multimediacatcarpeta']);	
		$linkeliminar = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarMultimedia('.$fila['multimediacod'].')" title="Eliminar " >&nbsp;</a>';
		$multimediaestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['multimediacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$linkeditar =  '<a class="editar" href="javascript:void(0)" onclick="EditarMultimedia('.$fila['multimediacod'].','.$fila['multimediaconjuntocod'].')" title="Editar" >&nbsp;</a>';
		$linkvisualizar =  '<a class="ver" href="javascript:void(0)" onclick="VisualizarMultimedia('.$fila['multimediacod'].','.$fila['multimediaconjuntocod'].')" title="Visualizar" >&nbsp;</a>';


		if ($fila['multimediaidexterno']!="")
			$multimedianombre .= " (Codigo: <b>".utf8_encode($fila['multimediaidexterno'])."</b>)</div>";
		
		if ($fila['multimediadesc']=="")
				$fila['multimediadesc']= "(Sin descripción)";
			
		$multimedianombre .='<div style="margin-top: -15px; margin-left: 10px;"  class="multimediadesctitulo">'.'Titulo:'.'</div><div class="multimediadesc" style="margin-top: -15px; margin-left: 50px;"  >'.html_entity_decode($fila['multimediatitulo']).'</div>';
		
		$multimedianombre .='<div style="margin-top: 3px; margin-left: 10px;"  class="multimediadesctitulo">'.'Descripci&oacute;n:'.'</div><div class="multimediadesc"  style="margin-top: 3px; margin-left: 3px;" >'.utf8_encode($fila['multimediadesc']).'</div>';
		
		$fila['multimediatitulo'] = utf8_encode($fila['multimediatitulo']);

		$datosmostrar = array($fila['multimediacod'],$imagen,$multimedianombre,$multimediaconjuntodesc,$multimediatipoarchivo,$linkvisualizar,$linkeditar,$multimediaestado,$linkeliminar);
		$responce->rows[$i]['cell'] = $datosmostrar;
		
		$responce->rows[$i]['multimediacod'] = $fila['multimediacod'];
		$responce->rows[$i]['id'] = $fila['multimediacod'];
		
		
		$i++;

	}
}


echo json_encode($responce);


?>