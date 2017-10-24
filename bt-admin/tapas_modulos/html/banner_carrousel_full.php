<?
$oBanner = new cBanners($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$Direccion = "left";

$AutoPlay="false";
$MuestraBotonera=true;
$TipoCarrousel = 1;

if (isset($objDataModel->Direccion))
	$Direccion = $objDataModel->Direccion;

if (isset($objDataModel->AutoPlay) && $objDataModel->AutoPlay==1)
	$AutoPlay = "true";

if (isset($objDataModel->MuestraBotonera) && $objDataModel->MuestraBotonera==0)
	$MuestraBotonera = false;

if (isset($objDataModel->TipoCarrousel))
	$TipoCarrousel = $objDataModel->TipoCarrousel;


$anchoImgThumb = 100;
$altoThumb = 70;

$anchoImg = 1368;
$alto = 500;
if (isset($vars['width']) && $vars['width']!="")
{
	$porcAncho = round($vars['width']*100/$anchoImg);
	$anchoImg = $vars['width'];
	$alto = round($vars['width']*$alto/$anchoImg);
	
	$anchoNuevoImgThumb = round($porcAncho*$anchoImgThumb/100);
	$altoThumb = round($anchoNuevoImgThumb*$altoThumb/$anchoImgThumb);
	$anchoImgThumb = $anchoNuevoImgThumb;
}


?>
        
<div class="bannerfull carrouselhome tap_modules" id="module_<?=$vars['zonamodulocod']?>" <?= $vars['mouseaction']?>>
	<? echo $vars['htmledit']?>

        <div class="carousel_full">
            <ul class="CarrouselFotos" id="bannercarrousel_<?=$vars['zonamodulocod']?>">
				<? 
					$cantidad = 0;
                    foreach ($objDataModel->bannercod as $bannercod)
                    {
						$cantidad++;
                        $datosbusqueda['bannercod'] = $bannercod;
                        if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
                            return false;
                        
                        $datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
                        
                        $muestraurl = false;
                        if ($datosbanner['bannerurl']!="")
                        {
                            $muestraurl = true;
                            $url = $datosbanner['bannerurl'];
							FuncionesPHPLocal::ArmarLinkMD5Front("banner_click.php",array("codigo"=>$datosbanner['bannercod']),$get,$md5);
                           $target = $datosbanner['bannertarget'];	
                        }
                        
                        $nombrearchivo = $datosbanner['bannerarchubic'];
                        $pathinfo = pathinfo($nombrearchivo);
                        $extension = strtolower($pathinfo['extension']);
                        $html = "";
                        switch($extension)
                        {
                            case "jpg":
                            case "png":
                            case "gif":
								$imagenGrande = str_replace("banners/","",Multimedia::GetImagenStatic(1500, 0, "banners/".$nombrearchivo));
								$imagenNormal = str_replace("banners/","",Multimedia::GetImagenStatic(1200, 0, "banners/".$nombrearchivo));
								$imagen960 = str_replace("banners/","",Multimedia::GetImagenStatic(960, 0, "banners/".$nombrearchivo));
								$imagen768 = str_replace("banners/","",Multimedia::GetImagenStatic(768, 0, "banners/".$nombrearchivo));
								$imagen480 = str_replace("banners/","",Multimedia::GetImagenStatic(480, 0, "banners/".$nombrearchivo));
								
								$html = "<img src='/multimedia/banners/".$imagenNormal."' alt=".FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES);
                                break;
                            case "swf":
                                list($ancho, $alto, $tipo, $atr) = getimagesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA."banners/".$nombrearchivo);
                                $html .= '<object type="application/x-shockwave-flash" data="'.DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$nombrearchivo.'" width="'.$ancho.'" height="'.$alto.'">';
                                $html .= '	<param name="movie" value="'.DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$nombrearchivo.'" />';
                                $html .= '	<param name="quality" value="high" />';
                                $html .= '	<param name="wmode" value="transparent" />';
                                $html .= '	<embed src="'.DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$nombrearchivo.'" width="'.$ancho.'" height="'.$alto.'" quality="high" type="application/x-shockwave-flash"  pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
                                $html .= '</object> ';
                                break;
                        }
                        ?>
                        <li>
							<? if ($muestraurl){?>
                                <a href="/banner/<? echo $datosbanner['bannercod']."/".$md5?>" target="<? echo $target?>" title="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES);?>">
                            <? }?>
                            	<? echo $html;?>
							 <? if ($muestraurl){?>
                                </a>
                            <? }?>
                           <? /*if ($objDataModel->MuestraDescripcion->{$bannercod}==1){?>
                                <div class="<? echo $objDataModel->Alineacion->{$bannercod}?>" style="width:<? echo $objDataModel->TamanioBanner->{$bannercod}?>%">
                                    <? if ($muestraurl){?>
                                        <a href="/banner/<? echo $datosbanner['bannercod']."/".$md5?>" target="<? echo $target?>" title="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES);?>">
                                    <? }?>
                                        <h4><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES)?></h4>
                                    <? if ($muestraurl){?>
                                        </a>
                                    <? }?>
                                    <? if ($datosbanner['bannerdesclarga']!=""){
											$datosbanner['bannerdesclarga'] = preg_replace("/<strong[^>]*><\\/strong[^>]*>/",'',$datosbanner['bannerdesclarga']);
											$datosbanner['bannerdesclarga'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<br />',$datosbanner['bannerdesclarga']);
										?>
                                        <p><? echo strip_tags(nl2br($datosbanner['bannerdesclarga']),"<b><strong><a><em><br /><br>");?></p>
                                    <? }else{?><p>&nbsp;</p><? }?>
                                </div>
                            <? }*/?>
                        </li>
                    <? }
                ?>
            </ul>
            <div class="outside">
            	<div class="contenedor_fijo">
                  <div class="slider-prev">
                  	<span id="slider-prev">&nbsp;</span>
                  </div>
                  <div class="slider-next">
                  	<span id="slider-next">&nbsp;</span>
                  </div>
                </div>
            </div>
            <div style="clear:both; height:1px;">&nbsp;</div>
        </div>
        
        <? if ($cantidad>1){?>
		<script type="text/javascript">
        jQuery(document).ready(function(){
            $('#bannercarrousel_<?php echo $vars['zonamodulocod']?>').bxSlider({
				mode: 'fade',
				autoDelay: 5000,
				adaptiveHeight: true,
				auto: false,
				nextSelector: '#slider-next',
			    prevSelector: '#slider-prev',
				nextText: '<i class="fa fa-angle-right"></i>',
				prevText: '<i class="fa fa-angle-left"></i>'
            });
        });
        </script>
        <? }?>
            
 </div>