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

$oPaginasCategorias = new cPaginasCategorias($conexion,"");

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
	
$sidx ="catorden"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

if (!$oPaginasCategorias->BuscarAvanzadaxCategoriaSuperior ($datos,$resultado,$numfilas))
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

	
if (!$oPaginasCategorias->BuscarAvanzadaxCategoriaSuperior($datos,$resultado,$numfilas))
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
		
		if ($fila['catestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarCategorias('.$fila['catcod'].')" title="Eliminar" ></a>';
		$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['catcod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';

		FuncionesPHPLocal::ArmarLinkMD5("pag_categorias.php",array("catcod"=>$fila['catcod']),$get,$md5);
		$link = '<div>'.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)).'</div><div>&nbsp;</div><div><a class="link" href="pag_categorias.php?catsuperior='.$fila['catcod'].'&md5='.$md5.'" title="Ingresar a subcategoria des '.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)).'">Ingresar a categoria de '.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)).'</a></div><div>&nbsp;</div>';
		FuncionesPHPLocal::ArmarLinkMD5("pag_paginas.php",array("catcod"=>$fila['catcod']),$getgal,$md5gal);
		$linkpag ='<a class="link" href="pag_paginas.php?catcod='.$fila['catcod'].'&md5='.$md5gal.'" title="P&aacute;ginas" >&nbsp;</a>';
		$linkedit = '<a class="editar" href="javascript:void(0)" onclick="EditarPagCat('.$fila['catcod'].',\''.$fila['catsuperior'].'\')" title="Editar" ></a>';
		$linkorden= '<a class="opciones" href="pag_paginas_orden.php?catcod='.$fila['catcod'].'" title="Orden de las Paginas" ">&nbsp;</a>';

		$datosmostrar = array($fila['catcod'],$link,$linkestado,$linkorden,$linkedit,$linkdel);
		$responce->rows[$i]['catcod'] = $fila['catcod'];
		$responce->rows[$i]['id'] = $fila['catcod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
