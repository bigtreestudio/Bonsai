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

$oLinks = new cLinks($conexion,"");

if (!isset($_GET['catcod']) || $_GET['catcod']=="")
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
$_SESSION['volver']="lin_links.php?catcod=".$_GET['catcod'];

$catcod = $_GET['catcod'];
$datos['catcod']= $_GET['catcod'];
if(!$oLinks->BuscarCategoriaxCatcod($datos,$resultado,$numfilas))
	return false;
if($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
$datoscategoria = $conexion->ObtenerSiguienteRegistro($resultado);

$_SESSION['msgactualizacion'] = "";

?>

<script type="text/javascript" src="modulos/lin_links/js/lin_links.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	listarLinks();	
});
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Links de la categoria <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscategoria['catnom'],ENT_QUOTES)?></h2>
</div>
 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left" href="lin_links_am.php?catcod=<?php  echo $catcod?>"  >Crear nuevo link</a></li>
    </ul>
    <ul>
        <li><a class="left" href="lin_categorias.php">Volver</a></li>
    </ul>
</div>

<div class="txt_izq">
    <form action="lin_links.php" method="post" name="formbusqueda" id="formbusqueda">
       <input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod ?>" />
   </form>
</div>


<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstLinks" style="width:100%;">
       <table id="listarLinks"></table>
    <div id="pager2"></div>
</div>

<?php  
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>