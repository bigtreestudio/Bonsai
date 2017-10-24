<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);
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

//if (isset($objDataModel->TipoCarrousel))
	//$TipoCarrousel = $objDataModel->TipoCarrousel;

?>
        
<div class="carrouselNoticias tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>

<?php  

switch ($TipoCarrousel)
{
	case 1: ?>	
        <div class="carousel_full">
            <ul id="noticiacarrousel_<?php  echo $vars['zonamodulocod']?>">
				<?php  
                    foreach ($objDataModel->noticiacod as $noticiacod)
                    {
                        $datosbusqueda['noticiacod'] = $noticiacod;
                        if(!$oNoticias->BuscarDatosCompletosNoticiasPublicadasxCodigo($datosbusqueda,$resultado,$numfilas))
                            return false;
                        
						$datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
					
						$titulo = $datosnoticia['noticiatitulo'];
						$catdominio = $datosnoticia["catdominio"];
						$dominio = $datosnoticia["noticiadominio"];
						$catnom = $datosnoticia["catnom"];
						$catcod = $datosnoticia["catcod"];
						$catcolor = $datosnoticia["catcolor"];						
						$conFoto = false;

						$url = DOMINIORAIZSITE.$catdominio."/".$dominio;
						$oNoticiasMultimedia = new cNoticiasMultimedia($vars['conexion'],"");
						if(!$oNoticiasMultimedia->BuscarMultimediaFotosxCodigoNoticia($datosnoticia,$resultadofotos,$numfilasfoto))
							die();
						
						if ($numfilasfoto!=0)
						{
							$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultadofotos);
							$conFoto = true;
							if (!file_exists(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datosFoto['multimediacatcarpeta']."BN/".$datosFoto['multimediaubic']))
								$conFoto = false;
						}
						
						
						
						?>
						<li>
						   <?php  if ($conFoto){?>
								<img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$datosFoto['multimediacatcarpeta']."BN/".$datosFoto['multimediaubic'];?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFoto['multimediadesc'],ENT_QUOTES)?>" />
						   <?php  }?>
						   <?php  if ($objDataModel->MuestraDescripcion->{$noticiacod}==1){?>
								<div class="<?php  echo $objDataModel->Alineacion->{$noticiacod}?>" style="width:<?php  echo $objDataModel->TamanioNoticia->{$noticiacod}?>%">
                                    <a href="<?php  echo $url?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES);?>">
                                        <h2><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?></h2>
                                    </a>
                                    <p>
                                    	<?php  echo strip_tags($datosnoticia['noticiacopete']);?>
                                    </p>
								</div>
							<?php  }?>
							<?php  if (!$conFoto && $objDataModel->MuestraDescripcion->{$noticiacod}!=1){?>
								<div>&nbsp;</div>
							<?php  }?>
						</li>
				   <?php  }?>
                </ul>
            <?php  if ($MuestraBotonera){?>
                <a class="prev" id="prev_<?php  echo $vars['zonamodulocod']?>" href="#"><span>anterior</span></a>
                <a class="next" id="next_<?php  echo $vars['zonamodulocod']?>" href="#"><span>siguiente</span></a>
            <?php  } ?>
            <div style="clear:both; height:1px;">&nbsp;</div>
        </div>
		<script type="text/javascript">
        jQuery(document).ready(function(){
			
            $('#noticiacarrousel_<?php  echo $vars['zonamodulocod']?>').carouFredSel({
                    auto: {play:<?php  echo $AutoPlay?>, delay:0.9, pauseDuration:5000},
                    scroll  : {duration        : 1},
                    direction   : "<?php  echo $Direccion?>",
                    <?php  if ($MuestraBotonera){?>
                        prev: '#prev_<?php  echo $vars['zonamodulocod']?>',
                        next: '#next_<?php  echo $vars['zonamodulocod']?>',
                    <?php  }?>	
                    height : "auto"
                    
            });
        
        });
        </script>
<?php 
	break;

	case 2:	
?>
        <div id="jslidernews_<?php  echo $vars['zonamodulocod']?>"  class="lof-slidecontent">
            <div class="preload"><div></div></div>
    
			<?php  if ($MuestraBotonera){?>    
                 <div  class="button-previous">Previous</div>
            <?php  }  ?> 
             <!-- MAIN CONTENT --> 
              <div class="main-slider-content">
                <ul class="sliders-wrap-inner">
					 <?php                                   
                            foreach ($objDataModel->noticiacod as $noticiacod)
                            {
                                $datosbusqueda['noticiacod'] = $noticiacod;
                                if(!$oNoticias->BuscarDatosCompletosNoticiasPublicadasxCodigo($datosbusqueda,$resultado,$numfilas))
                                    return false;
                                
                                $datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
                                
                                $muestraurl = false;
								if ($datosnoticia['noticiadominio']!="")
								{
									$muestraurl = true;
									$url = $datosnoticia["catdominio"]."/".$datosnoticia["noticiadominio"];;
								}
								
								$titulo = $datosnoticia['noticiatitulo'];
								$catdominio = $datosnoticia["catdominio"];
								$catnom = $datosnoticia["catnom"];
								$catcod = $datosnoticia["catcod"];
								$catcolor = $datosnoticia["catcolor"];						
								$conFoto = false;
		
								$oNoticiasMultimedia = new cNoticiasMultimedia($vars['conexion'],"");
								if(!$oNoticiasMultimedia->BuscarMultimediaFotosxCodigoNoticia($datosnoticia,$resultadofotos,$numfilasfoto))
									die();
								
								if ($numfilasfoto!=0)
								{
									$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultadofotos);
									$conFoto = true;
								}
								$html = "";
								if($conFoto){
									$nombrearchivo = $datosFoto['multimediaubic'];
									$pathinfo = pathinfo($nombrearchivo);
									$extension = strtolower($pathinfo['extension']);
									switch($extension)
									{
										case "jpg":
										case "png":
										case "gif":
											$html = '<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA.$datosFoto['multimediacatcarpeta']."CH/".$nombrearchivo.'" alt="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES).'">'; 
											break;
										case "swf":
											$html .= '';
	
											break;
									}
								}
                                ?>
                                <li>
                                    <?php  echo $html;?>
                                    <?php  if ($objDataModel->MuestraDescripcion->{$noticiacod}==1){?>
                                        <div class="slider-description <?php  echo $objDataModel->Alineacion->{$noticiacod}?>" style="width:<?php  echo $objDataModel->TamanioNoticia->{$noticiacod}?>%">
                                            <?php  if ($muestraurl){?>
                                                <a href="<?php  echo $url?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES);?>">
                                            <?php  }?>
                                                <h4><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?></h4>
                                            <?php  if ($muestraurl){?>
                                                </a>
                                            <?php  }?>
                                            <?php  if ($titulo!=""){?>
                                                <p><?php  echo strip_tags($datosnoticia['noticiacopete']);?></p>
                                            <?php  }else{?><p>&nbsp;</p><?php  }?>
                                        </div>
                                    <?php  }?>
                                </li>
                            <?php  }
                        ?>
                    </ul>
                                                                
                    </div>
                   <!-- END MAIN CONTENT --> 
                   <!-- NAVIGATOR -->
                    <div class="navigator-content">
                          <div class="navigator-wrapper">
                                <ul class="navigator-wrap-inner">
                                     <?php                                   
										foreach ($objDataModel->noticiacod as $noticiacod)
										{
											$datosbusqueda['noticiacod'] = $noticiacod;
											if(!$oNoticias->BuscarDatosCompletosNoticiasPublicadasxCodigo($datosbusqueda,$resultado,$numfilas))
												return false;
											
											$datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
											
                                
											$muestraurl = false;
											if ($datosnoticia['noticiadominio']!="")
											{
												$muestraurl = true;
												$url = $datosnoticia["catdominio"]."/".$datosnoticia["noticiadominio"];;
											}
											
											$titulo = $datosnoticia['noticiatitulo'];
											$catdominio = $datosnoticia["catdominio"];
											$catnom = $datosnoticia["catnom"];
											$catcod = $datosnoticia["catcod"];
											$catcolor = $datosnoticia["catcolor"];						
											$conFoto = false;
					
											$oNoticiasMultimedia = new cNoticiasMultimedia($vars['conexion'],"");
											if(!$oNoticiasMultimedia->BuscarMultimediaFotosxCodigoNoticia($datosnoticia,$resultadofotos,$numfilasfoto))
												die();
											
											if ($numfilasfoto!=0)
											{
												$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultadofotos);
												$conFoto = true;
											}
											$html = "";
											if($conFoto){
												$nombrearchivo = $datosFoto['multimediaubic'];
												$pathinfo = pathinfo($nombrearchivo);
												$extension = strtolower($pathinfo['extension']);
												switch($extension)
												{
													case "jpg":
													case "png":
													case "gif":
														$html = '<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA.$datosFoto['multimediacatcarpeta']."Thumbs/".$nombrearchivo.'" alt="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES).'">'; 
														break;
													case "swf":
														$html .= '';
				
														break;
												}
											}
											?>
											<li>
												<div>
													<h3><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?></h3>
													<div style="text-align:center">
													<?php  echo $html;?>
													</div>
												</div>
											</li>
										<?php  }
                                        ?>                         
                                </ul>
                          </div>
                     </div> 
                  <!----------------- END OF NAVIGATOR --------------------->
                 <?php  if ($MuestraBotonera){?>    
                     <div class="button-next">Next</div>
                <?php  }  ?> 
                 <!-- BUTTON PLAY-STOP -->
                  <div class="button-control"><span></span></div>
                  <!-- END OF BUTTON PLAY-STOP -->
             </div> 
			<script type="text/javascript">
                jQuery(document).ready(function(){
                    var buttons = { previous:$('#jslidernews_<?php  echo $vars['zonamodulocod']?> .button-previous'),
                                    next:$('#jslidernews_<?php  echo $vars['zonamodulocod']?> .button-next') };	
                                             
                    $('#jslidernews_<?php  echo $vars['zonamodulocod']?>').lofJSidernews( { 
                        interval:5000,
                        easing:'easeInOutQuad',
                        duration:1200,
                        auto:<?php  echo $AutoPlay?>,
                        mainWidth:447,
                        mainHeight:250,
                        navigatorHeight	: 125,
                        navigatorWidth	: 220,
                        maxItemDisplay:2,
                        <?php  if ($MuestraBotonera){?>    
                            buttons:buttons 
                        <?php  }?>
                    } );						
                });
			</script>
		
<?php 
		break;
		default:
			return true;
 }  ?> 
         
            
 </div>