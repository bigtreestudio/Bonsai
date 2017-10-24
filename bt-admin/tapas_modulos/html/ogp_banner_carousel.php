<?
$oBanner = new cBanners($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);


?>
        
<div class="ogp_banners_carousel tap_modules" id="module_<? echo $vars['zonamodulocod']?>" <? echo  $vars['mouseaction']?>>
	<? echo $vars['htmledit']?>
	<div id="carousel-ogp_<? echo $vars['zonamodulocod']?>" class="carousel slide carousel-ogp" data-ride="carousel"  data-interval="5000"> 
    	<!-- Indicators -->
        <ol class="carousel-indicators">
		<? 
		$cantidad = 0;
        foreach ($objDataModel->bannercod as $bannercod)
        {
			$active="";
			if ($cantidad==0)
				$active="active";
			?>
            <li data-target="#carousel-ogp_<? echo $vars['zonamodulocod']?>" data-slide-to="0" class="<? echo $active?>"></li>
            <?
			$cantidad++;
		}?>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
			<? 
            $cantidad = 0;
            foreach ($objDataModel->bannercod as $bannercod)
            {
				$active="";
				if ($cantidad==0)
					$active="active ";
				
                $datosbusqueda['bannercod'] = $bannercod;
                if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
                   return false;
                                
                $datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
                $nombrearchivo = $datosbanner['bannerarchubic'];
                $imagenNormal = str_replace("banners/","",Multimedia::GetImagenStatic(1400, 600, "banners/".$nombrearchivo, 1, true));
                ?>
                <div class="item <? echo $active?>">
                	<img src="<? echo DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$imagenNormal;?>" alt="<? echo utf8_decode($objDataModel->BannerTitulo->$bannercod)?>"/>
                     <div class="carousel-caption">
                        <h1><? echo utf8_decode($objDataModel->BannerTitulo->$bannercod)?></h1>
                        <p class="lead"><? echo utf8_decode($objDataModel->BannerTexto->$bannercod)?></p>
                    </div>
                </div>
                <?
                $cantidad++;
            }
            ?>
        </div>
         <!-- Controls -->
        <a class="left carousel-control" href="#carousel-ogp_<? echo $vars['zonamodulocod']?>" role="button" data-slide="prev">
           <span class="glyphicon glyphicon-chevron-left"></span>
         </a>
         <a class="right carousel-control" href="#carousel-ogp_<? echo $vars['zonamodulocod']?>" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
         </a>
    </div>
 </div>