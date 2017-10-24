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


$noticiacod="";
$titulo = "Listado de comentarios";
if (isset($_GET['noticiacod']) && $_GET['noticiacod']!="")
{
	
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("noticiacod"=>$_GET['noticiacod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$noticiacod = $_GET['noticiacod'];
	
	$oNoticias = new cNoticias($conexion);
	if(!$oNoticias->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	$fila = $conexion->ObtenerSiguienteRegistro($resultado);
	$titulo = "Listado de comentarios de la noticia: ".$fila['noticiatitulo'];
}
?>
<link rel="stylesheet" type="text/css" href="modulos/not_comentarios/css/not_comentarios.css" />
<script type="text/javascript" src="modulos/not_comentarios/js/not_comentarios.js"></script>
<div class="form">
<form action="not_comentarios.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="inner-page-title" style="padding-bottom:2px;">
        <h2><?php  echo $titulo?> </h2>
    </div>
    <div class="ancho_10">
    	<div class="ancho_1">&nbsp;</div>
		<div class="ancho_3">
			<div class="ancho_4">
				<label>Nombre:</label>
			</div>
			<div class="ancho_6">
				<input name="comentarionombre" id="comentarionombre" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="255" size="60" value="" />
			</div>
		</div>
		<div class="ancho_1">&nbsp;</div>
		<div class="ancho_1">&nbsp;</div>
		<div class="ancho_3">
			<div class="ancho_4">
				<label>Email:</label>
			</div>
			<div class="ancho_6">
				<input name="comentarioemail" id="comentarioemail" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="50" size="60" value="" />
			</div>
		</div>
		<div class="clearboth">&nbsp;</div>	</div>
        <?php  if($noticiacod==""){?>
        <div class="ancho_1">&nbsp;</div>
        <div class="ancho_3">
            <div class="ancho_4">
                <label>Titulo Noticia:</label>
            </div>
            <div class="ancho_6">
               <input name="noticiatitulo" id="noticiatitulo" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="255" size="60" value="" />
            </div>
        </div>
     	<div class="clearboth">&nbsp;</div>	
        <?php  }?>
        <input type="hidden" name="noticiacod" id="noticiacod" value="<?php  echo $noticiacod?>" />
</form>    


<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
    	<?php  /*<li><a class="left" href="not_comentarios_am.php?noticiacod=<?php  echo $noticiacod?>" >Crear nuevo Comentario</a></li> */?>
    	<li><a class="left boton azul" href="javascript:void(0)" onclick="Resetear()">Limpiar</a></li>
       <?php  if($noticiacod!=""){?>
        <li><a class="left boton base" href="not_noticias.php" >Volver</a></li>
        <?php  } ?>
    </ul>
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstDatos" style="width:100%;">
       <table id="listarDatos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();

?>