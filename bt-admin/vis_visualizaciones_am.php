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

$oVisualizaciones = new cVisualizaciones($conexion,"");

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['visualizacioncod']) && $_POST['visualizacioncod'])
{
	$esmodif = true;
	if (!$oVisualizaciones->BuscarVisualizacionxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosvisualizacion = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$visualizacioncod = "";
$visualizaciontipocod = "";
$visualizaciondesc= "";
$onclick = "return InsertarVisualizacion();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$visualizacioncod = $datosvisualizacion['visualizacioncod'];
	$visualizaciontipocod = $datosvisualizacion['visualizaciontipocod'];
	$visualizaciondesc= $datosvisualizacion['visualizaciondesc'];
	$onclick = "return ModificarVisualizacion();";
}

?>

<div style="text-align:left">
	<div class="form">
		<form action="mul_multimedia_formatos.php" method="post" name="formvisualizacion" id="formvisualizacion" >
						<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="visualizaciondesc"  id="visualizaciondesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($visualizaciondesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
                <div>
    				<label>Tipo de Visualizaci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<?php 	
						$oVisualizacionesTipos=new cVisualizacionesTipos($conexion);
						$oVisualizacionesTipos->VisualizacionTiposSP($spnombre,$spparam);
						FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formvisualizacion","visualizaciontipocod","visualizaciontipocod","visualizacionnombre",$visualizaciontipocod,"Seleccione un tipo",$regnousar,$selecnousar,1,"","","",false);
					?>

    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><div class="ancho_boton aire"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></div></li>
        				<li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $visualizacioncod?>" name="visualizacioncod" id="visualizacioncod" />
		</form>
	</div>
</div>
