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

$oContactos= new cContactos($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="formulariocod"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;


if (!$oContactos->BusquedaAvanzada($datos,$resultado,$numfilas))
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


if (!$oContactos->BusquedaAvanzada($datos,$resultado,$numfilas))
		die();

$i = 0;

$responce =new StdClass;
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


		if ($fila['formularioestado']==ACTIVO)
		{
			$tipoactivacion = 4;
			$class = "activo";
			
		}
		

		$formulariodel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarFormContacto('.$fila['formulariocod'].')" title="Eliminar" >&nbsp;</a>';
		$formularioestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['formulariocod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		$formularioedit ='<a class="editar" href="con_contactos_am.php?formulariocod='.$fila['formulariocod'].'" title="Editar"">&nbsp;</a>';		
		$formmularioemail='<a class="email" href="javascript:void(0)" onclick="EmailDestino('.$fila['formulariocod'].')" title="Opciones" >&nbsp;</a>';	
		$datosmostrar = array($fila['formulariocod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['formulariotipotitulo'],ENT_QUOTES)),$formmularioemail,$formularioestado,$formularioedit,$formulariodel);
		
	
		$responce->rows[$i]['formulariocod'] = $fila['formulariocod'];
		$responce->rows[$i]['id'] = $fila['formulariocod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
