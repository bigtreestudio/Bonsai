<?php 
$oBanner = new cBanners($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$datosbusqueda['bannercod'] = $objDataModel->bannercod;
if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
	return false;

$datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
unset($oNoticia);

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
		$html = '<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$nombrearchivo.'" alt="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES).'">'; 
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
<div class="banner tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
   	<?php  if ($muestraurl){?>
    	<a href="/banner/<?php  echo $datosbanner['bannercod']."/".$md5?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES);?>">
    <?php  }?>
        <?php  echo $html;?>
   	<?php  if ($muestraurl){?>
    	</a>
    <?php  }?>
</div>
