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

?>
<link rel="stylesheet" type="text/css" href="modulos/tap_tapas/css/tap_menu_tipos.css" />
<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/age_agenda/js/agendaestados.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Estados de agenda</h2>
</div>

<div class="form">
<form action="vis_visualizaciones.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
<div class="ancho_10">
        <div class="ancho_2">
            <div class="ancho_3">
                <label>Nombre:</label>
            </div>
            <div class="ancho_6">
    			<input type="text"  name="agendaestadodesc"  id="agendaestadodesc" class="full" onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_2">
            <div class="ancho_4">
                <label>Constante:</label>
            </div>
            <div class="ancho_6">
    			<input type="text"  name="agendaestadocte"  id="agendaestadocte" class="full" onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
		<div class="clear fixalto">&nbsp;</div>
    </div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="AltaAgendaEstados()">Alta Agenda Estado</a></li>
        <li><a class="left boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar Busqueda</a></li>
    </ul>    
</div>    
<div class="clear aire_vertical">&nbsp;</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstAgendaEstados" style="width:100%;">
    <table id="ListarAgendaEstados"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
<?php  
$oEncabezados->PieMenuEmergente();
?>