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

$oPlantillas = new cPlantillas($conexion,"");

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['plantcod']) && $_POST['plantcod'])
{
	$esmodif = true;
	if (!$oPlantillas->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosplantilla = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$plantcod = "";
$plantdesc = "";
$planthtmlcod = "";
$onclick = "return InsertarPlantilla();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$plantcod = $datosplantilla['plantcod'];
	$plantdesc = $datosplantilla['plantdesc'];
	$planthtmlcod = $datosplantilla['planthtmlcod'];
	$onclick = "return ModificarPlantilla();";
}

?>
<div style="text-align:left">
	<div class="form">
		<form action="tap_plantillas_am.php" method="post" name="formplantilla" id="formplantilla" onsubmit="return false;">
			<div class="datosgenerales">
				<div>
    				<label>Html General de Plantilla:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
					 <?php  
					 	if (!$esmodif)
						{
							$PlantillasHtml = new cPlantillasHtml($conexion);
							$PlantillasHtml->PlantillasHtmlSP($spnombre,$sparam);
							FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","planthtmlcod","planthtmlcod","planthtmldesc",$planthtmlcod,"Seleccione un html...",$regactual,$seleccionado,1,"",false,false);
						}else
						{
							echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosplantilla['planthtmldesc'],ENT_QUOTES);	
						}
                     ?>                     
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="plantdesc"  id="macrodesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($plantdesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div class="menubarra">
    				<ul>
        				<li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="boton base" href="javascript:void(0)"  onclick="DialogClosePlantilla()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $plantcod?>" name="plantcod" id="plantcod" />
		</form>
	</div>
</div>
