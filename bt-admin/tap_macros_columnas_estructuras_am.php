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

$oMacrosColumnasEstructuras = new cMacrosColumnasEstructuras($conexion,"");

$esmodif = false;
header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset($_POST['colestructuracod']) && $_POST['colestructuracod'])
{
	$esmodif = true;
	if (!$oMacrosColumnasEstructuras->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosmacrocoles = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$columnacod = $_POST['columnacod'];
$colestructuracod= "";
$colestructuradesc = "";
$colestructuraclass = "";
$onclick = "return InsertarMacroColEstructura();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$columnacod = $datosmacrocoles['columnacod'];
	$colestructuracod= $datosmacrocoles['colestructuracod'];
	$colestructuradesc = $datosmacrocoles['colestructuradesc'];
	$colestructuraclass = $datosmacrocoles['colestructuraclass'];
	$onclick = "return ModificarMacroColEstructura();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="aire_vertical ">
		<form action="tap_macros_estructuras_am.php" method="post" name="formmacrocolestructura" id="formmacrocolestructura" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="colestructuradesc"  id="colestructuradesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($colestructuradesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
                <div>
    				<label>Class:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="colestructuraclass"  id="colestructuraclass" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($colestructuraclass,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="left" href="javascript:void(0)"  onclick="DialogCloseColEs()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $columnacod?>" name="columnacod" id="columnacod" />
            <input type="hidden" value="<?php  echo $colestructuracod?>" name="colestructuracod" id="colestructuracod" />
		</form>
	</div>
</div>
