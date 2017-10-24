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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
$volver= "vis_visualizaciones.php"; 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/vis_visualizaciones/js/vis_visualizaciones.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Visualizaciones</h2>
</div>
    
<div class="form">
<form action="vis_visualizaciones.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
<div class="ancho_10">
        <div class="ancho_3">
            <div class="ancho_3">
                <label>Descripci&oacute;n:</label>
            </div>
            <div class="ancho_6">
               <input name="visualizaciondesc" id="visualizaciondesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
		<div class="ancho_3">
            <div class="ancho_5">
                <label>Tipo Visualizacion:</label>
            </div>
            <div class="ancho_5">
                  	<?php 	
						$oVisualizacionesTipos=new cVisualizacionesTipos($conexion);
						$oVisualizacionesTipos->VisualizacionTiposSP($spnombre,$spparam);
						FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formbusqueda","visualizaciontipocod","visualizaciontipocod","visualizacionnombre","","Seleccione un tipo",$regnousar,$selecnousar,1,"doSearch(arguments[0]||event)","","",false);
					?>

            </div>
        </div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
</form>
 </div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="AltaVisualizacion()">Agregar visualizaci&oacute;n</a></div></li>
        <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar Busqueda</a></div></li>
    </ul>    
</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstVisualizaciones" style="width:100%;">
    <table id="ListarVisualizaciones"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
<?php  
$oEncabezados->PieMenuEmergente();
?>