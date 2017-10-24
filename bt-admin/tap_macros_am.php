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

$oMacros = new cMacros($conexion,"");

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['macrocod']) && $_POST['macrocod'])
{
	$esmodif = true;
	if (!$oMacros->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosmacro = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$macrocod = "";
$macrodesc = "";
$onclick = "return InsertarMacro();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$macrocod = $datosmacro['macrocod'];
	$macrodesc = $datosmacro['macrodesc'];
	$onclick = "return ModificarMacro();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="form">
		<form action="tap_macros_am.php" method="post" name="formmacro" id="formmacro" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="macrodesc"  id="macrodesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($macrodesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="boton base left" href="javascript:void(0)"  onclick="DialogCloseMacros()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $macrocod?>" name="macrocod" id="macrocod" />
		</form>
	</div>
</div>
