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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
$volver= $_SESSION['volver']; 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$oBanners= new cBanners($conexion);
$oAlbums = new cAlbums($conexion,"");
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("albumcod"=>$_GET['albumcod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

if (!$oAlbums->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Albums.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosalbums = $conexion->ObtenerSiguienteRegistro($resultado);
	
$albumcod = $datosalbums['albumcod'];
$albumtitulo=$datosalbums['albumtitulo'];
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/gal_albums_gal_galerias/js/gal_albums_gal_galerias.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	ListarAlbums();			
});
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Galerias del album <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($albumtitulo,ENT_QUOTES)?></h2>
</div>
    
<form action="gal_albums_gal_galerias.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
    <input type="hidden" name="albumcod" id="albumcod" value="<?php  echo $albumcod;?>" />
</form>

<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="BusquedaGaleria(<?php  echo $albumcod ?>)">Agregar galeria</a></li>
        <li><a class="left boton base" href="<?php  echo $volver?>">Volver</a></li>
    </ul>    
</div>

         		
<div id="ModalGaleria"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstAlbums" style="width:100%;">
    <table id="ListarAlbums"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
$oEncabezados->PieMenuEmergente();
?>