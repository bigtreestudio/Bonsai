<?php 
ob_start();
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

$oAgenda = new cAgenda($conexion,"");

//header('Content-type:text/javascript;charset=UTF-8');
header('Content-Type: text/html; charset=iso-8859-1'); 

$datos = $_POST;
$datos['fechainicio'] = date("Y-m-d",$_POST['start']);
$datos['fechafin'] = date("Y-m-d",$_POST['end']);


if(!$oAgenda->BuscarAgendaBusquedaAvanzanda($datos,$resultado,$numfilas))
	return false;
	
$arregloagendas = array();
$arregloturnos = array();
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$arregloagenda =	array(
			'id' => $fila['agendacod'],
			'title' => utf8_encode($fila['agendatitulo'])." - ".utf8_encode($fila['catnom']),
			'description' => "",
			'start' => $fila['agendafdesde']." ".$fila['horainicio'],
			'end' => $fila['agendafhasta']." ".$fila['horafin'],
			'allDay' => false,
			'color' => $fila['agendaestadocolor'],
			'borderColor'=>  '#000000',
			'textColor'=>  '#ffffff',
			'puedoeditar' => true
		);
	$arregloagendas[]=$arregloagenda;
}

echo json_encode($arregloagendas); 
ob_end_flush();
?>