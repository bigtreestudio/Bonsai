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

$oMacrosEstructuras = new cMacrosEstructuras($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1'); 

$oPlantillasMacrosZonas = new cPlantillasMacrosZonas($conexion);
$oMacrosColumnas = new cMacrosColumnas($conexion);
if(!$oPlantillasMacrosZonas->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;

$datos = $conexion->ObtenerSiguienteRegistro($resultado);

$oPlantillasCss = new cPlantillasCss($conexion);
$oPlantillasCss->SpEstilosColumnaCSS($_POST,$spnombre,$sparam);


?>
<div class="form">
<form action="javascript:void(0)" method="post" name="formcolumnaagregar"  class="general_form" id="formcolumnaagregar" >
<div style="text-align:left;">
	<div>
        <div style="float:left; width:80px;">
            <label>Columnas:</label>
        </div>
		<div  style="float:left;">
        	<?php 
				$oMacrosColumnas->ColumnasSP($datos,$spnombre,$sparam);
                FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formbusqueda","columnacod","columnacod","columnadesc","","Seleccione la cantidad de columnas...",$regactual,$seleccionado,1,"",false,false);
            ?>
        </div>
        <div class="clearboth">&nbsp;</div>
		<div style="margin-top:50px; text-align:center">
        	<input type="button" name="Agregar" class="boton verde" value="Agregar" onclick="AgregarColumna(<?php  echo $datos['macrozonacod']?>)" />
            <input type="hidden" name="macrozonacod" value="<?php  echo $datos['macrozonacod']?>" />
        </div>
	</div>
</div>    
</form>
</div>
<?php  

?>