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

$oGraficos = new cGraficos($conexion);

?>
<script type="text/javascript" src="modulos/gra_graficos/js/gra_graficos.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Gr&aacute;ficos</h2>
</div>
<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="AltaGrafico(<?php  echo GRAFPORCENTAJES?>)" title="Crear nuevo gr&aacute;fico">Crear nuevo gr&aacute;fico de un eje</a></li>
        <li><a class="left boton verde" href="javascript:void(0)" onclick="AltaGrafico(<?php  echo GRAFVALORES?>)" title="Crear nuevo gr&aacute;fico">Crear nuevo gr&aacute;fico de 2 ejes</a></li>
    </ul>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstGraficos" style="width:100%;">
    <table id="ListarGraficos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
?>