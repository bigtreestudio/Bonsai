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

$oAlbums = new cAlbums($conexion,"");




$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$albumcod = "";
$nivelalbumdesc = "Inicio";
$albumsup = "";
$albumsuperior = "";

if (isset($_GET['albumsuperior']))
{	
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("albumcod"=>$_GET['albumsuperior']),$get,$md5);
	//FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_GET['albumsuperior']),$get,$md5);
	if(!isset($_GET["md5"]) || $_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$albumsuperior= $_GET['albumsuperior'];
	
	$datosal['albumcod'] = $albumcod = $albumsuperior;
	if (!$oAlbums->BuscarxCodigo($datosal,$resultado,$numfilas))
		return false;
	if($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error no existe el album.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}	
	$datoscategoria = $conexion->ObtenerSiguienteRegistro($resultado);	
	$nivelalbumdesc = $datoscategoria['albumtitulo'];
	$albumsup = "?albumsuperior=".$albumcod."&md5=".$md5;
}

if (!$oAlbums->ArregloHijos($albumcod,$arrcat,$cantidadarreglo))
	return false;


$_SESSION['msgactualizacion'] = "";
$_SESSION['volver'] = "gal_albums.php".$albumsup;
$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

?>

<link rel="stylesheet" type="text/css" href="modulos/gal_albums/css/gal_albums.css" />
<script type="text/javascript" src="modulos/gal_albums/js/gal_albums.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Album</h2>
</div>
 
<div class="txt_izq">
     <form action="gal_albums.php" method="post" name="formbusqueda" id="formbusqueda">
		<input type="hidden" name="albumsuperior" id="albumsuperior" value="<?php  echo $albumcod ?>" />
	</form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="AltaAlbums('<?php  echo $albumsuperior ?>')">Crear nuevo album</a></li>
    </ul>
</div>
<?php  
	$oAlbums->MostrarJerarquia($albumcod,$jerarquia,$nivel);
	print_r ($jerarquia);
?>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstAlbums" style="width:100%;">
    <table id="ListarAlbums"></table>
    <div id="pager2"></div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>