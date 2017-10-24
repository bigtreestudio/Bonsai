<?php
$zonamodulocod="";
if ($vars['zonamodulocod']!="")
	$zonamodulocod = $vars['zonamodulocod'];
	
if ($zonamodulocod!="")
{
	$archivo = "modulo_ogpbannerscaja_".$zonamodulocod.".json";
	if(file_exists(PUBLICA."json/".$archivo))
	{
		$string = file_get_contents(PUBLICA."json/".$archivo);
		$arrayJson = json_decode($string,true);
		$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
	}
	$cantidad = 0;
	shuffle($array);
    foreach ($array as $banner)
    {   
        ?>
         <div class="col-md-4 col-sm-6 col-xs-12">
               <figure class="caja_link" style="background-image:url('<? echo DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$banner["img"];?>">
                   <div class="banda_amarilla"><a href="<? echo $banner["link"]?>" target="_blank"><h3><? echo $banner["titulo"]?></h3></a></div>
                   <? if ($banner["bajada"]!=""){?>
                       <div class="overlay">
                            <div class="text">
                                <a href="<? echo $banner["link"]?>" target="_blank">
                                    <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($banner["bajada"],ENT_QUOTES)?>
                                </a>
                            </div>
                        </div>
                    <? }?> 
               </figure>
         </div>
        <?
        $cantidad++;
     }
}?>