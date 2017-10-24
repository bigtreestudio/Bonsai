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

$oTemas = new cTemas($conexion,"");




$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';


$temacod = "";
$niveltemadesc = "Inicio";
$temasup = "";
$temacodsuperior = "";

if (isset($_GET['temacodsuperior']))
{	
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("temacod"=>$_GET['temacodsuperior']),$get,$md5);
	//FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_GET['temacodsuperior']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$temacodsuperior= $_GET['temacodsuperior'];
	
	$datoscat['temacod'] = $temacod = $temacodsuperior;
	if (!$oTemas->BuscarxCodigo($datoscat,$resultado,$numfilas))
		return false;
	$datostema = $conexion->ObtenerSiguienteRegistro($resultado);	
	$niveltemadesc = $datostema['tematitulo'];
	$temasup = "?temacodsuperior=".$temacod;
}
if (!$oTemas->ArregloHijos($temacod,$arrcat,$cantidadarreglo))
	return false;


$_SESSION['msgactualizacion'] = "";
$_SESSION['volver'] = "tem_temas.php".$temasup;

?>
<link rel="stylesheet" type="text/css" href="modulos/tem_temas/css/tem_temas.css" />
<script type="text/javascript" src="modulos/tem_temas/js/tem_temas.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Temas</h2>
</div>
 
<div class="txt_izq">
    <form action="tem_temas.php" method="post" name="formbusqueda" id="formbusqueda">
		<input type="hidden" name="temacodsuperior" id="temacodsuperior" value="<?php  echo $temacod ?>" />
	</form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left" href="javascript:void(0)" onclick="AltaTemas('<?php  echo $temacodsuperior ?>')">Crear nuevo Tema</a></li>
    </ul>
</div>
<?php  
	$oTemas->MostrarJerarquia($temacod,$jerarquia,$nivel);
	print_r ($jerarquia);
?>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstTemas" style="width:100%;">
    <table id="ListarTemas"></table>
    <div id="pager2"></div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>