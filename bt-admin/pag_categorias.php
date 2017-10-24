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

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oPaginasCategorias = new cPaginasCategorias($conexion,"");



$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$catcod = "";
$nivelcatdesc = "Inicio";
$catsup = "";
$catsuperior = "";

if (isset($_GET['catsuperior']))
{	
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("catcod"=>$_GET['catsuperior']),$get,$md5);
	//FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_GET['albumsuperior']),$get,$md5);
	if(!isset($_GET["md5"]) || $_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$catsuperior= $_GET['catsuperior'];
	
	$datoscat['catcod'] = $catcod = $catsuperior;
	if (!$oPaginasCategorias->BuscarxCodigo($datoscat,$resultado,$numfilas))
		return false;
	if($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error no existe la categoria de la p&aacute;gina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}	
	$datoscategoria = $conexion->ObtenerSiguienteRegistro($resultado);	
	$nivelcatdesc = $datoscategoria['catdesc'];
	$catsup = "?catsuperior=".$catcod."&md5=".$md5;
}

if (!$oPaginasCategorias->ArregloHijos($catcod,$arrcat,$cantidadarreglo))
	return false;


$_SESSION['msgactualizacion'] = "";
$_SESSION['volver'] = "pag_categorias.php".$catsup;
$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

?>

<link rel="stylesheet" type="text/css" href="modulos/pag_paginas/css/categorias.css" />
<script type="text/javascript" src="modulos/pag_paginas/js/categorias.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Categorias</h2>
</div>
 
<div class="txt_izq">
     <form action="gal_albums.php" method="post" name="formbusqueda" id="formbusqueda">
		<input type="hidden" name="catsuperior" id="catsuperior" value="<?php  echo $catcod ?>" />
        <input type="hidden" name="catestado" id="catestado" value="<?php  echo ACTIVO.",".NOACTIVO ?>" />
	</form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="AltaPagCat('<?php  echo $catsuperior ?>')">Crear nueva categoria</a></li>
    </ul>
</div>
<?php  
	$oPaginasCategorias->MostrarJerarquia($catcod,$jerarquia,$nivel);
	print_r ($jerarquia);
?>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstPaginasCategorias" style="width:100%;">
    <table id="ListarPaginasCategorias"></table>
    <div id="pager2"></div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>