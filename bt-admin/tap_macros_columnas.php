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
$_SESSION['volver']= "tap_macros.php"; 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$oMacrosEstructuras = new cMacrosEstructuras($conexion,"");
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("estructuracod"=>$_GET['estructuracod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

if (!$oMacrosEstructuras->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Macro Estructura.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosmacro = $conexion->ObtenerSiguienteRegistro($resultado);
	
$estructuracod = $datosmacro['estructuracod'];
$estructuradesc=$datosmacro['estructuradesc'];
?>
<link rel="stylesheet" type="text/css" href="modulos/tap_macros/css/tap_macros_columnas.css" />
<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/tap_macros/js/tap_macros_columnas.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Macro Columna: <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($estructuradesc,ENT_QUOTES)?></h2>
</div>
    
<form action="tap_macros_columnas.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
<div class="ancho_10">
        <div class="ancho_2">
            <div class="ancho_3">
                <label>Descripci&oacute;n:</label>
            </div>
            <div class="ancho_6">
               <input name="columnadescbusqueda" id="columnadescbusqueda" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_3">&nbsp;</div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
    <input type="hidden" name="estructuracod" id="estructuracod" value="<?php  echo $estructuracod;?>" />

</form>

<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><a class="left" href="javascript:void(0)" onclick="AltaMacrosColumna(<?php  echo  $estructuracod ?>)">Agregar Columna</a></li>
        <li><a class="left" href="javascript:void(0)" onclick="Resetear()">Limpiar Busqueda</a></li>
        <li><a class="left" href="javascript:history.back()">Volver</a></li>
    </ul>    
</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstMacrosColumnas" style="width:100%;">
    <table id="ListarMacrosColumnas"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
<?php  
$oEncabezados->PieMenuEmergente();
?>