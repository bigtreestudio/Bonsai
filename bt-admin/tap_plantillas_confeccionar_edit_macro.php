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

//$oMacrosEstructuras = new cMacrosEstructuras($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1'); 

$oPlantillasMacros = new cPlantillasMacros($conexion);
$datos['plantmacrocod'] = $plantmacrocod = $_POST['plantmacrocod'];
if(!$oPlantillasMacros -> BuscarxCodigo($datos,$resultado,$numfilas))
	return false;
	
if ($numfilas!=1)
	die();
		
$datosmacro = $conexion->ObtenerSiguienteRegistro($resultado);	
$Class="";
if ($datosmacro['plantmacrodatos']!="")
{
	$objDataModel = json_decode($datosmacro['plantmacrodatos']);
	if (isset($objDataModel->Class) && $objDataModel->Class!="")
		$Class = $objDataModel->Class;
}	
	
$oPlantillasCss = new cPlantillasCss($conexion);
$oPlantillasCss->SpEstilosMacroCSS($_POST,$spnombre,$sparam);
?>
<div class="form">
<form action="javascript:void(0)" method="post" name="formmacro"  class="general_form" id="formmacro" >

<div style="text-align:left;">
	<div>
        <div style="float:left; width:80px;">
            <label>Estilo:</label>
        </div>
		<div  style="float:left;">
        	<?php  
				FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","Class","cssclass","cssdesc",$Class,"Sin Estilo",$filaClass,$selecnousar,1,"");
			?>
        </div>
        <div class="clearboth">&nbsp;</div>
		<div style="margin-top:50px; text-align:center">
        	<input type="button" name="Guardar" class="boton"  value="Guardar" onclick="GuardarDatosMacro(<?php  echo $datos['plantmacrocod']?>)" />
            <input type="hidden" name="plantmacrocod" value="<?php  echo $datos['plantmacrocod']?>" />
        </div>
	</div>
</div>    
</form>
</div>
<?php  

?>