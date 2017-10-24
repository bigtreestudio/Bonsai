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

$oBannersTipos = new cBannersTipos($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['bannertipocod']) && $_POST['bannertipocod'])
{
	$esmodif = true;
	if (!$oBannersTipos->BuscarBannerTipoxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosbannerstipo = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$bannertipocod = "";
$bannertipodesc = "";
$bannerancho= "";
$banneralto= "";
$onclick = "return InsertarBannersTipos();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$bannertipocod = $datosbannerstipo['bannertipocod'];
	$bannertipodesc = $datosbannerstipo['bannertipodesc'];
	$bannerancho= $datosbannerstipo['bannerancho'];
	$banneralto= $datosbannerstipo['banneralto'];
	$onclick = "return ModificarBannersTipos();";
}

?>
<div style="text-align:left">
	<div class="form">
		<form action="mul_multimedia_formatos.php" method="post" name="formmultformato" id="formmultformato" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="bannertipodesc"  id="bannertipodesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($bannertipodesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Ancho:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="bannerancho" id="bannerancho" maxlength="80" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($bannerancho,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div> 
                <div>
    				<label>Alto:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="banneralto" id="banneralto" maxlength="80" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($banneralto,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div class="menubarra">
    				<ul>
        				<li><div class="ancho_boton aire"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></div></li>
        				<li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $bannertipocod?>" name="bannertipocod" id="bannertipocod" />
		</form>
	</div>
</div>
