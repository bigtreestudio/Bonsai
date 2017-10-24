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
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);


if (!isset($_GET['encuestacod']) || $_GET['encuestacod']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['encuestacod'],"NumericoEntero"))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar la encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("encuestacod"=>$_GET['encuestacod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$oEncuestas= new cEncuestas($conexion);
$oEncuestasOpciones= new cEncuestasOpciones($conexion);
$oEncuestasRespuestas= new cEncuestasRespuestas($conexion);

if (!$oEncuestas->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Encuesta inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datosencuestas = $conexion->ObtenerSiguienteRegistro($resultado);	

$datosencuestas['orderby'] = "opcionorden";
if (!$oEncuestasOpciones->BuscarxCodigoEncuestacod($datosencuestas,$resultadoopc,$numfilas))
	die();

if(!$oEncuestasRespuestas->BuscarCantidadRespuestasxEncuesta($datosencuestas,$resultadorespuestas,$numfilas))
	die();


$arreglodatos = array();
$arreglodatos['data'] = array();
$arreglodatos['type'] = "column";
while ($datosresp = $conexion->ObtenerSiguienteRegistro($resultadorespuestas))
{
	$data['name'] = $datosresp['opcionnombre'];
	$data['y'] = $datosresp['totalvotos'];
	$arreglodatos['data'][] = $data;	
}

$json = json_encode($arreglodatos);
$json = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $json);

?>

<script src="js/highcharts/highcharts.js"></script>
<script src="modulos/enc_encuestas/js/enc_encuestas_respuestas.js"></script>
<link rel="stylesheet" type="text/css" href="modulos/enc_encuestas/css/estilos.css" />
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Respuestas</h2>
</div>
 
<div class="form">
<form action="enc_encuestas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10" style="font-size:14px;">
        <label>Pregunta:</label>
         <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosencuestas['encuestapregunta'],ENT_QUOTES)?>
	    <div class="clear" style="height:1px;">&nbsp;</div>
    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>
    <div id="container"></div>
   	</form>

<div class="clear aire_vertical">&nbsp;</div>

<script type="text/javascript">
	var categorias = <?php  echo FuncionesPHPLocal::js_query($conexion,$resultadoopc,"opcionnombre");?>;
	var seriescarga = [<?php  echo  $json?>];
	GraficoPorcentajes(categorias,seriescarga);
</script>

<div class="clear" style="height:1px;">&nbsp;</div>
<div class="menubarra">
     <ul>
        <li><a class="left boton base" href="enc_encuestas.php">Volver al Listado</a></li>
    </ul>
</div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>