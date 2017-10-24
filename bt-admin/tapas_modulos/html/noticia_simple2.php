<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$oNoticia = new cNoticias($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$datosbusqueda['noticiacod'] = $objDataModel->noticiacod;
if(!$oNoticia->BuscarDatosCompletosNoticiasPublicadasxCodigo($datosbusqueda,$resultado,$numfilas))
	return false;

$datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);


$dominiocompartir = DOMINIOWEB."cn".$objDataModel->noticiacod;

$titulo = $datosnoticia['noticiatitulo'];
$copete = strip_tags($datosnoticia['noticiacopete'],"<strong><a>");
$catdominio = $datosnoticia["catdominio"];

$dominio = DOMINIORAIZSITE.$catdominio."/".$datosnoticia["noticiadominio"];
$target = "_self";
if ($datosnoticia['noticiahrefexterno']!="")
{
	$dominio = $datosnoticia['noticiahrefexterno'];
	$target = "_blank";
}
$catnom = $datosnoticia["catnom"];
$catcod = $datosnoticia["catcod"];
$catcolor = $datosnoticia["catcolor"];
$hora = substr($datosnoticia["noticiafecha"],11,5);
$noticiafecha =FuncionesPHPLocal::ConvertirFecha($datosnoticia["noticiafecha"],"aaaa-mm-dd","dd/mm/aaaa");




$Top=0;
if (isset($objDataModel->Top) && $objDataModel->Top!=0)
	$Top = $objDataModel->Top;
	
//if (isset($objDataModel->Titulo) && $objDataModel->Titulo==2)
	//$titulo = $datosnoticia['noticiatitulocorto'];


if (isset($objDataModel->noticiaTitulo) && $objDataModel->noticiaTitulo!="")
	$titulo = utf8_decode($objDataModel->noticiaTitulo);
	
if (isset($objDataModel->Texto) && $objDataModel->Texto!="")
	$copete =  strip_tags(utf8_decode($objDataModel->Texto),"<strong>,<a>");


$conCategoria = false;
if (isset($objDataModel->conCategoria) && $objDataModel->conCategoria=="on")
	$conCategoria = true;

$multimediacod = "";
if (isset($objDataModel->multimediacod) && $objDataModel->multimediacod!="")
	$multimediacod = $objDataModel->multimediacod;

$conVolanta = false;
if (isset($objDataModel->conVolanta) && $objDataModel->conVolanta=="on")
	$conVolanta = true;

$conCuerpo = false;
if (isset($objDataModel->conCuerpo) && $objDataModel->conCuerpo=="on")
	$conCuerpo = true;	

$conFecha = false;
if (isset($objDataModel->conFecha) && $objDataModel->conFecha=="on")
	$conFecha = true;

$conAudio = false;
if (isset($objDataModel->conAudio) && $objDataModel->conAudio=="on")
	$conAudio = true;

$conRedesSociales = false;
if (isset($objDataModel->conRedesSociales) && $objDataModel->conRedesSociales=="on")
	$conRedesSociales = true;

$noticiaTipo = 1;
if (isset($objDataModel->noticiaTipo))	
	$noticiaTipo = $objDataModel->noticiaTipo;


$IconoVideo = false;
if (isset($objDataModel->IconoVideo) && $objDataModel->IconoVideo=="on")
	$IconoVideo = true;	

$conVideo = false;
if (isset($objDataModel->conVideo) && $objDataModel->conVideo=="on")
	$conVideo = true;	



$conFoto = false;
$oNoticiasMultimedia = new cNoticiasMultimedia($vars['conexion'],"");
if (isset($objDataModel->conFoto) && $objDataModel->conFoto=="on")
{	
	if ($multimediacod!="")
	{
		$datosnoticia['multimediacod'] = $multimediacod;
		if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoNoticiaxCodigoMultimedia($datosnoticia,$resultado,$numfilas))
			die();
			
		if ($numfilas!=0)
		{
			$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
			$conFoto = true;
		}
	}
}

if (isset($objDataModel->conAudio) && $objDataModel->conAudio=="on")
{	
	if(!$oNoticiasMultimedia->BuscarMultimediaAudiosxCodigoNoticia($datosnoticia,$resultado,$numfilas))
		die();
		
	if ($numfilas!=0)
	{
		$datosAudio = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
		$conAudio = true;
	}
}


$conVideo = false;
$urlVideo = "";
if (isset($objDataModel->conVideo) && $objDataModel->conVideo=="on")
{	
	if(!$oNoticiasMultimedia->BuscarMultimediaVideosxCodigoNoticia($datosnoticia,$resultado,$numfilas))
		die();
		
	if ($numfilas!=0)
	{
		$datosVideo = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
		$conVideo = true;

		switch($datosVideo['multimediatipocod'])
		{
			case VIM:
				$urlVideo = '<iframe class="MultimediaVideo" src="http://player.vimeo.com/video/'.$datosVideo['multimediaidexterno'].'" frameborder="0" allowfullscreen></iframe>';	
			break;	
			case YOU:
				$urlVideo = '<iframe class="MultimediaVideo" src="http://www.youtube.com/embed/'.$datosVideo['multimediaidexterno'].'" frameborder="0" allowfullscreen></iframe>';	
			break;	
		}
		$conFoto = false;
	}
}


$conBajada = false;
if (isset($objDataModel->conBajada) && $objDataModel->conBajada=="on")
	$conBajada = true;

$conVinculadas = false;
if (isset($objDataModel->conVinculadas) && $objDataModel->conVinculadas=="on")
{	
	$oNoticiasNoticias = new cNoticiasNoticias($vars['conexion'],"");
	if(!$oNoticiasNoticias->BuscarNoticiasRelacionadasPublicadasxNoticia($datosnoticia,$resultadorel,$numfilasrel))
		die();
		
	if ($numfilasrel!=0)
		$conVinculadas = true;
}
$FondoOscuro = false;
if (isset($objDataModel->FondoOscuro) && $objDataModel->FondoOscuro=="on")
	$FondoOscuro = true;

unset($oNoticia);


$tiponoticia = "notaSimple";
switch($noticiaTipo)
{
	case 1:
		$clase = "notaSimple"; //TITULO ARRIBA / FOTO IZQ / PARR DER
		break;
	case 2:
		$clase = "notaSecundaria"; // FOTO IZQ / TITULO + PARR DERECHA
		break;
	case 3:
		$clase = "notaDestacada"; // NOTICIA DESTACADA
		break;
	case 4:
		$clase = "notaSecundariaFotoPrimero"; // FOTO / TITULO / PARRAFO
		break;
	case 5:
		$clase = "TituloFotoParrafo"; // TITULO / FOTO / PARRAFO
		break;
}


$corteVolanta = 20;	

$anchoImg = 300;
if (isset($vars['width']) && $vars['width']!="")
{
	$anchoImg = $vars['width'];
}

?>
<div class="<?php  echo $clase?> tap_modules<?php  if ($FondoOscuro) echo " fondo_oscuro"?> clearfix" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
   	
<?php  switch($noticiaTipo){
	case 1: // noticia siempre?>	
        <h2>
            <a href="<?php  echo  $dominio?>"  target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
				<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
            </a>
        </h2>    
        <?php  if ($conVideo){?><div class="video"><?php  echo $urlVideo;?></div><?php  }?>
        <?php  if ($conFoto){?>
            <div class="foto">
                <a href="<?php  echo  $dominio?>"  target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                    <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic($anchoImg, 0, $datosFoto['multimediacatcarpeta']."N/".$datosFoto['multimediaubic']);?>" alt="Im&aacute;gen de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" />
           		</a>
                <?php  if ($IconoVideo){?>
	                <div class="ico-player-home">
                        <a href="<?php  echo  $dominio?>"  target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                            &nbsp;
                        </a>
                    </div>
                <?php  }?>
                
                <?php  if ($conRedesSociales) {?>
                    <div class="redessociales">
                                <div class="redessociales_header">
                                    <a href="javascript:void(0)" title=""></a>
                                </div>
                                <div class="redessociales_list">
                                    <ul>
                                    	<li class="twitter">
                                            <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                                &nbsp;
                                            </a>
                                        </li> 
                                        <li class="facebook">
                                            <a href="http://www.facebook.com/sharer.php?u=<?php  echo urlencode($dominiocompartir)?>&t=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" onclick="facebookDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" target="_blank" title="Compartir en Facebook">
                                                &nbsp;
                                            </a>
                                        </li>
                                        <li class="twitter">
                                            <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                                &nbsp;
                                            </a>
                                        </li> 
                                    </ul>
                                </div>
                        </div>
                <?php  }?>
           </div>
        <?php  }?>
        <?php  if ($conBajada) {?>
            <div class="copete"><?php  echo $copete?></div>
        <?php  }?>  
        <?php  if ($conCuerpo) {?>
            <div class="copete"><?php  echo $datosnoticia['noticiacuerpo']?></div>
        <?php  }?>    
        <?php  if ($conCategoria || $conVolanta || $conFecha){?>
        	<div class="clearboth">&nbsp;</div>
        	<div class="cajaCategoriaNota clearfix">
			<?php  if ($conCategoria){?>
                <div class="seccionTop" >
                    <a class="categoria" style="color:<?php  echo $catcolor?>" href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?></a>           
                </div>
            <?php  }?>
    
			<?php  if ($conVolanta){
                $volanta=FuncionesPHPLocal::cortar_string($datosnoticia['noticiavolanta'],$corteVolanta);
                if (trim($volanta)!=""){
                    ?>
                       <div class="volanta" <?php  if ($catcolor!="") { echo 'style="color:'.$catcolor.' !important"';}?>><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($volanta,ENT_QUOTES);?></div>
                    <?php  
                }
            }?>
            <?php  if ($conFecha){?>
                  <span class="hora"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($hora,ENT_QUOTES);?>hs&nbsp;</span>
            <?php  }?> 
            </div>
        <?php  }?>      
        <?php  if ($conVinculadas){?>
        	<div class="clearboth">&nbsp;</div>
        	<ul class="noticias_relacionadas">
        	<?php  
				while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadorel))
				{
					$targetrel = "_self";
					$dominiorel = DOMINIORAIZSITE.$fila['catdominio']."/".$fila["noticiadominio"];
					if ($fila['noticiahrefexternorel']!="")
					{
						$dominiorel = $fila['noticiahrefexternorel'];
						$targetrel = "_blank";
					}
					?>
                    <li>
                    	<a href="<?php  echo  $dominiorel?>" target="<?php  echo $targetrel?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>">
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	<strong>
							<?php  }?>
								<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	</strong>
							<?php  }?>
                        </a>
                    </li>
                    <?php  
				}
			?>
            </ul>
		<?php  }?>
        
        
    <?php  break;  


	case 2: ?>	
        <?php  if ($conVideo){?><div class="video"><?php  echo $urlVideo;?></div><?php  }?>
		<?php  if ($conFoto){?>
            <div class="foto">
                <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                    <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic($anchoImg, 0, $datosFoto['multimediacatcarpeta']."N/".$datosFoto['multimediaubic']);?>" alt="Im&aacute;gen de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" />
                </a>
                <?php  if ($IconoVideo){?>
	                <div class="ico-player-home">
                        <a href="<?php  echo  $dominio?>"  target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                            &nbsp;
                        </a>
                    </div>
                <?php  }?>
                
                <?php  if ($conRedesSociales) {?>
                    <div class="redessociales">
                            <div class="redessociales_header">
                                <a href="" title=""></a>
                            </div>
                            <div class="redessociales_list">
                                <ul>
                                    <li class="twitter">
                                        <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                            
                                        </a>
                                    </li> 
                                    <li class="facebook">
                                        <a href="http://www.facebook.com/sharer.php?u=<?php  echo urlencode($dominiocompartir)?>&t=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" onclick="facebookDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" target="_blank" title="Compartir en Facebook">
                                            
                                        </a>
                                    </li>
                                      
                                    <li class="twitter">
                                        <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                            
                                        </a>
                                    </li> 
                                </ul>
                            </div>
                    </div>
                <?php  }?>
            </div>
            <div class="fototitulo">
               <h2 class="conFoto">
				   	<a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
				   		<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
               	 	</a>
               </h2>
			   <?php  if ($conBajada) {?>
                    <div class="copete"><?php  echo $copete?></div>
               <?php  }?>  
              <div class="clearboth">&nbsp;</div>
            </div>   
        <?php  }else{?>
        	<?php  if ($conRedesSociales) {?>
                        <div class="redessociales">
                                <div class="redessociales_header">
                                    <a href="" title=""></a>
                                </div>
                                <div class="redessociales_list">
                                    <ul>
                                    	 <li class="twitter">
                                            <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                                
                                            </a>
                                        </li>
                                        <li class="facebook">
                                            <a href="http://www.facebook.com/sharer.php?u=<?php  echo urlencode($dominiocompartir)?>&t=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" onclick="facebookDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" target="_blank" title="Compartir en Facebook">
                                                
                                            </a>
                                        </li>
                                          
                                        <li class="twitter">
                                            <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                                
                                            </a>
                                        </li> 
                                    </ul>
                                </div>
                        </div>
               	<?php  }?>
        	<h2>
            <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
				<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
            </a>
            </h2>
			<?php  if ($conBajada) {?>
                <div class="copete"><?php  echo $copete?></div>
            <?php  }?>  
			<?php  if ($conCuerpo) {?>
                <div class="copete"><?php  echo $datosnoticia['noticiacuerpo']?></div>
            <?php  }?>    
		<?php  }?>
        <?php  if ($conCategoria || $conVolanta || $conFecha){?>
        	<div class="cajaCategoriaNota clearfix">
			<?php  if ($conCategoria){?>
                <div class="seccionTop">
                    <a class="categoria" style="color:<?php  echo $catcolor?>" href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?></a>           
                </div>
            <?php  }?>
    
			<?php  if ($conVolanta){
                $volanta=FuncionesPHPLocal::cortar_string($datosnoticia['noticiavolanta'],$corteVolanta);
                if (trim($volanta)!=""){
                    ?>
                       <div class="volanta" <?php  if ($catcolor!="") { echo 'style="color:'.$catcolor.' !important"';}?>><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($volanta,ENT_QUOTES);?></div>
                    <?php  
                }
            }?>
            <?php  if ($conFecha){?>
                  <span class="hora"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($hora,ENT_QUOTES);?>hs&nbsp;</span>
            <?php  }?> 
            </div>
        <?php  }?>  
        <?php  if ($conVinculadas){?>
        	<ul class="noticias_relacionadas">
        	<?php  
				while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadorel))
				{

					$targetrel = "_self";
					$dominiorel = DOMINIORAIZSITE.$fila['catdominio']."/".$fila["noticiadominio"];
					if ($fila['noticiahrefexternorel']!="")
					{
						$dominiorel = $fila['noticiahrefexternorel'];
						$targetrel = "_blank";
					}
					?>
                    <li>
                    	<a href="<?php  echo  $dominiorel?>" target="<?php  echo $targetrel?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>">
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	<strong>
							<?php  }?>
								<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	</strong>
							<?php  }?>
                        </a>
                    </li>
                    <?php  
				}
			?>
            </ul>
		<?php  }?>
    <?php  break; 

	case 3: ?>	

        <?php  if ($conVideo){?><div class="col-1"><div class="video"><?php  echo $urlVideo;?></div> </div><?php  }?>
        <?php  if ($conFoto){?>
            <div class="foto">
                 <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                    <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic($anchoImg, 0, $datosFoto['multimediacatcarpeta']."N/".$datosFoto['multimediaubic']);?>" alt="Im&aacute;gen de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" />
                 </a>   
                <?php  if ($IconoVideo){?>
                    <div class="ico-player-home">
                        <a href="<?php  echo  $dominio?>"  target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                            &nbsp;
                        </a>
                    </div>
                <?php  }?>
				<?php  if ($conCategoria){?>
                    <div class="categoria clearfix">
                        <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?></a>           
                    </div>
                <?php  }?>  
             </div>     
        <?php  }?>
		<div class="fototitulo">
        	<?php  if ($conVolanta || $conFecha ){?>
                <div class="cajaCategoriaNota clearfix">
                    <?php  if ($conFecha){?>
                          <span class="fecha"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiafecha,ENT_QUOTES);?>&nbsp;</span>
                    <?php  }?> 
					<?php  if ($conVolanta){
                        $volanta=$datosnoticia['noticiavolanta'];
                        if (trim($volanta)!=""){?>
                               <div class="volanta"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($volanta,ENT_QUOTES);?></div>
                         <?php  }
                    }?>
                </div>
            <?php  }?> 
            <h2> 
                <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
                </a>
            </h2>
            <?php  if ($conBajada) {?>
                <div class="copete"><?php  echo $copete?></div>
            <?php  }?> 
			<?php  if ($conCuerpo) {?>
                <div class="copete"><?php  echo $datosnoticia['noticiacuerpo']?></div>
            <?php  }?>              
            <?php  if ($conVinculadas){?>
                <ul class="noticias_relacionadas">
                <?php  
                    while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadorel))
                    {
						$targetrel = "_self";
						$dominiorel = DOMINIORAIZSITE.$fila['catdominio']."/".$fila["noticiadominio"];
						if ($fila['noticiahrefexternorel']!="")
						{
							$dominiorel = $fila['noticiahrefexternorel'];
							$targetrel = "_blank";
						}
                        ?>
                        <li>                 
                            <a href="<?php  echo  $dominiorel?>" target="<?php  echo $targetrel?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>">
                                <?php  if ($fila['noticiaimportante']==1){?>
                                    <strong>
                                <?php  }?>
                                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>
                                <?php  if ($fila['noticiaimportante']==1){?>
                                    </strong>
                                <?php  }?>
                            </a>
                        </li>
                        <?php  
                    }
                ?>
                </ul>
            <?php  }?>
			  <?php  /* if ($conRedesSociales) {?>
                <div class="redessociales">
                        <div class="redessociales_header">
                            <a href="" title="">Compartir</a>
                        </div>
                        <div class="redessociales_list">
                        <ul>
                            <li class="twitter">
                                <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                    witter
                                </a>
                            </li> 
                            <li class="facebook">
                                <a href="http://www.facebook.com/sharer.php?u=<?php  echo urlencode($dominiocompartir)?>&t=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" onclick="facebookDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" target="_blank" title="Compartir en Facebook">
                                    acebook
                                </a>
                            </li>
                              
                            <li class="twitter">
                                <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                    Google +
                                </a>
                            </li> 
                        </ul>
                       </div>
                </div>
            <?php  } */?>
        </div>    
    <?php  break;  ?>

	<?php  case 4: //notaSecundariaFotoPrimero?>	               
		
        <?php  if ($conVideo){?><div class="col-1"><div class="video"><?php  echo $urlVideo;?></div> </div><?php  }?>
        <?php  if ($conFoto){?>
            <div class="foto">
                 <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                    <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic($anchoImg, 0, $datosFoto['multimediacatcarpeta']."N/".$datosFoto['multimediaubic']);?>" alt="Im&aacute;gen de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" />
                 </a>   
                <?php  if ($IconoVideo){?>
                    <div class="ico-player-home">
                        <a href="<?php  echo  $dominio?>"  target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                            &nbsp;
                        </a>
                    </div>
                <?php  }?>
				<?php  if ($conCategoria){?>
                    <div class="categoria clearfix">
                        <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?></a>           
                    </div>
                <?php  }?>  
             </div>     
        <?php  }?>
		<div class="fototitulo">
        	<?php  if ($conVolanta || $conFecha ){?>
                <div class="cajaCategoriaNota clearfix">
                    <?php  if ($conFecha){?>
                          <span class="fecha"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiafecha,ENT_QUOTES);?>&nbsp;</span>
                    <?php  }?> 
					<?php  if ($conVolanta){
						$volanta=FuncionesPHPLocal::cortar_string($datosnoticia['noticiavolanta'],$corteVolanta);
                        if (trim($volanta)!=""){?>
                               <div class="volanta"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($volanta,ENT_QUOTES);?></div>
                         <?php  }
                    }?>
                </div>
            <?php  }?> 
            <h2> 
                <a href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
                </a>
            </h2>
            <?php  if ($conBajada) {?>
                <div class="copete"><?php  echo $copete?></div>
            <?php  }?> 
			<?php  if ($conCuerpo) {?>
                <div class="copete"><?php  echo $datosnoticia['noticiacuerpo']?></div>
            <?php  }?>              
            <?php  if ($conVinculadas){?>
                <ul class="noticias_relacionadas">
                <?php  
                    while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadorel))
                    {
						$targetrel = "_self";
						$dominiorel = DOMINIORAIZSITE.$fila['catdominio']."/".$fila["noticiadominio"];
						if ($fila['noticiahrefexternorel']!="")
						{
							$dominiorel = $fila['noticiahrefexternorel'];
							$targetrel = "_blank";
						}
                        ?>
                        <li>                 
                            <a href="<?php  echo  $dominiorel?>" target="<?php  echo $targetrel?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>">
                                <?php  if ($fila['noticiaimportante']==1){?>
                                    <strong>
                                <?php  }?>
                                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>
                                <?php  if ($fila['noticiaimportante']==1){?>
                                    </strong>
                                <?php  }?>
                            </a>
                        </li>
                        <?php  
                    }
                ?>
                </ul>
            <?php  }?>
			  <?php  /* if ($conRedesSociales) {?>
                <div class="redessociales">
                        <div class="redessociales_header">
                            <a href="" title="">Compartir</a>
                        </div>
                        <div class="redessociales_list">
                        <ul>
                            <li class="twitter">
                                <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                    witter
                                </a>
                            </li> 
                            <li class="facebook">
                                <a href="http://www.facebook.com/sharer.php?u=<?php  echo urlencode($dominiocompartir)?>&t=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" onclick="facebookDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" target="_blank" title="Compartir en Facebook">
                                    acebook
                                </a>
                            </li>
                              
                            <li class="twitter">
                                <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                    Google +
                                </a>
                            </li> 
                        </ul>
                       </div>
                </div>
            <?php  } */?>
        </div>    
    <?php  break;  ?>

	<?php  case 5: //TITULOFOTOPARRAFO?>
        <h2>
        	<a href="<?php  echo  $dominio?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" target="<?php  echo $target?>">
				<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
            </a>
        </h2>
		<?php  if ($conVideo){?><div class="video"><?php  echo $urlVideo;?></div><?php  }?>
        <?php  if ($conFoto){?>
            <div class="foto">
                 <a href="<?php  echo  $dominio?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>"  target="<?php  echo $target?>">
                     <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$datosFoto['multimediacatcarpeta']."S/".$datosFoto['multimediaubic'];?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFoto['multimediadesc'],ENT_QUOTES)?>" />
           		 </a>
				 <?php  if ($IconoVideo){?>
                    <div class="ico-player-home">
                        <a href="<?php  echo  $dominio?>"  target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>">
                            &nbsp;
                        </a>
                    </div>
                 <?php  }?>
                 
                 <?php  if ($conRedesSociales) {?>
                    <div class="redessociales">
                                <div class="redessociales_header">
                                    <a href="" title="">Compartir</a>
                                </div>
                                <div class="redessociales_list">
                                <ul>
                                    <li class="twitter">
                                        <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                            witter
                                        </a>
                                    </li>   
                                    <li class="facebook">
                                        <a href="http://www.facebook.com/sharer.php?u=<?php  echo urlencode($dominiocompartir)?>&t=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" onclick="facebookDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" target="_blank" title="Compartir en Facebook">
                                            acebook
                                        </a>
                                    </li>
                                    <li class="twitter">
                                        <a href="http://twitter.com/home?status=<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>+<?php  echo urlencode($dominiocompartir)?>" onclick="twitterDialog( '<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>', '<?php  echo $dominiocompartir?>' ); return false;" title="Compartir en Twitter" target="_blank" >
                                            Google +
                                        </a>
                                    </li> 
                                </ul>
                               </div>
                    </div>
                <?php  }?>
           </div>
        <?php  }?>
        <?php  if ($conCategoria || $conVolanta || $conFecha){?>
        	<div class="cajaCategoriaNota clearfix">
			<?php  if ($conCategoria){?>
                <div class="seccionTop">
                    <a class="categoria" style="color:<?php  echo $catcolor?>" href="<?php  echo  $dominio?>" target="<?php  echo $target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?></a>           
                </div>
            <?php  }?>
    
			<?php  if ($conVolanta){
                $volanta=FuncionesPHPLocal::cortar_string($datosnoticia['noticiavolanta'],$corteVolanta);
                if (trim($volanta)!=""){
                    ?>
                       <div class="volanta" <?php  if ($catcolor!="") { echo 'style="color:'.$catcolor.' !important"';}?>><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($volanta,ENT_QUOTES);?></div>
                    <?php  
                }
            }?>
            <?php  if ($conFecha){?>
                  <span class="hora"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($hora,ENT_QUOTES);?>hs&nbsp;</span>
            <?php  }?> 
            </div>
        <?php  }?>
        <?php  if ($conBajada) {?>
            <div class="copete"><?php  echo $copete?></div>
        <?php  }?>  
        <?php  if ($conCuerpo) {?>
            <div class="copete"><?php  echo $datosnoticia['noticiacuerpo']?></div>
        <?php  }?>          
        <?php  if ($conVinculadas){?>
        	<div class="icon icon-relacionadas">&nbsp;</div>
            <div class="clearfix brisa_vertical">&nbsp;</div>
        	<ul class="noticias_relacionadas">
        	<?php  
				while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadorel))
				{
					$targetrel = "_self";
					$dominiorel = DOMINIORAIZSITE.$fila['catdominio']."/".$fila["noticiadominio"];
					if ($fila['noticiahrefexternorel']!="")
					{
						$dominiorel = $fila['noticiahrefexternorel'];
						$targetrel = "_blank";
					}
					?>
                    <li>
                    	<a href="<?php  echo $dominiorel?>" target="<?php  echo $targetrel?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>">
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	<strong>
							<?php  }?>
								<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	</strong>
							<?php  }?>
                        </a>
                    </li>
                    <?php  
				}
			?>
            </ul>
		<?php  }?>
        
    <?php  break;  ?>
	<div style="clear:both; height:0px; font-size:0px;"></div>
<?php  }?>    
</div>
