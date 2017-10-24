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

$oRevistaTapas= new cRevistaTapas($conexion);

$_SESSION['msgactualizacion'] = "";
$revtapatitulo="";
$revtapacod="";

?>
<link rel="stylesheet" type="text/css" href="modulos/rev_tapas/css/estilos.css" />
<script type="text/javascript" src="modulos/rev_tapas/js/rev_tapas.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Tapas</h2>
</div>
 
<div class="form">
<form action="tap_tapas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
                    <label>Título:</label>
                </div>
                <div class="ancho_6">
                   <input name="revtapatitulo" id="revtapatitulo" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>

    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>
    <input type="hidden" name="revtapatitulo" id="revtapatitulo" value="<?php  echo $revtapatitulo?>" />
    <input type="hidden" name="revtapacod" id="revtapacod" value="<?php  echo $revtapacod?>" />
    
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
    	<li><a class="left boton verde" href="rev_tapas_am.php">Crear nueva Tapa</a></li>
    </ul>
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstRevTapa" style="width:100%;">
       <table id="listarRevTapa"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>