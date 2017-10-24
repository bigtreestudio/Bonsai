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
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

?>


<script type="text/javascript" src="js/archivos/provincias.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Provincias</h2>
</div>
 
<div class="txt_izq">
     <form action="provincias.php" method="post" name="formbusqueda" id="formbusqueda">
	</form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="AltaProvincia()">Nueva Provincia</a></div></li>
    </ul>
</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstProvincias" style="width:100%;">
    <table id="ListarProvincias"></table>
    <div id="pager2"></div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>