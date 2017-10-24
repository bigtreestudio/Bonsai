<?php 
$oFrases = new cFrases($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$Direccion = "left";

$AutoPlay="false";
$MuestraBotonera=true;



if (isset($objDataModel->AutoPlay) && $objDataModel->AutoPlay==1)
	$AutoPlay = "true";

if (isset($objDataModel->MuestraBotonera) && $objDataModel->MuestraBotonera==0)
	$MuestraBotonera = false;


//Preguntar donde esta BANNERFULL definido y donde se colocan las columnar?>
<div class="carrouselfrases tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="carrousel clearfix">
    <h2>Frases</h2>
	<?php  if ($MuestraBotonera){?>
        <a class="anterior" id="prev_<?php  echo $vars['zonamodulocod']?>" href="#"><span>anterior</span></a>
        <a class="siguiente" id="next_<?php  echo $vars['zonamodulocod']?>" href="#"><span>siguiente</span></a>
    <?php  }?>
    <div class="clearboth">&nbsp;</div>
   	<ul id="frasecarrousel_<?php  echo $vars['zonamodulocod']?>" class="detallefrases">
	<?php  
		foreach ($objDataModel->frasecod as $frasecod)
		{
			$datosbusqueda['frasecod'] = $frasecod;
			if(!$oFrases->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
				return false;
			
			$datosfrases = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
			?>
            <li>
                     <div class="fondoiconos frase">&nbsp;</div>
                     <q><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosfrases['frasedesclarga'],ENT_QUOTES)?></q>
                     <div class="clearboth">&nbsp;</div>
                     <div class="cita">
                     	<cite><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosfrases['fraseautor'],ENT_QUOTES)?></cite>
                     </div>
            </li>
		<?php  }
	?>
    </ul>
   
    <div style="clear:both; height:1px;">&nbsp;</div>
    </div>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		$('#frasecarrousel_<?php  echo $vars['zonamodulocod']?>').carouFredSel({
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
</div>
