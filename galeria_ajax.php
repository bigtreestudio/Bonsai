<?php  
include("./config/include.php");
include(DIR_CLASES."cGalerias.class.php");
include(DIR_CLASES."cMultimedia.class.php");

//instancio un service para la galeria
$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);
$databusqueda['galeriacod'] = $_POST['codigo'];
$oGaleriasService = new cGalerias($conexion);
$datosgaleria = $oGaleriasService->BuscarxCodigo($databusqueda);

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

$datosgaleria = $oGaleriasService->BuscarxCodigo($databusqueda);

if ($datosgaleria===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
$galeriatitulo=FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria["galeriatitulo"],ENT_QUOTES);

$encoded_imagenes=array();
if (count($fotos)>0){
	$i=0;
	foreach ($fotos as $imagen){
		$encoded_imagenes[$i]["id"]=$imagen['codigo'];
		$encoded_imagenes[$i]["thumbnail_large"]=DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic(1000, 0, $imagen['url']);
		$encoded_imagenes[$i]["thumbnail_small"]=DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic(172, 0, $imagen['url']);
		$encoded_imagenes[$i]["epigrafe"]= FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen['titulo'],ENT_QUOTES);
		$encoded_imagenes[$i]["title"]=FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen['titulo'],ENT_QUOTES);
		$encoded_imagenes[$i]["description"]=FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen['descripcion'],ENT_QUOTES);			$i++;
	}
	$json_imagenes = json_encode($encoded_imagenes);
}

switch($_POST['tipo']){
	case 1:
		?>
        <script src="<?php  echo DOMINIORAIZSITE?>js/galleria/galleria-1.2.8.min.js"></script>

		<script>
		$(document).ready(function(){
			if( $('.galleria')[0] ) {
				Galleria.loadTheme('/js/galleria/themes/azur/galleria.azur.min.js');
				Galleria.run('.galleria');
			}
		});
								
		$('.galleria').galleria({
			showInfo: true
		});
		Galleria.ready(function(options) {
									
			var imagenes = <?php  echo $json_imagenes?>;
			this.bind('image', function(e) {
				$("#fotografo").html("");
				$("#epigrafe").html(imagenes[e.index]['epigrafe']);
			});
			
		});
		</script>

     <div class="galeria_mosaico center">
                <div style="position:relative">
                    <div class="galleria">                    
                    <?php  for($i=0; $i<count($encoded_imagenes);$i++){ ?>
                        <img src="<?php  echo $encoded_imagenes[$i]['thumbnail_large']?>" alt="<?php  echo $encoded_imagenes[$i]['title']?> " data-title="<?php  echo $encoded_imagenes[$i]['title']?>" data-description="<?php  echo $encoded_imagenes[$i]['description']?>"/>

                    <?php  } ?>
                    </div>
                </div>
                <div class="clearboth brisa">&nbsp;</div>
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
      </div><?
	break;
	
	case 2:?>
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
	 <?
	 case 3:
	 $anchoImg = 172;
	 ?>
            <div class="clearboth">&nbsp;</div>
	         <div class="lst_galeria">
                <ul>
                    <?php  
					if (count($fotos)>0){
						foreach ($fotos as $imagen){
                        $alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen['titulo'],ENT_QUOTES);
                        $alt .= " - Im&aacute;gen de galeria ".$galeriatitulo;
                        ?>
                        <li>
                            <a class="imagen_multimedia" rel="galeria_rel" href="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic(1000, 0, $imagen['url'] );?>" onclick="return false;" title="<?php  echo $alt?>">
                               <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic($anchoImg, 0, $imagen['url']);?>"  alt="<?php  echo $alt?>" />
                                <span class="zoom">&nbsp;</span>
                            </a>
                            
                        </li>
                    <?php  }
					}?>
                </ul>
            </div>
       
            <div class="clearboth">&nbsp;</div>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/noticia.js"></script>


		<? break; 
	
}
?>
