<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cPortadas.class.php");
include(DIR_CLASES."cMultimedia.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);


if (!isset($_GET['dominio']) || $_GET['dominio']=='')
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

if(strlen($_GET['dominio'])>255)
	die();

/*---------------------------------------------------------------------------------------------*/
/*LLAMADO DE UNA PORTADA POR DEFAULT, SINO EXISTE EL DOMINIO SIGUE POR PAGINAS*/
/*---------------------------------------------------------------------------------------------*/
$datos['tapatipourlfriendly'] = $_GET['dominio'];
if (!isset($_GET['listado']) && !isset($_GET['pagina']))
{
	$oPortada = new cPortadas($conexion);
	if($oPortada->BuscarPortadaxDominio($datos))
	{
		if (file_exists(PUBLICA.$oPortada->getArchivo()))
		{
			$html=file_get_contents(PUBLICA.$oPortada->getArchivo());
			$oEncabezados->Procesar($html,$htmlprocesado);
			die();
		}else
		{
			ob_clean();
			FuncionesPHPLocal::Error404();
			die();
		}
	}
}

/*---------------------------------------------------------------------------------------------*/
/*LLAMADO DE UNA PAGINA SINO SIGUE POR NOTICIAS */
/*---------------------------------------------------------------------------------------------*/

	include(DIR_CLASES."cPaginas.class.php");
	$oPaginasService = new cPaginas($conexion);
	$datospagina['pagdominio'] = $_GET['dominio'];
	//busco el dominio si exist en paginas
	if($oPaginas=$oPaginasService->BuscarPaginaxDominio($datospagina))
	{
        include("paginainclude.php");
		die;
	}

/*---------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------*/
include(DIR_CLASES."cNoticias.class.php");
include(DIR_CLASES."cNoticiasCategorias.class.php");

$oCategoriasService = new cNoticiasCategorias($conexion);
$datos['catdominio'] = $_GET['dominio'];
$oCategorias = $oCategoriasService->BuscarCategoriaxDominio($datos);
if($oCategorias===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
$datosextra = $oCategorias->getDataJson();

if ($oCategorias->getMenuCodigo()!="")
{
	$oEncabezados->setMenu($oCategorias->getMenuCodigo());
	$oMenu = new cMenu($conexion);
	$datosMenuBusqueda['menucod'] = $oCategorias->getMenuCodigo();
	$datosMenuBusqueda['menutipocod'] = $oCategorias->getMenuTipoCodigo();
	$oMenu->BuscarxCodigo($datosMenuBusqueda,$resultado,$numfilas);
	if ($numfilas>0)
	{
		$datosMenu = $conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosMenu['menucodsup']!="")
			$oEncabezados->setMenu($datosMenu['menucodsup']);
	}
}


$oEncabezados->setTitle($oCategorias->getNombre());
$oEncabezados->setDescription(substr(strip_tags($oCategorias->getDescripcion()),0,100));
$oEncabezados->setOgTitle($oCategorias->getNombre());
$oEncabezados->setPlantilla($oCategorias->getPlantillaHtmlCodigo());


$oEncabezados->EncabezadoMenuEmergente();

?>
<div id="DetalleCategorias">
	<div id="NoticiasLst">
		<div class="leftcolumn">
			<h1><?php  echo $oCategorias->getNombre();?></h1>
			<div class="cuerpo">
				<?php  echo $oCategorias->getDescripcion();?>
			</div>
            <div class="noticias_lst">
				<?php 
                    include("categoria_lst_ajax.php");
                ?>
            </div>
		</div>
		<div class="rightcolumn">
			<?php  
				if (file_exists(PUBLICA."secciones/seccion_".$oCategorias->getCodigo().".html"))
					include(PUBLICA."secciones/seccion_".$oCategorias->getCodigo().".html");
			?>
		</div>
	</div>
</div>
<?php  
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>