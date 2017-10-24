<?php 

$objDataModel = json_decode($vars['modulodata']);
if ($vars['modulodata']!="" && isset($objDataModel) && isset($objDataModel->bannercod))
{
	$oBanner = new cBanners($vars['conexion']);
	$datosbusqueda['bannercod'] = $objDataModel->bannercod;
	if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
		return false;
	
	if ($numfilas>0)
	{
		$datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
		unset($oBanner);
		
		$muestraurl = false;
		if ($datosbanner['bannerurl']!="")
		{
			$muestraurl = true;
			$url = $datosbanner['bannerurl'];
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
		<div class="banner_pagina">
			<?php  if ($muestraurl){?>
				<a href="<?php  echo $url?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosbanner['bannerdesc'],ENT_QUOTES);?>">
			<?php  }?>
				<?php  echo $html;?>
			<?php  if ($muestraurl){?>
				</a>
			<?php  }?>
		</div>
<?php  
	}
}?>