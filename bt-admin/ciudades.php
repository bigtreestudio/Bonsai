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

$oProvincias = new cProvincias($conexion,"");

$_SESSION['msgactualizacion'] = "";
$_SESSION['volver'] = "provincias.php";
$mensajeaccion = "";
$volver= $_SESSION['volver']; 
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';
	
if (isset($_GET['provinciacod']) && $_GET['provinciacod']!="")
{
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("provinciacod"=>$_GET['provinciacod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	
	
}
if($oProvincias->BuscarxCodigo ($_GET,$resultado,$numfilas)) 
$datosprovincia= $conexion->ObtenerSiguienteRegistro($resultado);

?>


<script type="text/javascript" src="js/archivos/ciudades.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Cuidades - <?php  echo utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($datosprovincia['provinciadesc']))  ?></h2>
</div>
 
<div class="txt_izq">
     <form action="cuidades.php" method="post" name="formbusqueda" id="formbusqueda">
		<input type="hidden" name="provinciacod" id="provinciacod" value="<?php  echo $_GET['provinciacod'] ?>" />
    </form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a href="javascript:void(0)" onclick="AltaDepartamento(<?php  echo $_GET['provinciacod']?>)">Nueva Ciudad</a></li>
        <li><a class="left" href="<?php  echo $volver?>">Volver</a></li>
    </ul>
</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstDepartamentos" style="width:100%;">
    <table id="ListarDepartamentos"></table>
    <div id="pager2"></div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>