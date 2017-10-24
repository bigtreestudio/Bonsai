<?php  
require('./config/include.php');
require('./Librerias/cGoogleAnalytics.php');

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

$oGoogle = new cGoogle($conexion);
$datos['googlecod'] = $googlecod =1;
if (!$oGoogle ->Buscar($datos,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
$datosanalytics = $conexion->ObtenerSiguienteRegistro($resultado);
$datosanalytics['googlepass'] = FuncionesPHPLocal::DesencriptarFrase($datosanalytics['googlepass'],CLAVEENCRIPTACION);
$ga = new gapi($datosanalytics['googleuser'],$datosanalytics['googlepass']);

$ga->requestReportData($datosanalytics['googleprofile'],array('browser'),array('pageviews','visits'),array('-visits'),NULL,NULL,NULL,1,10);
$fecha = date("d/m/Y H:i\H\s.",strtotime($ga->getUpdated()));
?>

<div class="content-box">
    <div class="two-column">
        <div class="column">
            <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                <div class="portlet-header ui-widget-header">Visitas al <?php  echo $fecha; ?></div>
                <div class="portlet-content">
                    <?php  
                    ?>
                    <table class="data">
                        <tr>
                          <th style="width:40%">Navegador</th>
                          <th style="width:30%">P&aacute;ginas vistas</th>
                          <th style="width:30%">Visitas</th>
                        </tr>
                        <?php 
                        foreach($ga->getResults() as $result):
                        ?>
                        <tr>
                          <td><?php  echo $result ?></td>
                          <td><?php  echo $result->getPageviews() ?></td>
                          <td><?php  echo $result->getVisits() ?></td>
                        </tr>
                        <?php 
                        endforeach
                        ?>
                    </table>
                    <br />
                    <table class="data">
                        <tr>
                          <th>Totales P&aacute;ginas Vistas</th>
                          <td><?php  echo $ga->getPageviews() ?>
                        </tr>
                        <tr>
                          <th>Total Visitas</th>
                          <td><?php  echo $ga->getVisits() ?></td>
                        </tr>
                    </table>
                        
                </div>
            </div>
        </div>
        <div class="column column-right">
            <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
                <div class="portlet-header ui-widget-header">P&aacute;ginas m&aacute;s vistas  al <?php  echo $fecha; ?></div>
                <div class="portlet-content">
                    <?php  
                    $ga->requestReportData($datosanalytics['googleprofile'],array('pagepath'),array('pageviews'),array('-pageviews'),NULL,NULL,NULL,1,10);
                    ?>
                    <br />
                    <table class="data">
                        <tr>
                          <th style="width:60%">P&aacute;gina</th>
                          <th style="width:40%">Visitas</th>
                        </tr>
                        <?php 
                        foreach($ga->getResults() as $result):
                        ?>
                        <tr>
                          <td title="<?php  echo $result?>"><?php  echo substr($result,0,100) ?></td>
                          <td><?php  echo $result->getPageviews() ?></td>
                        </tr>
                        <?php 
                        endforeach
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>        
<?php  ?>