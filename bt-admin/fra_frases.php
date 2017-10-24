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

$oFrases= new cFrases($conexion);

$_SESSION['msgactualizacion'] = "";
$frasecod="";
$fraseurl="";
$fraseorden="1";

?>
<link rel="stylesheet" type="text/css" href="modulos/fra_frases/css/estilos.css" />
<script type="text/javascript" src="modulos/fra_frases/js/fra_frases.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Frases</h2>
</div>
 
<form action="tap_tapas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
                    <label>Autor:</label>
                </div>
                <div class="ancho_6">
                   <input name="fraseautor" id="fraseautor" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
                    <label>Descripci&oacute;n:</label>
                </div>
                <div class="ancho_6">
                   <input name="frasedesclarga" id="frasedesclarga" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />               </div>
            </div>
            
            <div class="ancho_3">&nbsp;</div>

    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>
              <input type="hidden" name="fraseurl" id="fraseurl" value="<?php  echo $fraseurl?>" />
              <input type="hidden" name="fraseorden" id="fraseorden" value="<?php  echo $fraseorden?>" />

   	</form>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left" href="javascript:void(0)" onclick="AltaFrase()">Crear nueva Frase</a></li>
    </ul>
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstFrases" style="width:100%;">
       <table id="listarFrases"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>