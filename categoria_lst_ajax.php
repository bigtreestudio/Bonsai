<?php  

header('Content-Type: text/html; charset=ISO-8859-15'); 
	
$inicio = 0;
$cantidad = CANTIDADPAGINADO;
$paginaactual=1;
if (isset($_GET['pagina']) && $_GET['pagina']!='')
{
	if(strlen($_GET['pagina'])>10)
		die();
		
	if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['pagina'],"NumericoEntero"))
		die();
		
	$paginaactual = $_GET['pagina'];
	$pagina = $paginaactual-1;
	if ($pagina<0)
		$pagina=0;
	$inicio = $pagina*$cantidad;
}
if (!isset($_GET['dominio']) || $_GET['dominio']=='')
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

if(strlen($_GET['dominio'])>255)
	die();
	
$oCategoriasService = new cNoticiasCategorias($conexion);
$datos['catdominio'] = $_GET['dominio'];
$oCategorias = $oCategoriasService->BuscarCategoriaxDominio($datos);
if($oCategorias===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
$datosextra = $oCategorias->getDataJson();
//echo $catsuperior;

$limit = "LIMIT ".$inicio.",".$cantidad;
$oCategoriasService->CargarNoticiasxCategoria($oCategorias,$cantidaTotal,$limit);
$arreglonoticias = $oCategorias->getNoticias();

if (isset($_GET['pagina']))
{	
	$paginaactual = $_GET['pagina'];
}else
	$paginaactual = 1;
	
FuncionesPHPLocal::ObtenerValoresPaginado($cantidaTotal,$cantidad,$paginaactual,$cantidadtotal,$paginasiguiente,$paginaanterior);
$oNoticiaService = new cNoticias($conexion);



if (isset($datosextra->muestralistado) && $datosextra->muestralistado==1){?>
		<?php  if (count($arreglonoticias)){ $i=1; $alinear = "left"?>
			<?php  foreach ($arreglonoticias as $datosnoticia){?>
				<div class="noticia <?php  echo $alinear?> clearfix">
					<?php              
					$oNoticiaService->CargarImagenes($datosnoticia,1);
					$imagenes = $datosnoticia->getImagenes();
					?>
					<h2>
						<a href="<?php  echo DOMINIORAIZSITE?><?php  echo $datosnoticia->getDominioCategoria()."/".$datosnoticia->getDominio();?>" title="<?php  echo $datosnoticia->getTitulo()?>">
							<?php  echo $datosnoticia->getTitulo()?>
						</a>
					</h2>
					<?php  if (count($imagenes)>0){
						foreach ($imagenes as $imagen){
							
							$alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen->getDescripcion(),ENT_QUOTES);
							if ($alt=="")
								$alt = "Im&aacute;gen descriptiva";
							
							$imgUbic = DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic(165, 0, $imagen->getUbicacionURL("N/"));
							?>
							<div class="imagen"><img src="<?php  echo $imgUbic;?>" alt="<?php  echo $alt?>" /></div>
						<?php  }?>
					<?php  }?>
					<div class="copete"><?php  echo $datosnoticia->getCopete();?></div>
				</div>
			<?php  
				if ($i==1)
				{
					$alinear="right";
					$i=0;
				}else
				{
					$alinear="left";
					$i++;
				}
			}?>
		<?php  }?>
<?php  }else{?>
	<?php  
	$i=1;
	foreach ($arreglonoticias as $datosnoticia){ ?>
        <div class="noticia clearfix">
            <?php              
			$oNoticiaService->CargarImagenes($datosnoticia,1);
			$imagenes = $datosnoticia->getImagenes();
            
			if (count($imagenes)>0){?>
                <?php  foreach ($imagenes as $imagen){
					$alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($imagen->getDescripcion(),ENT_QUOTES);
					if ($alt=="")
						$alt = "Im&aacute;gen descriptiva";
					
					$imgUbic = DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic(165, 0, $imagen->getUbicacionURL("N/"));
					?>
                    <div class="imagen"><img src="<?php  echo $imgUbic;?>" alt="<?php  echo $alt?>" /></div>
                <?php  }?>
            <?php  }
			?>
            <h2>
                <a href="<?php  echo DOMINIORAIZSITE?><?php  echo $datosnoticia->getDominioCategoria()."/".$datosnoticia->getDominio();?>" title="<?php  echo $datosnoticia->getTitulo()?>">
                    <?php  echo $datosnoticia->getTitulo()?>
                </a>
            </h2>
            <div class="copete"><?php  echo $datosnoticia->getCopete();?></div>
        </div>
        <?php  if ($i==3){echo '<div class="clearboth">&nbsp;</div>'; $i=0;}else{$i++;} ?>
    <?php  }
 }?>      

  
<?php  if ($paginaactual<$cantidadtotal){?>
	<div class="paginado clearfix" id="Paginado">
		<div class="clearboth">&nbsp;</div>
		<div class="cargandoresultados">
			<div class="cargandoicono">&nbsp;</div>
			<div class="textocargando">Cargando <?php  echo $oCategorias->getNombre();?>...</div>
			<div class="clearboth">&nbsp;</div>
		</div>
		<div class="botoninferior">
			<a href="<?php  echo DOMINIORAIZSITE?><?php  echo $datos['catdominio']?>/<?php  echo $paginasiguiente?>" title="m&aacute;s <?php  echo $oCategorias->getNombre();?>" onclick="MasResultados('<?php  echo DOMINIORAIZSITE?><?php  echo $datos['catdominio']?>',<?php  echo  $paginasiguiente; ?>); return false;">M&aacute;s resultados</a>                    
		</div>    
	</div>    
<?php  }?>
		
