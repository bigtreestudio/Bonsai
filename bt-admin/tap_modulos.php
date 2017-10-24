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


$_SESSION['msgactualizacion'] = "";


?>
<script type="text/javascript" src="modulos/tap_modulos_confeccionar/js/tap_modulos.js?v=1.1"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>M&oacute;ulos</h2>
</div>

<div class="form">
<form action="tap_tapas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10">
            <div class="ancho_3">
                <div class="ancho_4">
                    <label>Descripci&oacute;n:</label>
                </div>
                <div class="ancho_6">
                   <input name="modulodesc" id="modulodesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />               </div>
            </div>
            
            <div class="ancho_3">&nbsp;</div>

    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>
</form>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="AltaModulo()">Agregar M&oacute;dulo</a></li>
        <li><a class="boton base" href="tap_modulos_files.php">Administrar archivos de m&oacute;dulos</a></li>
    </ul>
</div>
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstModulos" style="width:100%;">
       <table id="listarModulos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>