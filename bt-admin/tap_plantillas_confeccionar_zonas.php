<?php  
ob_start();
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

$oMacros= new cMacros($conexion);
header('Content-Type: text/html; charset=iso-8859-1'); 

/*
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("tapacod"=>$_GET['tapacod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	ob_clean();
	header("Location:tap_tapas_confeccionar_error.php");
	die();
}
*/
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$datos = $_POST;

?>
<div class="form">
<div style="text-align:left;">
	<div>
        <div style="float:left; width:80px;">
            <label>Macro:</label>
        </div>
		<div  style="float:left;">
        	<?php 
                $oMacros->MacrosSP($spnombre,$sparam);
                FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formbusqueda","macrocod","macrocod","macrodesc","","Seleccione un macro...",$regactual,$seleccionado,1,"CargarMacro(".$datos['plantcod'].")",false,false);
            ?>
        </div>
        <div class="clearboth">&nbsp;</div>
		<div id="CargaMacro" style="margin-top:50px;">
        </div>
	</div>
</div>   
</div> 