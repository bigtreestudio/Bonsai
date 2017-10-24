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
$oGraficosValores = new cGraficosValoresPorcentajes($conexion,"");

//print_r($_POST);
header('Content-Type: text/html; charset=iso-8859-1'); 

function js_str($s) {
  return '"'.addcslashes($s, "\0..\37\"\\").'"';
}

function js_array($arreglo) {
  $temp=array();
  foreach ($arreglo as $fila)
  { 
  	$temp[] = js_str($fila);
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

	
if (!$oGraficosValores->BuscarxGrafico($datosgrafico,$resultadoval,$numfilasval))
	die();
$arreglofilas = array();
while($datavalores = $conexion->ObtenerSiguienteRegistro($resultadoval))
	$arreglofilas[$datavalores['filacod']] = $datavalores['valor'];


$i = 0;
$arreglo=$arreglotitulo=$arreglocomuness=array();

while ($fila = $conexion->ObtenerSiguienteRegistro($resultadofilas))
{
	$valores = 0;
	if (array_key_exists($fila['filacod'],$arreglofilas))
		$valores = $arreglofilas[$fila['filacod']];
	
	if ($valores!="")
		$valormostrar = $valores;
	else
		$valormostrar = NULL;
	
	$arreglotitulos[]=utf8_encode($fila['filatitulo']);
	$arreglocarga['name']= utf8_encode($fila['filatitulo']);
	$arreglocarga['color']= $fila['filacolor'];
	$arreglocarga['y']= $valormostrar;

	$arreglo[] = $arreglocarga;
	$arreglocomunes[$i] = $arreglocarga;
	$i++;	
}

if ($datosgrafico['graficotipovalor']=="pie")
{
	$arreglodatosmostrar['data']=$arreglo;
	$arreglodatosmostrar['type']=$datosgrafico['graficotipovalor'];
}else
{
	$arreglodatosmostrar['data']=$arreglocomunes;
	$arreglodatosmostrar['type']=$datosgrafico['graficotipovalor'];
}
$codigo = rand(0,100000000);
$datosgrafico=FuncionesPHPLocal::DecodificarUtf8 ($datosgrafico);


$json = json_encode($arreglodatosmostrar);
$json = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $json);

$jsongrafico = json_encode($datosgrafico);
$jsongrafico = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $jsongrafico);

?>
<script type="text/javascript">
var categories = <?php  echo js_array($arreglotitulos)?>;
var seriescarga = [<?php  echo $json;?>];
var datosgrafico = <?php  echo $jsongrafico;?>;

jQuery(document).ready(function(){
	GraficoPorcentajes('<?php  echo $datosgrafico['graficotipovalor']?>',<?php  echo $codigo?>);
});
</script>
<div id="container_<?php  echo $codigo?>"></div>
<?php  ?>