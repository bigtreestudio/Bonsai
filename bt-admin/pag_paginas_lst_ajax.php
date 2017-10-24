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

$oPaginas=new cPaginas($conexion,"");



header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="pagcod"; 
$sord ="DESC"; 

$datos = $_POST;
$_SESSION['datosbusquedafiltropagina'] = $_POST;


$datos['orderby'] = $sidx." ".$sord;
$datos['rolcod'] = $_SESSION['rolcod'];
$datos['paginaestadobaja'] = 1;
$datos['pagcopiacod']=1;
if (!$oPaginas->BusquedaAvanzada($datos,$resultado,$numfilas))
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

if (!$oPaginas->BusquedaAvanzada($datos,$resultado,$numfilas))
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
		
		if ($fila['puedeeditar'])
		{
			FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_am.php",array("pagcod"=>$fila['pagcod']),$getpag,$md5);
			$linkedit = '<a class="editar" href="pag_paginas_am.php?'.$getpag.'" title="Editar p&aacute;gina" >&nbsp;</a>';
			if ($fila['pagestadocod']==PAGPUBLICADA)
			{	
				FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_publicacion.php",array("pagcod"=>$fila['pagcod']),$get,$md5);
				$linkedit = '<a class="editar" href="pag_paginas_publicacion.php?'.$get.'" title="Publicacion" id="editar_'.$fila['pagcod'].'">&nbsp;</a>';
			}
		}else
		{
			FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_am.php",array("pagcod"=>$fila['pagcod']),$get,$md5);
			$linkedit = '<a class="ver" href="pag_paginas_am.php?'.$get.'" title="Ver P&aacute;gina" id="editar_'.$fila['pagcod'].'">&nbsp;</a>';
			if ($fila['pagestadocod']==PAGPUBLICADA)
			{	
				FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_publicacion.php",array("pagcod"=>$fila['pagcod']),$get,$md5);
				$linkedit = '<a class="editar" href="pag_paginas_publicacion.php?'.$get.'" title="Publicacion" id="editar_'.$fila['pagcod'].'">&nbsp;</a>';
			}
		}



		$linkdel = '';
		if ($fila['puedeeliminar'])
		{
			FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_eliminar.php",array("pagcod"=>$fila['pagcod'],"paginaworkflowcod"=>$fila['pagworkflowcodeliminar'],"accion"=>1),$getdel,$md5del);
			$linkdel = '<a class="eliminar" href="pag_paginas_eliminar.php?'.$getdel.'" title="Eliminar" id="eliminar_'.$fila['pagcod'].'" onclick="if (!confirm(\'Esta seguro que desea eliminar la pagina?\')) return false;">&nbsp;</a>';
		}
		
		$linktitulo=utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['pagtitulo'],ENT_QUOTES));
		if($fila["pagcopiacodorig"]!=""){
			FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_publicacion.php",array("pagcod"=>$fila['pagcopiacodorig']),$get,$md5);
			$linktitulo='<div>'.$linktitulo.'<div/><div style="margin:5px 0;"><a class="puborig" href="pag_paginas_publicacion.php?'.$get.'" title="Publicacion" id="editar_'.$fila['pagcopiacodorig'].'"> (Ver publicaci&oacute;n original)</a></div>';
		}
		
		$datosmostrar = array(
			$fila['pagcod'],
			$linktitulo,
			utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)),
			utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['estado'],ENT_QUOTES)),
			$linkedit,
			$linkdel
		);
		$responce->rows[$i]['pagcod'] = $fila['pagcod'];
		$responce->rows[$i]['id'] = $fila['pagcod'];
		$responce->rows[$i]['cell'] = $datosmostrar;
		$i++;
	}
}
echo json_encode($responce);
?>
