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

$oTemas = new cTemas($conexion,"");


if(isset($_POST['temacodsuperior']) && $_POST['temacodsuperior']!="")
	$temacodsuperior= $_POST['temacodsuperior'];
else
	$temacodsuperior ="";
//print_r($_POST);
if (isset($_POST['temacod']) && $_POST['temacod'])
{
	$esmodif = true;
	if (!$oTemas->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datostemas = $conexion->ObtenerSiguienteRegistro($resultado);
}
$botonejecuta = "BtAlta";
$boton = "Alta";
$temacod="";
$tematitulo = "";
$temadesc ="";
$temacolor= "#000000";
$onclick = "return InsertarTemas();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	//$titulo = "Modificación de Ciudad - ". FuncionesPHPLocal::HtmlspecialcharsBigtree($datostemas['provinciadesc'],ENT_QUOTES).")";
	$temacod = $datostemas['temacod'];
	$tematitulo = $datostemas['tematitulo'];
	$temadesc=$datostemas['temadesc'];
	$temacolor = $datostemas['temacolor'];
	$onclick = "return ModificarTemas();";
}

?>
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	$("#temacolor").miniColors();
	
});
</script>
    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="ciudades.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>T&iacute;tulo</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="tematitulo"  id="tematitulo" name="tematitulo" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($tematitulo,ENT_QUOTES)?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Color</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
            			<input type="text" value="<?php  echo $temacolor?>" id="temacolor" name="temacolor" maxlength="7" size="10" />(Hexadecimal)
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Descripci&oacute;n</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
                        <textarea name="temadesc" class="textarea full rich-text" id="temadesc" rows="5" cols="40"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($temadesc,ENT_QUOTES)?></textarea>
                    </div>
                    <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
            	<input type="hidden" name="temacodsuperior" id="temacodsuperior" value="<?php  echo $temacodsuperior?>" />
                <input type="hidden" name="temacod" id="temacod" value="<?php  echo $temacod?>" />
        
            </form>
        </div>
    </div>
