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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$oPaginas = new cPaginasPublicacion($conexion,"");

$pagcod = "";
$pagtitulo = "";
$catcod = "";
$pagestadocod = "";
$pagcodsuperior="";
	
if (!isset($_GET['catcod']) || $_GET['catcod']=='' || strlen($_GET['catcod'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['catcod'],"NumericoEntero"))
	die();
	
	
$catcod = $_GET['catcod'];
$url = "?catcod=".$catcod;
$mostrarvolver = false;
if (isset($_GET['pagcodsuperior']) && $_GET['pagcodsuperior']!=""){
	
	if (strlen($_GET['pagcodsuperior'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['pagcodsuperior'],"NumericoEntero"))
		die();
		
	$pagcodsuperior = $_GET['pagcodsuperior'];
	$datospagina['pagcod'] = $_GET['pagcodsuperior'];
	if(!$oPaginas->EsPaginaPublicada($datospagina,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		$datosencontrados = $conexion->ObtenerSiguienteRegistro($resultado);
		$url .= "?pagcodsuperior=".$datosencontrados['pagcodsuperior'];
		
	}
	$mostrarvolver = true;
}
	

	
?>
<link rel="stylesheet" type="text/css" href="modulos/pag_paginas/css/paginas.css" />
<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/pag_paginas/js/paginas_orden.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Ordenamiento de P&aacute;ginas</h2>
</div>
<p>
	<b>Nota importante:</b> Recuerde que cualquier cambio de orden impactar&aacute; directamente con las p&aacute;ginas publicadas.
</p>
<div class="menubarra">
    <ul>
        <li><a class="left" href="pag_categorias.php">Volver al listado de categor&iacute;as</a></li>
        <?php  if ($mostrarvolver){?>
        <li><a class="left" href="pag_paginas_orden.php<?php  echo $url?>">Volver al superior</a></li>
        <?php  }?>
	</ul>
</div>    
<div class="clear aire_vertical">&nbsp;</div>
    <form action="pag_paginas.php" method="post" name="formbusqueda" id="formbusqueda">

        <input type="hidden" name="catcod" value="<?php  echo  $catcod?>" id="catcod" />
        <input type="hidden" name="pagestadocod" value="<?php  echo  $pagestadocod?>" id="pagestadocod" />
        <input type="hidden" name="pagcodsuperior" id="pagcodsuperior" value="<?php  echo $pagcodsuperior?>" />
	</form>
<div id="LstPaginas" style="width:100%;">
    <table id="ListarPaginas"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
    
<?php  
$oEncabezados->PieMenuEmergente();
?>