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

$oNoticiasWorkflow = new cNoticiasWorkflow($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

if (isset($_POST['noticiaworkflowcod']) && $_POST['noticiaworkflowcod'])
{
	$esmodif = true;
	if (!$oNoticiasWorkflow->BuscarNoticiaWorkflowxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosworkflow = $conexion->ObtenerSiguienteRegistro($resultado);
}
$botonejecuta = "BtAlta";
$boton = "Alta";
$noticiaworkflowcod = "";
$noticiaestadocodinicial = "";
$noticiaestadocodfinal= "";
$noticiaaccion ="";
$onclick = "return InsertarWorkflow();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$noticiaworkflowcod = $datosworkflow['noticiaworkflowcod'];
	$noticiaestadocodinicial = $datosworkflow['noticiaestadocodinicial'];
	$noticiaestadocodfinal= $datosworkflow['noticiaestadocodfinal'];
	$noticiaaccion= $datosworkflow['noticiaaccion'];
	$onclick = "return ModificarWorkflow();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="form">
		<form action="mul_multimedia_formatos.php" method="post" name="formworkflow" id="formworkflow" >
			<div class="datosgenerales">
				<div>
    				<label>Estado Inicial:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
            	<?php 
					$oNoticiasEstados=new cNoticiasEstados($conexion);
					$oNoticiasEstados->NoticiasEstadosSP($spnombre,$spparam);
					FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formbusqueda","noticiaestadocodinicial","noticiaestadocod","noticiaestadodesc",$noticiaestadocodinicial,"Todos...",$regnousar,$selecnousar,1,"","","",false);
				?>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div> 
                <div>
    				<label>Estado Final:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
            	<?php 
					$oNoticiasEstados=new cNoticiasEstados($conexion);
					$oNoticiasEstados->NoticiasEstadosSP($spnombre,$spparam);
					FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formbusqueda","noticiaestadocodfinal","noticiaestadocod","noticiaestadodesc",$noticiaestadocodfinal,"Todos...",$regnousar,$selecnousar,1,"doSearch(arguments[0]||event)","","",false);
				?>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>
                <div>
    				<label>Acci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="noticiaaccion"  id="noticiaaccion" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiaaccion,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>

				<div class="clearboth aire_menor">&nbsp;</div>
				<div class="menubarra">
    				<ul>
        				<li><div class="ancho_boton aire"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></div></li>
        				<li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $noticiaworkflowcod?>" name="noticiaworkflowcod" id="noticiaworkflowcod" />
		</form>
	</div>
</div>
