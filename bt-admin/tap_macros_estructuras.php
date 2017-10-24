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
$oMacros = new cMacros($conexion,"");
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("macrocod"=>$_GET['macrocod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

if (!$oMacros->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Albums.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosmacro = $conexion->ObtenerSiguienteRegistro($resultado);
	
$macrocod = $datosmacro['macrocod'];
$macrodesc=$datosmacro['macrodesc'];
?>
<link rel="stylesheet" type="text/css" href="modulos/tap_macros/css/tap_macros_estructuras.css" />
<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/tap_macros/js/tap_macros_estructuras.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	ListarPlantillasMacros();			
});
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Macro: <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($macrodesc,ENT_QUOTES)?></h2>
</div>
    
<form action="tap_macros_estructuras.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
    <input type="hidden" name="macrocod" id="macrocod" value="<?php  echo $macrocod;?>" />
</form>

<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="AltaMacroEstructura(<?php  echo $macrocod;?>)">Agregar Estructura</a></li>
        <li><a class="left boton base" href="<?php  echo $volver?>">Volver</a></li>
    </ul>    
</div>
     		
<div id="ModalMacroCol"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstPlantillasMacros" style="width:100%;">
    <table id="ListarPlantillasMacros"></table>
    <div id="pager2"></div>
</div>
    
<div class="clearboth">&nbsp;</div>

<?php  
$oEncabezados->PieMenuEmergente();
?>