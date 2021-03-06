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

// ve si el sistema est� bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oContactos= new cContactos($conexion);

$_SESSION['msgactualizacion'] = "";

?>
<link rel="stylesheet" type="text/css" href="modulos/con_contactos/css/estilos.css" />
<script type="text/javascript" src="modulos/con_contactos/js/con_contactos.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Contactos</h2>
</div>
 
<div class="form">
<form action="con_contactos_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4" style="margin-top:5px">
                    <label>Nombre:</label>
                </div>
                <div class="ancho_6">
                   <input name="formulariotipotitulo" id="formulariotipotitulo" class="full" type="text"  onkeydown="doSearchFormContacto(arguments[0]||event)" maxlength="100" size="60" value="" />
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
        <li><a class="left boton verde" href="con_contactos_am.php">Crear nuevo Formulario</a></li>
    </ul>    
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstContactos" style="width:100%;">
    <table id="listarContactos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div> 

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>