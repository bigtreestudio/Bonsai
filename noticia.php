<?php  
session_start();
ob_start();
include("./config/include.php");
include(DIR_CLASES."cNoticias.class.php");
include(DIR_CLASES."cGalerias.class.php");
include(DIR_CLASES."cNoticiasCategorias.class.php");
include(DIR_CLASES."cMultimedia.class.php");
include(DIR_CLASES."cNoticiasEstadisticas.class.php");
include(DIR_CLASES."cNoticiasComentarios.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);
$oCategorias = new cNoticiasCategorias($conexion);
$oNoticia = new cNoticias($conexion);
$oMultimedia = new Multimedia();

if (!isset($_GET['codigo']) || $_GET['codigo']=='' || strlen($_GET['codigo'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['codigo'],"NumericoEntero"))
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
if (!isset($_GET['folder']) || $_GET['folder']=='' || strlen($_GET['folder'])>6 || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['folder'],"NumericoEntero"))
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
	
$datos['noticiacod'] = $_GET['codigo'];
$folder = $_GET['folder'];

if(isset($_POST[session_name()]))
{
	setcookie(session_name(),$_POST[session_name()],0,"/");
	// arma las variables de sesion y verifica si se tiene permisos
	FuncionesPHPLocal::ArmarLinkMD5Front(basename($_SERVER['PHP_SELF']),array("codigo"=>$_GET['codigo'],session_name()=>$_POST[session_name()]),$getPrevisualizar,$md5Prev);
	if ($_GET['md5']!=$md5Prev)
	{	
		ob_clean();
		FuncionesPHPLocal::Error404();
		die();
	}
	//busco la noticia en la base de datos
	$datosNoticia = $oNoticia->BuscarNoticiaPrevisualizacion($datos);
}else
{
	$datosNoticia = $oNoticia->BuscarNoticia($datos, $folder);
	$oNoticiasEstadisticas = new cNoticiasEstadisticas($conexion);
	$oNoticiasEstadisticas->SumarCantidad($datos);
}

if ($datosNoticia===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
$datosNoticia=FuncionesPHPLocal::ConvertiraUtf8($datosNoticia);
$imagenes=array();
if (isset($datosNoticia["multimedias"]["fotos"]) && count($datosNoticia["multimedias"]["fotos"])>0)
{
	$imagenes=$datosNoticia["multimedias"]["fotos"];
}

//videos
$videos=array();
if (isset($datosNoticia["multimedias"]["videos"]) && count($datosNoticia["multimedias"]["videos"])>0)
{
	$videos=$datosNoticia["multimedias"]["videos"];
}

//archivos
$archivos=array();
if (isset($datosNoticia["multimedias"]["archivos"]) && count($datosNoticia["multimedias"]["archivos"])>0)
{
	$archivos=$datosNoticia["multimedias"]["archivos"];
}


//si la categoria tiene un menu asignado , lo marco en el encabezado
$oCategoria=new cNoticiasCategorias($conexion);
$datosCategoria=$oCategoria->BuscarCategoriaxCategoria($datosNoticia);
$datosCategoria=FuncionesPHPLocal::ConvertiraUtf8($datosCategoria);
if (isset($datosCategoria["menucod"]) && $datosCategoria["menucod"]!="")
{
	$oEncabezados->setMenu($datosCategoria["menucod"]);
	$datosMenuBusqueda['menucod'] = $datosCategoria["menucod"];
	$datosMenuBusqueda['menutipocod'] = $datosCategoria["menutipocod"];
	
	$oMenu = new cMenu($conexion);
	$oMenu->BuscarxCodigo($datosMenuBusqueda,$resultado,$numfilas);
	if ($numfilas>0)
	{
		$datosMenu = $conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosMenu['menucodsup']!="")
			$oEncabezados->setMenu($datosMenu['menucodsup']);
	}
}

//marco la imagen de FB en el encabezado
$oEncabezados->setOgImage(DOMINIOGENERAL."/public/cms/imagenes/logo.png");
if (count($imagenes)>0)
{
	$tieneimg = true;
	$imagen = current($imagenes);
	$imgurl=DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic(600, 315, $imagen["url"],1,true);
	$oEncabezados->setOgImage(DOMINIOPORTAL.$imgurl);
} 

$oEncabezados->setPlantilla($datosCategoria["planthtmlcod"]);
$oEncabezados->setTitle(utf8_decode($datosNoticia["noticiatitulo"]));
$oEncabezados->setKeywords($datosNoticia["noticiatags"]);
$oEncabezados->setDescription(substr(strip_tags($datosNoticia["noticiacopete"]),0,255));
$oEncabezados->setOgTitle(utf8_decode($datosNoticia["noticiatitulo"]));
$oEncabezados->setOgUrl(DOMINIOGENERAL.$datosNoticia["catdominio"]."/".$datosNoticia["noticiaurl"]);
$oEncabezados->setOgDescription(substr(strip_tags($datosNoticia["noticiacopete"]),0,255));
$oEncabezados->EncabezadoMenuEmergente();

?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/maps/googlemaps.js"></script>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/noticia.js"></script>

<?php  

if (isset($datosextra->visualizacioncod) && $datosextra->visualizacioncod!="")
	include("visualizaciones/noticias/noticia_".$datosextra->visualizacioncod.".php");
else
	include("visualizaciones/noticias/noticia_1.php");


$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>