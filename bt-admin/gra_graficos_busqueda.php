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

$oGraficos = new cGraficos($conexion);

$editorId = $_GET['editorid'];

?>
<script type="text/javascript" src="modulos/gra_graficos/js/gra_graficos_busqueda.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Gr&aacute;ficos</h2>
</div>
 
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstGraficos" style="width:100%;">
    <table id="ListarGraficos"></table>
    <div id="pager2"></div>
    <input type="hidden" name="editorid" id="editorid" value="<?php  echo $editorId?>" />
</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
?>