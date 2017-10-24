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
$tapatipocod="";
?>
<script type="text/javascript" src="modulos/tap_tapas/js/tap_tapas_tipos.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Tipos de Portadas</h2>
</div>
 <div class="form">
<form action="tap_tapas_tipos.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_2" style="margin-top:5px;">
                    <label>Tipo:</label>
                </div>
                <div class="ancho_8">
                   <input name="tapatipodesc" id="tapatipodesc" class="full" type="text" onkeydown="doSearchTipos(arguments[0]||event)" maxlength="100" size="60" value="" />
                </div>
            </div>
            
            <div class="ancho_3">&nbsp;</div>

    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>
   	</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left boton verde" href="javascript:void(0)" onclick="AltaTipo()">Crear nuevo Tipo de Portada</a></li>
    </ul>
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstTipos" style="width:100%;">
    <table id="listarTipos"></table>
    <div id="pager2"></div>
</div>
<div class="clear" style="height:1px;">&nbsp;</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>