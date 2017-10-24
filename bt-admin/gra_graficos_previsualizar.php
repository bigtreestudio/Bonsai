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

$oGraficos = new cGraficos($conexion,"");
$oGraficosFilas = new cGraficosFilas($conexion,"");
$oGraficosColumnas = new cGraficosColumnas($conexion,"");
$oGraficosValores = new cGraficosValores($conexion,"");

//print_r($_POST);
header('Content-Type: text/html; charset=iso-8859-1'); 

function js_str($s) {
  return '"'.addcslashes($s, "\0..\37\"\\").'"';
}

function js_query($conexion,$resultado,$clave) {
  $temp=array();
  while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
  { 
  	$temp[] = js_str($fila[$clave]);
  }
  return '['.implode(',', $temp).']';
}

$graficocod = $_POST['graficocod'];
if (!$oGraficos->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el grafico por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datosgrafico = $conexion->ObtenerSiguienteRegistro($resultado);	
$graficotitulocolumnas = $datosgrafico['graficotitulocolumnas'];
$graficotitulofilas = $datosgrafico['graficotitulofilas'];

$codigo = rand(0,100000000);

if (!$oGraficosFilas->BuscarxGrafico ($datosgrafico,$resultadofilas,$numfilasfilas))
	die();

if ($numfilasfilas==0)
{
	?>
    	<div style="text-align:center; font-weight:bold; font-size:24px;">DEBE EXISTIR AL MENOS UNA SERIE PARA PREVISUALIZAR</div>
    <?php  
	die();
}

if (!$oGraficosColumnas->BuscarxGrafico ($datosgrafico,$resultadocol,$numfilascol))
	die();

if ($numfilascol==0)
{
	?>
    	<div style="text-align:center; font-weight:bold; font-size:24px;">DEBE EXISTIR AL MENOS UN EJE PARA PREVISUALIZAR</div>
    <?php  
	die();
}
	
	
$arreglodatos = array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultadocol))
	$arreglodatos[] = $fila;


$i = 0;
while ($fila = $conexion->ObtenerSiguienteRegistro($resultadofilas))
{
	if (!$oGraficosValores->BuscarxGraficoxFila($fila,$resultadoval,$numfilasval))
		die();
	$arreglofilas = array();
	while($datavalores = $conexion->ObtenerSiguienteRegistro($resultadoval))
		$arreglofilas[$datavalores['columnacod']] = $datavalores['valor'];

	$arreglodatosmostrar[$i]['name'] = utf8_encode($fila['filatitulo']);
	$arreglodatosmostrar[$i]['color'] = $fila['filacolor'];

	foreach($arreglodatos as $datos)
	{
		$valores = 0;
		if (array_key_exists($datos['columnacod'],$arreglofilas))
			$valores = $arreglofilas[$datos['columnacod']];
			
		if ($valores!="")
			$arreglodatosmostrar[$i]['data'][] = $valores;
		else
			$arreglodatosmostrar[$i]['data'][] = NULL;
	}  
	$i++; 
}

$conexion->MoverPunteroaPosicion($resultadocol,0);
if ($datosgrafico['graficoestilo']!="")
{
?>
	<script src="../js/highcharts/themes/<?php  echo $datosgrafico['graficoestilo']?>.js?rand=<?php  echo rand(0,500000000)?>"></script>
<?php  
}
$datosgrafico=FuncionesPHPLocal::DecodificarUtf8 ($datosgrafico);

$json = json_encode($arreglodatosmostrar);
$json = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $json);

$jsongrafico = json_encode($datosgrafico);
$jsongrafico = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $jsongrafico);

?>
<script type="text/javascript">
var categories = <?php  echo js_query($conexion,$resultadocol,"columnatitulo")?>;
var seriescarga = <?php  echo $json;?>;
var datosgrafico = <?php  echo $jsongrafico;?>;
jQuery(document).ready(function(){
	GraficoBarras('<?php  echo $datosgrafico['graficotipovalor']?>',<?php  echo $codigo?>);
});
</script>
<div id="container_<?php  echo $codigo?>"></div>