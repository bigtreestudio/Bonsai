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

$oTapasTiposMetadataCampos = new cTapasTiposMetadataCampos($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

if (isset($_POST['tapatipometadatacod']) && $_POST['tapatipometadatacod'])
{
	$esmodif = true;
	if (!$oTapasTiposMetadataCampos->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datostiposmetadatacampos = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$tapatipometadatacod = "";
$tapatipometadatacampo = "";
$tapatipometadataestado= "";
$tapatipometadatacte= "";
$onclick = "return Insertar();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$tapatipometadatacod = $datostiposmetadatacampos['tapatipometadatacod'];
	$tapatipometadatacampo = $datostiposmetadatacampos['tapatipometadatacampo'];
	$tapatipometadataestado= $datostiposmetadatacampos['tapatipometadataestado'];
	$tapatipometadatacte= $datostiposmetadatacampos['tapatipometadatacte'];
	$onclick = "return Modificar();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="form">
		<form action="mul_multimedia_formatos.php" method="post" name="formtipometadatacampo" id="formtipometadatacampo" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="tapatipometadatacampo"  id="tapatipometadatacampo" maxlength="50" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($tapatipometadatacampo,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Constante:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="tapatipometadatacte" id="tapatipometadatacte" maxlength="20" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($tapatipometadatacte,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $tapatipometadatacod?>" name="tapatipometadatacod" id="tapatipometadatacod" />
		</form>
	</div>
</div>
