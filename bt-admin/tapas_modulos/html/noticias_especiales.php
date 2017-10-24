<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$Direccion = "left";
$oMultimedia = new Multimedia($vars['conexion'],"");

$AutoPlay="false";
$MuestraBotonera=true;
$TipoCarrousel = 1;

//print_r($vars);
if (isset($objDataModel->Direccion))
	$Direccion = $objDataModel->Direccion;

if (isset($objDataModel->AutoPlay) && $objDataModel->AutoPlay==1)
	$AutoPlay = "true";

if (isset($objDataModel->MuestraBotonera) && $objDataModel->MuestraBotonera==0)
	$MuestraBotonera = false;

$CantidadPasaje=1;
if (isset($objDataModel->CantidadPasaje))
	$CantidadPasaje = $objDataModel->CantidadPasaje;


$Titulo="Noticias Especiales";
if (isset($objDataModel->Titulo))
	$Titulo = $objDataModel->Titulo;

	
$ColorFondo = "amarillo";
if (isset($objDataModel->ColorFondo))
	$ColorFondo = $objDataModel->ColorFondo;	
	
$ColorTexto = "textonegro";
if (isset($objDataModel->ColorTexto))
	$ColorTexto = $objDataModel->ColorTexto;	

$cantidad = 0;
$cantidadCarousel = 3;
?>
        
<div class="<?php  echo $ColorFondo?> <?php  echo $ColorTexto?> carrouselNoticiasEspeciales tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
		<?php  echo $vars['htmledit']?>
		<div class="titulo">
        	<h2><?php  echo $Titulo;?></h2>
        </div>
        <div style="clear:both; height:1px;">&nbsp;</div>
        <div class="carousel_especiales_full">
            <ul class="clearfix" id="noticiacarrousel_<?php  echo $vars['zonamodulocod']?>">
				<?php  
                    foreach ($objDataModel->noticiacod as $noticiacod)
                    {
                        $cantidad++;
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
						$archivoFoto="";
						$conFoto = false;
						if ($numfilasfoto!=0)
						{
							$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultadofotos);
							$anchoFoto = 290;
							$archivoFoto = DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic($anchoFoto, 0, $datosFoto['multimediacatcarpeta']."/N/".$datosFoto['multimediaubic']);
							$conFoto = true;
						}
						?>
						<li>
						   <?php  if ($conFoto){?>
								<div class="foto">
                                    <div class="redessociales">
                                                <div class="redessociales_header">
                                                    <a href="javascript:void(0)" title="Compartir">&nbsp;</a>
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
                                        <?php  if ($conFoto){?>
                                            <a  href="<?php  echo  $url?>"  title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES);?>">
                                                <img src="<?php  echo $archivoFoto?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFoto['multimediadesc'],ENT_QUOTES)?>" />
                                            </a>
                                        <?php  }?>
                                        <div class="cajaCategoriaNota clearfix">
                                            <div class="seccionTop">
                                                <a class="categoria" style="color:<?php  echo $catcolor?> !important" href="<?php  echo  $url?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);?></a>           
                                            </div>
                                            <div class="transparencia">&nbsp;</div>
                                        </div>                                
                                </div>    
						   <?php  }?>
                            <h3>
                                <a  href="<?php  echo  $url?>"  title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES);?>">
                                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
                                </a>    
                            </h3>
                            <p>
                                <?php  
								$parrafo = strip_tags(html_entity_decode($datosnoticia['noticiacopete']));
								$siguiente = "";
								$cantcaracteres = 200;
								if (strlen($parrafo)>$cantcaracteres)
									$siguiente = " [...]";
								echo substr($parrafo,0,$cantcaracteres).$siguiente;?>
                            </p>
						</li>
				   <?php  }?>
                </ul>
            <?php  if ($MuestraBotonera && $cantidad>$cantidadCarousel){?>
                <a class="prev" id="prev_<?php  echo $vars['zonamodulocod']?>" href="javascript:void(0)"><span>anterior</span></a>
                <a class="next" id="next_<?php  echo $vars['zonamodulocod']?>" href="javascript:void(0)"><span>siguiente</span></a>
            <?php  } ?>
            <div style="clear:both; height:1px;">&nbsp;</div>
        </div>
        <?php  if ($cantidad>$cantidadCarousel){?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				
				$('#noticiacarrousel_<?php  echo $vars['zonamodulocod']?>').carouFredSel({
						auto: {play:<?php  echo $AutoPlay?>, delay:0.9, timeoutDuration:5000},
						scroll  : {duration        : 1, items: <?php  echo $CantidadPasaje?>},
						direction   : "<?php  echo $Direccion?>",
						visible   : <?php  echo $cantidadCarousel?>,
						<?php  if ($MuestraBotonera){?>
							prev: '#prev_<?php  echo $vars['zonamodulocod']?>',
							next: '#next_<?php  echo $vars['zonamodulocod']?>',
						<?php  }?>	
						height : "100%"
				});
			});
        </script>
        <?php  }?>
 </div>