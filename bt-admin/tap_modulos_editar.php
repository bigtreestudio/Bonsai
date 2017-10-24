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

$oTapas= new cTapas($conexion);

$_SESSION['msgactualizacion'] = "";
$tapatipocod="";
?>
<link rel="stylesheet" type="text/css" href="modulos/tap_tapas/css/estilos.css" />
<script type="text/javascript" src="modulos/tap_tapas/js/tap_modulos_editar.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Modulos de la Portada</h2>
</div>
 
<form action="tap_tapas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">

	<input type="hidden" name="tapacod" id="tapacod" value="<?php  echo $_GET["tapacod"]?>" />
</form>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstTapasModulos" style="width:100%;">
       <table id="listarTapasModulos"></table>
    <div id="pager2"></div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>