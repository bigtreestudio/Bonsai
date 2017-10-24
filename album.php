<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cGalerias.class.php");
include(DIR_CLASES."cAlbums.class.php");
include(DIR_CLASES."cMultimedia.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

//codigo de galeria
if (!isset($_GET['codigo']) || $_GET['codigo']=='')
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
if(strlen($_GET['codigo'])>10)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}	
if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['codigo'],"NumericoEntero"))
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
//instancio un service para la galeria
$databusqueda['albumcod'] = $_GET['codigo'];
$oAlbumsService = new cAlbums($conexion);
$oMultimediaService = new cMultimedia($conexion);
if(isset($_POST[session_name()]))
{
	//previsualizacion
	setcookie(session_name(),$_POST[session_name()],0,"/");
	// arma las variables de sesion y verifica si se tiene permisos
	FuncionesPHPLocal::ArmarLinkMD5Front(basename($_SERVER['PHP_SELF']),array("codigo"=>$_GET['codigo'],session_name()=>$_POST[session_name()]),$getPrevisualizar,$md5Prev);
	if ($_GET['md5']!=$md5Prev)
	{	
		ob_clean();
		FuncionesPHPLocal::Error404();
		die();
	}
	$datosalbum = $oAlbumsService->BuscarxCodigo($databusqueda);
}else
	$datosalbum = $oAlbumsService->BuscarxCodigo($databusqueda);


if ($datosalbum===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

if ($datosalbum['menucod']!="")
{
	$oEncabezados->setMenu($datosalbum['menucod']);
	$oMenu = new cMenu($conexion);
	$datosMenuBusqueda['menucod'] = $datosalbum['menucod'];
	$datosMenuBusqueda['menutipocod'] = $datosalbum['menutipocod'];
	$oMenu->BuscarxCodigo($datosMenuBusqueda,$resultado,$numfilas);
	if ($numfilas>0)
	{
		$datosMenu = $conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosMenu['menucodsup']!="")
			$oEncabezados->setMenu($datosMenu['menucodsup']);
	}
}


$galerias= array();
if (isset($datosalbum['galerias']))
	$galerias = $datosalbum['galerias'];

$albumtitulo=FuncionesPHPLocal::HtmlspecialcharsBigtree($datosalbum["albumtitulo"],ENT_QUOTES);
$oEncabezados->setTitle($albumtitulo);
$oEncabezados->setDescription(substr(strip_tags($albumtitulo),0,50));
$oEncabezados->setOgTitle($albumtitulo);
$oEncabezados->EncabezadoMenuEmergente();

?>
<div id="DetalleGaleria">
    <h1><?php  echo $albumtitulo;?></h1>
    <div class="lst_albums">
                <ul>
                    <?php  
					//$cantitemsporfila=5;
					if(count($galerias)>0)
					{
						$anchoImg = 172;
						foreach ($galerias as $galeriacod=>$datosmultimedia){
						
						$fotos=$videos=$audios = array();
						if (isset($datosmultimedia['multimedias']["fotos"]))
							$fotos = $datosmultimedia['multimedias']["fotos"];
						if(isset($datosmultimedia['multimedias']["videos"]))
							$videos = $datosmultimedia['multimedias']["videos"];
						
						$img ="";
						if (count($fotos)>0)
						{
							foreach ($fotos as $imagen)
							{
								$img = DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic($anchoImg, 0, $imagen['url']);
							}
						}
						 if (count($videos)>0)
						 {
                            foreach ($videos as $video)
							{
								$img = $oMultimediaService->ArmarImagenVideo($video['tipo'], $video['idexterno']);
							}
						 }
						
                        $alt = "Galeria ". FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmultimedia['galeriatitulo'],ENT_QUOTES);?>
                        <li class="estilo_<?php  echo $datosmultimedia['multimediaconjuntocod']?>">
                            <a class="imagen_multimedia" rel="galeria_rel" href="<?php  echo DOMINIORAIZSITE."galeria/".$datosmultimedia['galeriadominio'];?>" title="<?php  echo $alt?>">	
                            	<?php  if ($img!=""){?>
                                	<img src="<?php  echo $img;?>" alt="<?php  echo $alt?>" />
                                <?php  }?>
                                <span class="zoom"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmultimedia['galeriatitulo'],ENT_QUOTES);?></span>
                            </a>
                            <div class="clearboth">&nbsp;</div>	
                            
	                        <span class="titulodesc">
									<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmultimedia['galeriatitulo'],ENT_QUOTES);?>
                            </span>
                            
                        </li>
                        <?php 
						/*
						$i++;
						if ($i==$cantitemsporfila)
						{
							echo ' <div class="clearboth">&nbsp;</div>';
						}*/
						}
					}?>
                </ul>
      </div>
      <div class="clearboth">&nbsp;</div>	
</div>

<?php  
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>