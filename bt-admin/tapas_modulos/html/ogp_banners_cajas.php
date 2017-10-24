<?
$oBanner = new cBanners($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$BannersVecs=array();
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	foreach ($objDataModel->bannercod as $bannercod)
    {
		$BannersVecs[$bannercod]["titulo"]=$objDataModel->BannerTitulo->$bannercod;
		$BannersVecs[$bannercod]["link"]=$objDataModel->BannerLink->$bannercod;
		$BannersVecs[$bannercod]["bajada"]=FuncionesPHPLocal::HtmlspecialcharsBigtree($objDataModel->BannerBajada->$bannercod,ENT_QUOTES);
		
		$datosbusqueda['bannercod'] = $bannercod;
        if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
               return false;
		
		$datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
        $nombrearchivo = $datosbanner['bannerarchubic'];
        $BannersVecs[$bannercod]["img"] = str_replace("banners/","",Multimedia::GetImagenStatic(360, 215, "banners/".$nombrearchivo));
	}
}
$BannersVecsJson=json_encode($BannersVecs);
if(!file_put_contents(PUBLICA."json/modulo_ogpbannerscaja_".$vars['zonamodulocod'].".json",$BannersVecsJson))
		echo "mal";
?>
        
<div class="ogp_caja_banners tap_modules" id="module_<? echo $vars['zonamodulocod']?>" <? echo  $vars['mouseaction']?>>
	<? echo $vars['htmledit']?>
	<div class="row links">
    	$$Tipo='Include' Archivo='ogp_banners_random.php' Parametros='zonamodulocod=<?php  echo $vars['zonamodulocod']?>'$$
    </div>
 </div>