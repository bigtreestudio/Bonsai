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


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/tap_plantillas/js/tap_plantillas_html.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Plantillas HTML</h2>
</div>

<div class="form">
<form action="tap_plantillas_html.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
    <div class="ancho_10">
        <div class="ancho_3">
            <div class="ancho_4">
                <label>Descripci&oacute;n:</label>
            </div>
            <div class="ancho_6">
               <input name="planthtmldesc" id="planthtmldesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
</form>
 </div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="tap_plantillas_html_am.php">Crear nuevo plantilla HTML</a></li>
		<li><a class="left boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar B&uacute;squeda</a></li>
    
    </ul>    
</div>

         		
<div id="LstPlantillas" style="width:100%;">
    <table id="ListarPlantillas"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
$oEncabezados->PieMenuEmergente();
?>