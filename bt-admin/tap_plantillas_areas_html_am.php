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

$oPlantillasAreasHtml = new cPlantillasAreasHtml($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

if (isset($_POST['areahtmlcod']) && $_POST['areahtmlcod'])
{
	$esmodif = true;
	if (!$oPlantillasAreasHtml->TraerAreasHtmlxCodigo($_POST,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosarea = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$areahtmlcod = "";
$areahtmldesc = "";
$areahtmlinicio= "";
$areahtmlfin= "";
$onclick = "return Insertar();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$areahtmlcod = $datosarea['areahtmlcod'];
	$areahtmldesc = $datosarea['areahtmldesc'];
	$areahtmlinicio= $datosarea['areahtmlinicio'];
	$areahtmlfin= $datosarea['areahtmlfin'];
	$onclick = "return Modificar();";
}

?>
<div style="text-align:left">
	<div class="form">
		<form action="age_agenda_estados_am.php" method="post" name="formareahtml" id="formareahtml" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="areahtmldesc"  id="areahtmldesc" maxlength="50" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($areahtmldesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>HTML Inicio:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<textarea name="areahtmlinicio" id="areahtmlinicio" rows="10" cols="50" style="width:90%;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($areahtmlinicio,ENT_QUOTES);?></textarea>
    			</div>
                <div class="clearboth aire_menor">&nbsp;</div>  
                <div>
                	<label>HTML Fin</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
         			<textarea name="areahtmlfin" id="areahtmlfin" rows="10" cols="50" style="width:90%;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($areahtmlfin,ENT_QUOTES);?></textarea>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
    
				<div class="menubarra">
    				<ul>
        				<li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $areahtmlcod?>" name="areahtmlcod" id="areahtmlcod" />
		</form>
	</div>
</div>
