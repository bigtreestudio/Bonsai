<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$oMultimedia = new cMultimedia($vars['conexion'],"");

$numfilas=0;
$AutoPlay = false;
$MuestraBotonera = 1;
$MuestraPaginador = 0;
$MuestraImgGrande=1;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);

	if (isset($objDataModel->galeriacod))
	{
		$galeriacod = $objDataModel->galeriacod;
		if ($objDataModel->AutoPlay==1)
			$AutoPlay = true;
		$MuestraBotonera = $objDataModel->MuestraBotonera;
		$MuestraPaginador = $objDataModel->MuestraPaginador;
		$MuestraImgGrande = $objDataModel->MuestraImgGrande;

		$oGaleriasMultimedia = new cGaleriasMultimedia($vars['conexion']);
		$datosgaleria['galeriacod'] = $galeriacod;
		if(!$oGaleriasMultimedia->BuscarMultimediaFotosxCodigoGaleria($datosgaleria,$resultado,$numfilas))
			die();

	}

}


?>
<div class="caja_texto carrouselhome tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="list_carousel responsive">
        <ul id="galeria_<?php  echo $vars['zonamodulocod']?>">
        	<?php  if ($numfilas>0){?>
				<?php  while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultado)){?>
                    <li>
                        <?php  if ($MuestraImgGrande==1){?>
                        	<a href="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$fila['multimediacatcarpeta']."L/".$fila['multimediaubic'];?>"  class="imagen_multimedia" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);?>" rel="galeria_rel_<?php  echo $vars['zonamodulocod']?>">
                        <?php  }?>
                            	<img src="<?php  echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);?>" />
                        <?php  if ($MuestraImgGrande==1){?>
                        	</a>
                        <?php  }?>
                    </li>
                <?php  }?>
            <?php  }?>
        </ul>
        <div style="clear:both; height:1px;">&nbsp;</div>
		<?php  if ($MuestraBotonera){?>
            <a class="prev" id="prev_<?php  echo $vars['zonamodulocod']?>" href="#"><span>anterior</span></a>
            <a class="next" id="next_<?php  echo $vars['zonamodulocod']?>" href="#"><span>siguiente</span></a>
        <?php  }?>
		<?php  if ($MuestraPaginador){?>
	        <div class="pagination" id="paginador_<?php  echo $vars['zonamodulocod']?>"></div>        
        <?php  }?>
        <div style="clear:both; height:1px;">&nbsp;</div>
    </div>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		$('#galeria_<?php  echo $vars['zonamodulocod']?>').carouFredSel({
			scroll: 1,
			items: {visible: {min: 2,max: 7	}},
			auto: {play: <?php  echo $AutoPlay ? 'true': 'false';?>},
			<?php  if ($MuestraBotonera){?>
				prev    : {
					button  : "#prev_<?php  echo $vars['zonamodulocod']?>",
					key     : "left"
				},
				next    : {
					button  : "#next_<?php  echo $vars['zonamodulocod']?>",
					key     : "right"
				},
			<?php  }?>
			<?php  if ($MuestraPaginador){?>
				pagination  : "#paginador_<?php  echo $vars['zonamodulocod']?>",
			<?php  }?>
			width: '100%'
		});
		<?php  if ($MuestraImgGrande==1){?>
			$("a.imagen_multimedia").fancybox({
				prevEffect		: 'none',
				nextEffect		: 'none',
				closeBtn		: true,
				nextClick		:false,
				helpers		: {
						title	: { type : 'inside' }
				},
				tpl: {
					next: '<a title="Next" class="fancybox-nav fancybox-next" id="next"><span></span></a>',
					prev: '<a title="Previous" class="fancybox-nav fancybox-prev" id="prev"><span></span></a>'
				}		
			});
		<?php  }?>
	});
	</script>
</div>

<?php  