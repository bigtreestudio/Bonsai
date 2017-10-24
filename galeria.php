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
//MULTIMEDIA
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
	
	//instancio un service para el elemento multimedia
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
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/noticia.js"></script>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/galeria.js"></script>
<script type="text/javascript" >
$(document).ready(function() {
	CargarGaleria(3,<? echo $databusqueda['galeriacod'] ?>)
});

</script>


<div id="DetalleGaleria">
	<div class="clearboth aire">&nbsp;</div>
     <div class="titulo">
    	<div class="titulo_galeria">
            <h1>
            <?php  echo $galeriatitulo;?>
            </h1>
        </div>
        <?php  if ($datosgaleria['multimediaconjuntocod']==FOTOS){?>
        <div class="botonera" style="float:right">
            <div class="mosaic-nav">
               <ul>
                    <li class="icons">
                       <a class="first" style=" cursor:pointer" title="Cargar Galeria Horizontal" onclick="return CargarGaleria(1,<? echo $databusqueda['galeriacod']?>)" ></a>
                        <a class="second" style=" cursor:pointer" title="Cargar Galeria Vertical"  onclick="return CargarGaleria(2,<? echo $databusqueda['galeriacod']?>)"></a>
                        <a class="third active" style=" cursor:pointer" title="Cargar Galeria Vista Normal" onclick="return CargarGaleria(3,<? echo $databusqueda['galeriacod']?>)"></a>                
                    </li>
                    <li><?php  echo count($fotos)?> fotos</li>
               </ul>
            </div>                    
        </div>
        <?php  }?>
    </div>
    <div class="cuerpo">
        <?php  echo $datosgaleria["galeriadesc"];?>
    </div>
    <div class="clearboth brisa_vertical">&nbsp;</div>
	<div class="Galeria"></div>

	
	<?php 
	switch ($datosgaleria['multimediaconjuntocod'])
	{
		

		case VIDEOS:
			$cantvideosporfila=5;
			
			if (count($videos)>0)
			{
				
				?>
                <div class="lst_galeria">
                    <ul>
                        <?php  
                        if (count($videos)>0){
                            foreach ($videos as $video){
								$alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($video['titulo'],ENT_QUOTES);
								$alt .= " - Im&aacute;gen de galeria ".$galeriatitulo;
								?>
								<li>
									<a class="imagen_multimedia" rel="galeria_videos" data-fancybox-type="iframe" data-fancybox-group="group1" href="<?php  echo str_replace("/watch?v=","/embed/", $oMultimediaService->ArmarUrlVideo($video['tipo'], $video['idexterno']))?>" title="<?php  echo $alt?>">
										<img src="<?php  echo $oMultimediaService->ArmarImagenVideo($video['tipo'], $video['idexterno']);?>" alt="<?php  echo $alt?>" />
										<span class="zoom">&nbsp;</span>
									</a>
									
								</li>
                        	<?php  }
                        }?>
                    </ul>
                </div>
                <div class="clearboth">&nbsp;</div>
				<script type="application/javascript">
					$(document).ready(function () {
                        $("a.imagen_multimedia").fancybox({
							margin: [20, 0, 20, 0],
							beforeShow: function () {
								if (this.title) {
									// New line
									this.title += '<br /><div class="soc-nav"><ul class="clearfix">';
									
									// Add tweet button
									this.title += '<li><a href="https://twitter.com/share" data-url="<?php  echo DOMINIOWEB."galeria/horizontal/".$galeriaDominio;?>" class="twitter-share-button" title="Twittear <?php  echo $galeriatitulo?>" data-lang="es" data-count="none">Twittear</a></li>';
									
									// Add FaceBook like button
									this.title += '<li class="facebooklike"><fb:like data-href="<?php  echo DOMINIOWEB."galeria/horizontal/".$galeriaDominio;?>" layout="button_count" width="120" show_faces="false"></fb:like></li></ul>';
									
									this.title += '</div>';
								}
							},
							afterShow: function() {
								// Render tweet button
								twttr.widgets.load();
							},
							helpers : {
								title : {
									type: 'inside'
								}
							}  
						});
					});
                </script>
                 <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                <?php 
				}
				break;
			
			case AUDIOS:
				$cantvideosporfila=5;
				if (count($audios)>0)
				{
					
					?>
                    <div class="caja_video">
						<div id="audioplayer_container">
							
						</div>
                        <div class="texto">
                        	<?php  echo nl2br($video_seleccionado['descripcion'])?>
                        </div>
                    </div>
					<div class="clearboth">&nbsp;</div>
					<div class="lst_galeria">
						<ul>
							<?php  
							$i=0;
							foreach ($audios as $audio){
								$alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($audio['descripcion'],ENT_QUOTES);
								if ($alt!="")
									$alt=" - ";
								$alt .= "Audio de galeria ".$galeriatitulo;
								
								?>
								<li class="audio">
									<a class="imagen_multimedia" href="<?php  echo DOMINIORAIZSITE.$galeriaDominio;?>_m<?php  echo $audio['codigo']?>" onclick="CargarAudio(<?php  echo $audio['codigo']?>);return false;" title="<?php  echo $alt?>">
									</a>
								</li>
								<?php 
								$i++;
								if ($i==$cantvideosporfila)
								{
									echo ' <div class="clearboth">&nbsp;</div>';
								}
							}?>
						</ul>
					</div>
					<div class="clearboth">&nbsp;</div>
					 <script type="application/javascript">
						$(document).ready(function() {
							CargarAudio(<?php  echo $video_seleccionado['codigo']?>)
						});	
						function CargarAudio(codigo)
						{ 
								param  = "codigo="+codigo; 
								var param, url;
								$.ajax({
								   type: "POST",
								   url: "<?php  echo DOMINIORAIZSITE?>galeria_audio_ajax.php",
								   data: param,
								   dataType:"html",
								   success: function(msg){ 
										$("#audioplayer_container").html(msg);
								   }
								});	
						}
					</script>
					<?php 
				}
			break;
			
	}
	?>
	
</div>
                  <script type="application/javascript">
					$("a.imagen_multimedia").fancybox({
				
							beforeShow: function () {
								if (this.title) {
									// New line
									this.title += '<br /><div class="soc-nav"><ul class="clearfix">';
									
									// Add tweet button
									this.title += '<li><a href="https://twitter.com/share" data-url="<?php  echo DOMINIOWEB."galeria/";?>" class="twitter-share-button" title="Twittear <?php  echo $galeriatitulo?>" data-lang="es" data-count="none">Twittear</a></li>';
									
									// Add FaceBook like button
									this.title += '<li class="facebooklike"><fb:like data-href="<?php  echo DOMINIOWEB."galeria/";?>" layout="button_count" width="120" show_faces="false"></fb:like></li></ul>';
									
									this.title += '</div>';
								}
							},
							afterShow: function() {
								// Render tweet button
								twttr.widgets.load();
							},
							helpers : {
								title : {
									type: 'inside'
								}
							}  
						});
            
            </script>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


<?php  
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>