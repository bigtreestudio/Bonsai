<?php  

	
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

if (!isset($_GET['search']))
	$_GET['search'] = "";

$q = "";

if(strlen($_GET['search'])>500)
	die();
if (isset($_GET['search']))
	$q = $_GET['search'];

if (strlen(trim($q))==0)
{
	?>
		<div class="sindatos">Debe ingresar al menos un t&eacute;mino de b&uacute;squeda</div>
	<?php     
	die();	
}

$oBusqueda = new cBusqueda($conexion);

$limit = "LIMIT ".$inicio.",".$cantidad;

$arreglodatos = $oBusqueda->Busqueda($q,$CantidadTotal,$limit);
if (isset($_GET['pagina']))
{	
	$paginaactual = $_GET['pagina'];
}else
	$paginaactual = 1;



FuncionesPHPLocal::ObtenerValoresPaginado($CantidadTotal,$cantidad,$paginaactual,$cantidadtotal,$paginasiguiente,$paginaanterior);

?>
		<?php  if (count($arreglodatos)>0){ $i=1; ?>
			<?php  foreach ($arreglodatos as $oDetalle){?>
				<div class="noticia clearfix">
					<h2>
						<a href="<?php  echo DOMINIORAIZSITE?><?php  echo $oDetalle->getDominio();?>" title="<?php  echo $oDetalle->getTitulo()?>">
							<?php  echo  preg_replace('/('.str_replace(" ","|",$datosbusqueda['search']).')/i','<span class=palabraremarcada "">$1</span>' , $oDetalle->getTitulo()); ?>
                        </a>
					</h2>
					<div class="copete"><?php  
					$data = strip_tags($oDetalle->getCopete(),"<p><strong><em><i><s>");
					$data = preg_replace("/<p[^>]*>(&nbsp;|&nbsp;&nbsp;*?)<\\/p[^>]*>/",'',$data);
					$data = preg_replace("/<p[^>]*><\\/p[^>]*>/",'',$data);
					$data = preg_replace('/<p>(.*?)<\/p>/','$1<br />',$data);
					echo FuncionesPHPLocal::cortar_string($data,500);?></div>
				</div>
			<?php  
			}?>
		<?php  }?>
		<?php  
		if ($paginaactual<$cantidadtotal){?>
			<div class="paginado clearfix" id="Paginado">
				<div class="clearboth">&nbsp;</div>
				<div class="cargandoresultados">
					<div class="cargandoicono">&nbsp;</div>
					<div class="textocargando">Cargando b&uacute;squeda <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($q,ENT_QUOTES);?>...</div>
					<div class="clearboth">&nbsp;</div>
				</div>
				<div class="botoninferior">
					<a href="<?php  echo DOMINIORAIZSITE?>busqueda/<?php  echo urlencode($q)?>/<?php  echo $paginasiguiente?>" title="m&aacute;s b&uacute;squeda de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($q,ENT_QUOTES);?>" onclick="MasResultados('<?php  echo DOMINIORAIZSITE?>busqueda/<?php  echo  urlencode($q)?>',<?php  echo  $paginasiguiente; ?>); return false;">M&aacute;s resultados</a>
				</div>    
			</div>    
		<?php  }?>
		
