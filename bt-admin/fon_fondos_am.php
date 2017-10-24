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

$oObjeto = new cFondos($conexion,"");

$esmodif = false;
$fondoimgubic = "";
$fondoimgnombre = "";
$fondoimgsize = "";
if (isset($_GET['fondocod']) && $_GET['fondocod']!="")
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
	$fondocod = $datosregistro["fondocod"];
	$fondodesc = $datosregistro["fondodesc"];
	$fondocte = $datosregistro["fondocte"];
	$fondoimgubic = $datosregistro["fondoimgubic"];
	$fondoimgnombre = $datosregistro["fondoimgnombre"];
	$fondoimgsize = $datosregistro["fondoimgsize"];
}
?>
<link href="modulos/fon_fondos/css/fon_fondos.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/fon_fondos/js/fon_fondos_am.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Fondo: <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fondodesc,ENT_QUOTES);?></h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="margin:10px; font-size:1.083em;">
	<strong>Nota Importante:</strong> Recuerde que la im&aacute;gen de fondo deber&aacute; ser de 1005px de ancho y aproximadamente 575px de alto.    
</div>
<div style="text-align:left;">
    <div class="ancho_5">
        <form action="fon_fondos.php" method="post" name="formalta" id="formalta" >
            <div class="ancho_1">&nbsp;</div>
			<div class="ancho_4">
                <div id="ImgFondo">
                    <?php  if (isset($datosregistro["fondoimgubic"] ) && $datosregistro["fondoimgubic"]!=""){  ?>
                        <img src="<?php  echo CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS_THUMB.$datosregistro["fondoimgubic"]."";?>" />
                    <?php  }else{ ?>
                        &nbsp;
                    <?php  }?>
        
                </div>
                <div class="clearboth">&nbsp;</div>
                <span>La imagen se guarda automaticamente</span>
				<div class="clearboth">&nbsp;</div>
                <div id="btn_subirImgMostrar" ></div> 
                    <input type="hidden" name="imagen" id="imagen" value="" />
                    <input type="hidden" name="size" id="size"  value="" />
                    <input type="hidden" name="name" id="name"  value="" />
                    <input type="hidden" name="file" id="file"  value="" />
            		<input type="hidden" name="fondocod" id="fondocod" value="<?php  echo $fondocod?>" />
            </div>
			<div class="clearboth">&nbsp;</div>

            <div class="menubarraInferior">
                <div class="menubarra">
                    <ul>
                        <li><a class="left" href="fon_fondos.php">Volver</a></li>
                        <li><a class="left" href="javascript:void(0)" onclick="Publicar()">Publicar Cambios</a></li>
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