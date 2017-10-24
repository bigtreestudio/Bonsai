<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oObjeto = new cFotosDia($conexion);

$oMultimedia = new cMultimedia($conexion,"noticias/");

$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$fotodiacod = "";
$multimediacod = "";
$fotodiatitulo = "";
$fotodiadesc = "";
$fotodiaestado = "";
$fotofecha = "";
if (isset($_GET['fotodiacod']) && $_GET['fotodiacod']!="")
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
	$fotodiacod = $datosregistro["fotodiacod"];
	$multimediacod = $datosregistro["multimediacod"];
	$fotodiatitulo = $datosregistro["fotodiatitulo"];
	$fotodiadesc = $datosregistro["fotodiadesc"];
	$fotodiaestado = $datosregistro["fotodiaestado"];
	$fotofecha = FuncionesPHPLocal::ConvertirFecha($datosregistro["fotofecha"],'aaaa-mm-dd','dd/mm/aaaa');
}
?>
<script type="text/javascript" src="modulos/fot_fotos_dia/js/fot_fotos_dia_am.js"></script>
	<script type="text/javascript" src="js/multimediaSelector.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Foto del dia</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div class="form">
    <form action="fot_fotos_dia.php" method="post" name="formalta" id="formalta" >
        <div class="ancho_5">
		
			<div class="ancho_2"><label for="fotodiatitulo">Titulo</label></div>
			<div class="ancho_8"><input type="text" class="full" maxlength="255" name="fotodiatitulo" id="fotodiatitulo" value="<?php  echo $fotodiatitulo?>" /></div>
			
			<div class="clearboth brisa_vertical">&nbsp;</div>

			<div class="ancho_2"><label for="fotofecha">Fecha</label></div>
			<div class="ancho_3"><input type="text" class="full fechacampo" maxlength="10" name="fotofecha"  id="fotofecha" value="<?php  echo $fotofecha?>" /></div>
			
			<div class="clearboth brisa_vertical">&nbsp;</div>

						<div class="ancho_2"><label for="fotodiadesc">Descripcion</label></div>
			<div class="ancho_8"><textarea class="full rich-text" rows="6" cols="20" name="fotodiadesc" id="fotodiadesc"><?php  echo $fotodiadesc?></textarea></div>
			
			<div class="clearboth brisa_vertical">&nbsp;</div>

            <input type="hidden" name="fotodiacod" id="fotodiacod" value="<?php  echo $fotodiacod?>" />
            <div class="menubarraInferior">
                <div class="menubarra">
                    <ul>
                        <li><a class="boton verde" href="javascript:void(0)" onclick="<?php  echo $onclick ?>">Guardar</a></li>
                        <li><a class="left boton base" href="fot_fotos_dia.php">Volver</a></li>
                    </ul>
                    <div class="clearboth">&nbsp;</div>
                </div>
                <div class="msgaccionupd">&nbsp;</div>
            </div>
            <div class="clearboth">&nbsp;</div>
        </div>
        <div class="ancho_1">&nbsp;</div>
        <div class="ancho_4">
		
			
					
					<div style="margin-bottom:10px;"><label for="multimediacod">Foto</label></div>
            <div class="menubarra">
                <ul>
                    <li><a class="left boton azul" href="javascript:void(0)" onclick="return SeleccionarMultimediaRepositorioFotos('multimediacod')">Seleccione una Im&aacute;gen</a></li>
                </ul>
                <div class="clearboth">&nbsp;</div>
            </div>
            <div class="ancho_8"><input type="hidden" name="multimediacod" id="multimediacod" value="<?php  echo $multimediacod?>" /></div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div id="multimediapreview_multimediacod">
            <?php  if ($multimediacod!=""){			
				$datosBusqueda["multimediacod"] = $multimediacod;
				if(!$oMultimedia->BuscarMultimediaxCodigo($datosBusqueda,$resultado,$numfilas))
					return false;
				$datosMultimedia = $conexion->ObtenerSiguienteRegistro($resultado);
			?>
                <img src="<?php  echo $oMultimedia->DevolverDireccionImg($datosMultimedia);?>" />
	            <?php  }?>
			</div>
					
		                <div class="txt">Recuerde <strong>guardar</strong> para que se realicen los cambios</div>
            </div>
        
        <div class="clearboth">&nbsp;</div>
    </form>
    <div class="clearboth">&nbsp;</div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();

?>