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

$oRevistaTapasMultimedia = new cRevistaTapasMultimedia($conexion,"");



header('Content-Type: text/html; charset=iso-8859-1'); 

$datos = $_POST;

if (!$oRevistaTapasMultimedia->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();


$i = 0;

$responce =new StdClass; 
$responce->page = 1;
$responce->total = $numfilas; 
$responce->records = $numfilas; 
$responce->rows = array();


if ($numfilas>0)
{
	
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
			$tipoactivacion = 5;
			$class = "desactivo";
			if ($fila['revtapamulestado']==ACTIVO)
			{
				$tipoactivacion = 4;
				$class = "activo";
				
			}
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarRevTapaMultimedia('.$fila['revtapamulcod'].','.$fila['revtapacod'].')" title="Eliminar" ></a>';
			$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['revtapamulcod'].','.$fila['revtapacod'].','.$tipoactivacion.')" title="Activar / desactivar" >&nbsp;</a>';
		   	$linkimg = '<img style="width:50px; height:50px" src='. DOMINIO_SERVIDOR_MULTIMEDIA."/tapas/Thumbs/".$fila['revtapamulubic'].' title="Tapa'.$fila['revtapamuldescripcion'].'" </img>';
		   	$linkorden = '<div class="ordenhiijos" style="width:20px; height:20px; text-align:center; margin:auto;">&nbsp;</div>';
			$datosmostrar = array(
				$fila['revtapamulcod'],$linkimg, 
				$linkorden,
				$linkestado,
				$linkdel
			);
			$responce->rows[$i]['revtapamulcod'] = $fila['revtapamulcod'];
			$responce->rows[$i]['id'] = $fila['revtapamulcod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
		
	}
}
echo json_encode($responce);
?>
