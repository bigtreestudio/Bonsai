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

$oPlantillasAreasHtml = new cPlantillasAreasHtml($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 
$sidx ="areahtmlcod"; 
$sord ="ASC"; 

if (!$oPlantillasAreasHtml->TraerAreasHtml ($resultado,$numfilas))
	die();

$i = 0;

$responce =new StdClass; 
$responce->page = 1;
$responce->total = 1; 
$responce->records = $numfilas; 
$responce->rows = array();


if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
			$linkedit ='<a class="editar" href="javascript:void(0)" onclick="EditarAreaHTML('.$fila['areahtmlcod'].')" title="Editar" >&nbsp;</a>';
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="Eliminar('.$fila['areahtmlcod'].')" title="Eliminar" >&nbsp;</a>';

			$datosmostrar = array(
				$fila['areahtmlcod'],
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['areahtmldesc'],ENT_QUOTES)),
				$linkedit,$linkdel
			);
			$responce->rows[$i]['id'] = $fila['areahtmlcod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
		
	}
}
echo json_encode($responce);
?>
