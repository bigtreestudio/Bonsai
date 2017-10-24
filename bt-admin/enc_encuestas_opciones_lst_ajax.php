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

$oEncuestasOpciones= new cEncuestasOpciones($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
$page = 1; 

$sidx ="opcionorden"; 
$sord ="ASC"; 
$datos = $_POST;
$datos['orderby'] = $sidx." ".$sord;
if (!$oEncuestasOpciones->BuscarxCodigoEncuestacod($datos,$resultado,$numfilas))
	die();


$responce =new StdClass; 
$responce->page = 1;
$responce->total = 1; 
$responce->records = $numfilas; 
$responce->rows = array();

$i=0;
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{

		//FuncionesPHPLocal::ArmarLinkMD5("tap_tapas_confeccionar.php",array("tapacod"=>$fila['tapacod']),$getconf,$md5conf);
		//FuncionesPHPLocal::ArmarLinkMD5("tap_tapas.php",array("tapacod"=>$fila['tapacod']),$get,$md5);
		
		$opcionndel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarEncuestaOpcion('.$fila['opcioncod'].','.$fila['encuestacod'].')" title="Eliminar" >&nbsp;</a>';
		$opcionedit = '<a class="editar" href="javascript:void(0)" onclick="EditarEncuestasOpciones('.$fila['opcioncod'].')" title="Editar" >&nbsp;</a>';		
		$nombre = '<span id="titulo_'.$fila['opcioncod'].'">'.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['opcionnombre'],ENT_QUOTES)).'</span>';
		$datosmostrar = array($fila['opcioncod'],$nombre,$opcionedit,$opcionndel);
		
	
		$responce->rows[$i]['opcioncod'] = $fila['opcioncod'];
		$responce->rows[$i]['id'] = $fila['opcioncod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
