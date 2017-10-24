<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oObjeto = new cNoticiasComentarios($conexion,"");

$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$comentariocod = "";
$noticiacod = "";
$comentarionombre = "";
$comentarioemail = "";
$comentariodesc = "";
$comentarioestado = "";
$comentariofalta =  "";
$volvernoticia = false;
if (isset($_GET['noticiacod']) && $_GET['noticiacod']!="")
{
	$noticiacod = $_GET["noticiacod"];
}
if (isset($_GET['comentariocod']) && $_GET['comentariocod']!="")
{
	$esmodif = true;
	$datos = $_GET;
	if(!$oObjeto->BuscarxCodigo($datos,$resultado,$numfilas))
		return false;
	if($numfilas!=1){
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Codigo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosregistro = $conexion->ObtenerSiguienteRegistro($resultado);
	$onclick = "return Modificar();";
	$comentariocod = $datosregistro["comentariocod"];
	$noticiacod = $datosregistro["noticiacod"];
	$comentarionombre = $datosregistro["comentarionombre"];
	$comentarioemail = $datosregistro["comentarioemail"];
	$comentariodesc = $datosregistro["comentariodesc"];
	$comentarioestado = $datosregistro["comentarioestado"];
	$comentariofalta = $datosregistro["comentariofalta"];
}
$volver = "not_comentarios.php";
?>
<script type="text/javascript" src="modulos/not_comentarios/js/not_comentarios_am.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Comentario</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
    <div class="form ancho_5">
        <form action="not_comentarios.php" method="post" name="formalta" id="formalta" >
        
			<div class="ancho_2"><label for="comentarionombre">Nombre: </label></div>
			<div class="ancho_8"><input type="text" class="full" maxlength="255" name="comentarionombre" id="comentarionombre" value="<?php  echo $comentarionombre?>" /></div>
			
			<div class="clearboth brisa_vertical">&nbsp;</div>

			<div class="ancho_2"><label for="comentarioemail">Email: </label></div>
			<div class="ancho_8"><input type="text" class="full" maxlength="50" name="comentarioemail" id="comentarioemail" value="<?php  echo $comentarioemail?>" /></div>
			
			<div class="clearboth brisa_vertical">&nbsp;</div>

			<div class="ancho_2"><label for="comentariodesc">Comentario: </label></div>
			<div class="ancho_8"><textarea class="full" rows="6" cols="20" name="comentariodesc" id="comentariodesc"><?php  echo $comentariodesc?></textarea></div>
			
			<div class="clearboth brisa_vertical">&nbsp;</div>

            <input type="hidden" name="comentariocod" id="comentariocod" value="<?php  echo $comentariocod?>" />
            <input type="hidden" name="noticiacod" id="noticiacod" value="<?php  echo $noticiacod?>" />
 			<input type="hidden" name="comentariofalta" id="comentariofalta" value="<?php  echo $comentariofalta?>" />            <div class="menubarraInferior">
                <div class="menubarra">
                    <ul>
                        <li><a class="left boton verde" href="javascript:void(0)" onclick="<?php  echo $onclick ?>">Guardar</a></li>
                        <li><a class="left boton base" href="<?php  echo $volver?>">Volver</a></li>
                    </ul>
                    <div class="clearboth">&nbsp;</div>
                </div>
                <div class="msgaccionupd">&nbsp;</div>
            </div>
            <div class="clearboth">&nbsp;</div>
        </form>
	</div>
    <div class="clearboth">&nbsp;</div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();

?>