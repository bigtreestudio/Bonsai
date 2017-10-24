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

$oEncabezados = new cEncabezados($conexion);
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
die();
$ga->requestAccountData();
?>
<div>
	
        <form action="goo_analytics_lst_ajax.php" method="post" name="formprofilesanalytics" id="formprofilesanalytics" >
           <table cellpadding="3" cellspacing="2">
            <?php  foreach($ga->getResults() as $result){?>
                  <tr style="height:30px;">
                       <td style="width:20%"> <input type="radio" value="<?php  echo $result->getProfileId()?>" <?php  if($result->getProfileId()==$datosanalytics['googleprofile']) echo 'checked="checked"'?>  name="googleprofile" id="profile_<?php  echo $result->getProfileId()?>" /></td>
                       <td style="width:80%"><label for="profile_<?php  echo $result->getProfileId()?>" style="font-size:16px;"><?php  echo $result?></label></td>
                  </tr>
            <?php  } ?>
            </table>
             
            <input type="hidden" name="accion" id="accion" value="2" />
            <input type="hidden" name="googlecod" id="googlecod" value="<?php  echo $googlecod?>" />
            <input type="hidden" name="googleprofilename" id="googleprofilename" value="" />
             <div class="clearboth aire">&nbsp;</div>
           <div class="menubarra">
                <ul>
                 <li><a class="left" name="guardardatos"  href="javascript:void(0)" onclick="ActualizarProfile()">Guardar Profiles</a></li>
                </ul>
   			</div>
        </form>
    
     <div class="clearboth aire">&nbsp;</div>
	
</div>