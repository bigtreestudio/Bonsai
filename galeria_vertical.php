<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cGalerias.class.php");
include(DIR_CLASES."cMultimedia.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

//codigo de galeria
if (!isset($_GET['codigo']) || $_GET['codigo']=='' || strlen($_GET['codigo'])>10 || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['codigo'],"NumericoEntero"))
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
//instancio un service para la galeria
$databusqueda['galeriacod'] = $_GET['codigo'];
$oGaleriasService = new cGalerias($conexion);

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
		$datosgaleria = $oGaleriasService->BuscarxCodigo($databusqueda);
}else
	$datosgaleria = $oGaleriasService->BuscarxCodigo($databusqueda);

if ($datosgaleria===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
//cargo elementos multimedia de la galeria
$fotos=$videos=$audios = array();
if (isset($datosgaleria['multimedias']["fotos"]))
	$fotos = $datosgaleria['multimedias']["fotos"];
if(isset($datosgaleria['multimedias']["videos"]))
	$videos = $datosgaleria['multimedias']["videos"];
if(isset($datosgaleria['multimedias']["audios"]))
	$audios = $datosgaleria['multimedias']["audios"];

//codigo de elemento mutimedia
if (isset($_GET['elemento']) && $_GET['elemento']!='')
{		
	if(strlen($_GET['elemento'])>10)
	{	
		ob_clean();
		FuncionesPHPLocal::Error404();
		die();
	}	
	if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['elemento'],"NumericoEntero"))
	{	
		ob_clean();
		FuncionesPHPLocal::Error404();
		die();
	}
	
	$databusqueda['multimediacod'] = $_GET['elemento'];	
	$video_seleccionado= $oGaleriasService->BuscarMultimediaxCodigo($databusqueda);

}
else
{
	if (count($videos)>0)
	{
		$codigo ="";
		foreach($videos as $multimediacod=> $video) 
		{
			if($codigo=="")
			{
				$codigo = $multimediacod;
				$video_seleccionado=$videos[$codigo];
			}
		}
	}
	else
		$video_seleccionado="";
}

$galeriatitulo=FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria["galeriatitulo"],ENT_QUOTES);

$oEncabezados->setTitle($galeriatitulo);
$oEncabezados->setDescription(substr(strip_tags($datosgaleria["galeriadesc"]),0,50));
$oEncabezados->setOgTitle($galeriatitulo);
$oEncabezados->EncabezadoMenuEmergente();


//genero el dominio de la galeria
$galeriaDominio=$datosgaleria['galeriadominio'];
?>
<script src="<?php  echo DOMINIORAIZSITE?>js/galleria/galleria-1.2.8.min.js"></script>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/noticia.js"></script>

<?php 
$encoded_imagenes=array();
if (count($fotos)>0){
	$i=0;
	foreach ($fotos as $imagen){
		$encoded_imagenes[$i]["id"]=$imagen['codigo'];
		$encoded_imagenes[$i]["thumbnail_large"]=DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic(1000, 0, $imagen['url']);
		$encoded_imagenes[$i]["thumbnail_small"]=DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic(172, 0, $imagen['url']);
		$encoded_imagenes[$i]["epigrafe"]= FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen['titulo'],ENT_QUOTES);
		$encoded_imagenes[$i]["title"]=FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen['titulo'],ENT_QUOTES);
		$encoded_imagenes[$i]["description"]=FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen['descripcion'],ENT_QUOTES);		$i++;
	}
}
//echo $json_imagenes;
?>

<div id="DetalleGaleria">
	<div class="clearboth aire">&nbsp;</div>
    <div class="titulo">
    	<div class="titulo_galeria">
            <h1>
            <?php  echo $galeriatitulo;?>
            </h1>
        </div>
        <div class="botonera" style="float:right">
            <div class="mosaic-nav">
               <ul>
                    <li class="icons">
                       <a class="first" href="/galeria/horizontal/<?php  echo $galeriaDominio?>"></a>
                        <a class="second active" href="/galeria/vertical/<?php  echo $galeriaDominio?>"></a>
                        <a class="third" href="/galeria/<?php  echo $galeriaDominio?>"></a>                
                    </li>
                    <li><?php  echo count($fotos)?> fotos</li>
               </ul>
            </div>                    
        </div>
    </div>
    <div class="cuerpo">
        <?php  echo $datosgaleria["galeriadesc"];?>
    </div>
    <div class="clearboth aire">&nbsp;</div>
     <div class="galeria_mosaico center">
        <div class="info-media">
                    <div class="gallery-data clearfix">
                        <span class="tags"><strong id="epigrafe"></strong></span>
                        <div class="soc-nav">
                            <ul class="clearfix">
                                <li class="facebooklike">
                                     <fb:like data-href="<?php  echo DOMINIOWEB."galeria/horizontal/".$galeriaDominio;?>" layout="button_count" width="120" show_faces="false"></fb:like>
                                </li>
                                <li>
                                    <a href="https://twitter.com/share" data-url="<?php  echo DOMINIOWEB."galeria/horizontal/".$galeriaDominio;?>" class="twitter-share-button" title="Twittear <?php  echo $galeriatitulo?>" data-lang="es" data-count="none">Twittear</a>
                                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                </li>
                                <li>
                                <div class="g-plusone" data-href="<?php  echo DOMINIOWEB."galeria/horizontal/".$galeriaDominio;?>" data-size="medium"></div>
                            </li>
                            </ul>
                        </div>
                    </div>
         </div>
         <div class="clearboth brisa">&nbsp;</div>	
     	 <?php  for($i=0; $i<count($encoded_imagenes);$i++){ ?>
                <div style="position:relative">
                   
                        <img src="<?php  echo $encoded_imagenes[$i]['thumbnail_large']?>" alt="<?php  echo $encoded_imagenes[$i]['title']?> " data-title="<?php  echo $encoded_imagenes[$i]['title']?>" data-description=" prueba <?php  echo $encoded_imagenes[$i]['description']?>"/>

                    
                </div>
                <div class="clearboth aire">&nbsp;</div>
         <?php  } ?>
      </div>
</div>

<?php  
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>