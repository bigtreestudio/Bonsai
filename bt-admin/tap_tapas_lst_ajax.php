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

$oTapas= new cTapas($conexion);
$oTapasTipos= new cTapasTipos($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="tapacod"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

if (!$oTapas->BusquedaAvanzada($datos,$resultado,$numfilas))
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


if (!$oTapas->BusquedaAvanzada($datos,$resultado,$numfilas))
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
		

		
		
		if (!$oTapasTipos->BuscarxCodigoDeTapaPublicada($fila,$resultadotapaspublicadas,$numfilastapaactiva))
			die();


		$tapaactiva = '<div style="color:#F00; font-size:12px; font-weight:bold;">No</div>';
		if($numfilastapaactiva>0){
			$tapaactiva = '<div style="color:#0C0; font-size:12px; font-weight:bold;" >Si</div>';
			}

		
		
		$tipoactivacion = 5;
		$class = "desactivo";
		$classmul = "";


		if ($fila['tapaestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		FuncionesPHPLocal::ArmarLinkMD5("tap_modulos_editar.php",array("tapacod"=>$fila['tapacod']),$gettapedit,$md5tapedit);

		$modulos="";
		if($_SESSION["rolcod"]==ADMISITE){
			$modulos='<br> <div style="margin-bottom:10px"><a href="tap_modulos_editar.php?'.$gettapedit.'" target="_blank" class="editarotrosdatos" title="Editar Modulos">Editar Modulos</a><br></div>';
		}
		FuncionesPHPLocal::ArmarLinkMD5("tap_tapas_confeccionar.php",array("tapacod"=>$fila['tapacod']),$getconf,$md5conf);
		FuncionesPHPLocal::ArmarLinkMD5("tap_tapas.php",array("tapacod"=>$fila['tapacod']),$get,$md5);
	
		$tapadel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarTapa('.$fila['tapacod'].')" title="Eliminar" >&nbsp;</a>';
		$tapaestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['tapacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$tapaedit = '<a class="editar" href="javascript:void(0)" onclick="EditarTapas('.$fila['tapacod'].')" title="Editar" >&nbsp;</a>';
		$tapaconfeccionar = '<a class="confeccionar"  href="tap_tapas_confeccionar.php?'.$getconf.'" target="_blank"  title="Confeccionar tapa">&nbsp;</a>'.$modulos;
		$tapametadata = '<a class="metadatos" href="javascript:void(0)" onclick="MetadatosTapas('.$fila['tapacod'].')" title="Editar Metadatos" >&nbsp;</a>';
		
		$datosmostrar = array($fila['tapacod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tapanom'],ENT_QUOTES)),utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tapatipodesc'],ENT_QUOTES)),utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['plantdesc'],ENT_QUOTES)),$tapaactiva,$tapaconfeccionar,$tapametadata,$tapaestado,$tapaedit,$tapadel);
		
	
		$responce->rows[$i]['tapacod'] = $fila['tapacod'];
		$responce->rows[$i]['id'] = $fila['tapacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
