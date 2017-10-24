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

$oNoticias = new cNoticias($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);


$_SESSION['datosbusquedafiltro'] = $_POST;

if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="n.noticiacod"; 
$sord ="DESC"; 

$datos = $_POST;
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['rolcod'] = $_SESSION['rolcod'];
$datos['noticiasestadobaja'] = 1;

$error = false;
$datos['orderby'] = $sidx." ".$sord;
if(!$oNoticias->BusquedaAvanzada($datos,$resultadonoticias,$numfilas)) {
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
	
if(!$oNoticias->BusquedaAvanzada($datos,$resultado,$numfilas)) {
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
		
		$fila['noticiatitulo'] = utf8_encode($fila['noticiatitulo']);
		$descripcioncat="";
		$descripcioncat = utf8_encode($fila['catnom']);
		
		$fecha = FuncionesPHPLocal::ConvertirFecha($fila['noticiafecha'],"aaaa-mm-dd","dd/mm/aaaa");
		
		$link = "";
		if ($fila['puedeeditar'])
		{
			FuncionesPHPLocal::ArmarLinkMD5("not_noticias_am.php",array("noticiacod"=>$fila['noticiacod']),$get,$md5);
			$link = '<a class="editar" href="not_noticias_am.php?'.$get.'" title="Editar Noticia" id="editar_'.$fila['noticiacod'].'">&nbsp;</a>';
			if ($fila['noticiaestadocod']==NOTPUBLICADA)
			{	
				FuncionesPHPLocal::ArmarLinkMD5("not_noticias_publicacion.php",array("noticiacod"=>$fila['noticiacod']),$get,$md5);
				$link = '<a class="editar" href="not_noticias_publicacion.php?'.$get.'" title="Publicacion" id="editar_'.$fila['noticiacod'].'">&nbsp;</a>';
			}
		}else
		{
			FuncionesPHPLocal::ArmarLinkMD5("not_noticias_am.php",array("noticiacod"=>$fila['noticiacod']),$get,$md5);
			$link = '<a class="ver" href="not_noticias_am.php?'.$get.'" title="Ver Noticia" id="editar_'.$fila['noticiacod'].'">&nbsp;/a>';
			if ($fila['noticiaestadocod']==NOTPUBLICADA)
			{	
				FuncionesPHPLocal::ArmarLinkMD5("not_noticias_publicacion.php",array("noticiacod"=>$fila['noticiacod']),$get,$md5);
				$link = '<a class="editar" href="not_noticias_publicacion.php?'.$get.'" title="Publicacion" id="editar_'.$fila['noticiacod'].'">Ver Publicaci&oacute;n</a>';
			}
		}

		if ($fila['puedeeliminar'])
		{
			FuncionesPHPLocal::ArmarLinkMD5("not_noticias_eliminar.php",array("noticiacod"=>$fila['noticiacod'],"noticiaworkflowcod"=>$fila['noticiaworkflowcodeliminar'],"accion"=>1),$getdel,$md5del);
			//$acciondel= utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['acciondel'],ENT_QUOTES));
			$link .= '&nbsp;&nbsp;<a class="eliminar" href="not_noticias_eliminar.php?'.$getdel.'" title="Eliminar" id="eliminar_'.$fila['noticiacod'].'" onclick="if (!confirm(\'Esta seguro que desea eliminar la noticia?\')) return false;">&nbsp;</a>';
		}
		

		$linktitulo=$fila['noticiatitulo'];
		//print_r($fila["noticiacopiacodorig"]);
		if($fila["noticiacopiacodorig"]!=""){
			FuncionesPHPLocal::ArmarLinkMD5("not_noticias_publicacion.php",array("noticiacod"=>$fila['noticiacopiacodorig']),$get,$md5);
			$linktitulo='<div>'.$fila['noticiatitulo'].'<div/><div style="margin:5px 0;"><a class="puborig" href="not_noticias_publicacion.php?'.$get.'" title="Publicacion" id="editar_'.$fila['noticiacopiacodorig'].'"> (Ver publicaci&oacute;n original)</a></div>';
		}
		$datosmostrar = array($fila['noticiacod'],$linktitulo,$descripcioncat,$fecha,utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['estado'],ENT_QUOTES)),$link);
		$responce->rows[$i]['cell'] = $datosmostrar;
		
		$responce->rows[$i]['noticiacod'] = $fila['noticiacod'];
		$responce->rows[$i]['id'] = $fila['noticiacod'];
		
		
		$i++;

	}
}


echo json_encode($responce);


?>