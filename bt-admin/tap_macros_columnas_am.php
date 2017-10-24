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

$oMacrosColumnas = new cMacrosColumnas($conexion,"");

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

if (isset($_POST['columnacod']) && $_POST['columnacod'])
{
	$esmodif = true;
	if (!$oMacrosColumnas->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosmacrocol = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$estructuracod = $_POST['estructuracod'];
$columnacod = "";
$columnadesc = "";
$onclick = "return InsertarMacrosColumna();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$estructuracod = $datosmacrocol['estructuracod'];
	$columnacod = $datosmacrocol['columnacod'];
	$columnadesc = $datosmacrocol['columnadesc'];
	$onclick = "return ModificarMacrosColumna();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="aire_vertical ">
		<form action="tap_macros_am.php" method="post" name="formmacrocolumna" id="formmacrocolumna" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="columnadesc"  id="columnadesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($columnadesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="left" href="javascript:void(0)"  onclick="DialogCloseMacrosCol()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $columnacod?>" name="columnacod" id="columnacod" />
            <input type="hidden" value="<?php  echo $estructuracod?>" name="estructuracod" id="estructuracod" />
		</form>
	</div>
</div>
