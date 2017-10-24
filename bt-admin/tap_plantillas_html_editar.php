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


$oPlantillas=new cPlantillasHtml($conexion);

if (!isset($_GET['planthtmlcod']) || $_GET['planthtmlcod']=="")
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("planthtmlcod"=>$_GET['planthtmlcod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
$planthtmlcod = $_GET['planthtmlcod'];
if (!$oPlantillas->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la plantilla html por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datosencontrados = $conexion->ObtenerSiguienteRegistro($resultado);	

FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_editar_connect.php",array("planthtmlcod"=>$datosencontrados['planthtmlcod']),$get,$md5);

?>
<link href="css/elfinder.min.css" rel="stylesheet" title="style" media="all" />
<link href="css/theme.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/elfinder.min.js"></script>
<script type="text/javascript" src="js/elfinder.es.js" charset="utf-8"></script>

<script type="text/javascript">

var mytoolbar = [
		['back', 'forward'],
		['upload'],
		['open', 'download', 'getfile'],
		['quicklook'],
		['copy', 'cut', 'paste'],
		['rm'],
		['rename', 'edit'],
		['view']
	];
	
	$().ready(function() {
		var elf = $('#AdminFiles').elfinder({
			url : 'tap_plantillas_html_editar_connect.php?planthtmlcod=<?php  echo $planthtmlcod ?>&md5=<?php  echo $md5?>',  // connector URL (REQUIRED)
			uiOptions : {toolbar : mytoolbar},
			"params"    : {
				"uplMaxSize" : "1M"
			},
			lang: 'es'            // language (OPTIONAL)
		}).elfinder('instance');
});

function Seleccionar()
{
	$("#archnom").focus();
	$("#archnom").select();
}
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Administrador de archivos de la plantilla <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosencontrados['planthtmldesc'],ENT_QUOTES)?></h2>
</div>

<div id="AdminFiles"></div>
<div class="clearboth">&nbsp;</div>

 <div class="menubarra">
     <ul>
            <li><a class="boton base" href="tap_plantillas_html.php">Volver al listado</a></li>
    </ul>
 </div>

<div class="clearboth">&nbsp;</div>


<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>