<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$graficocod = "";
$oGraficos = new cGraficos($vars['conexion']);
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->graficocod))
	{
		$graficocod = $objDataModel->graficocod;
		$datosbusqueda['graficocod'] = $graficocod;
		if (!$oGraficos->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
			return false;
		
		$datosgrafico = $vars['conexion']->ObtenerSiguienteRegistro($resultado);	

		if (!$oGraficos->MostrarGrafico($datosgrafico,$stringcategorias,$stringseries,$stringdatosgrafico))
			return false;

	}
}
?>
<div class="caja_texto tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<script type="text/javascript">
	var categories<?php  echo $vars['zonamodulocod']?> = <?php  echo $stringcategorias;?>;
	var seriescarga<?php  echo $vars['zonamodulocod']?> = <?php  echo $stringseries;?>;
	var datosgrafico<?php  echo $vars['zonamodulocod']?> = <?php  echo $stringdatosgrafico;?>;
	
	jQuery(document).ready(function(){
		<?php  if ($datosgrafico['conjuntocod']==GRAFVALORES){?>
	        GraficoBarras('<?php  echo $datosgrafico['graficotipovalor']?>',<?php  echo $vars['zonamodulocod']?>,categories<?php  echo $vars['zonamodulocod']?>,seriescarga<?php  echo $vars['zonamodulocod']?>,datosgrafico<?php  echo $vars['zonamodulocod']?>);
		<?php  }else{?>
	        GraficoPorcentajes('<?php  echo $datosgrafico['graficotipovalor']?>',<?php  echo $vars['zonamodulocod']?>,categories<?php  echo $vars['zonamodulocod']?>,seriescarga<?php  echo $vars['zonamodulocod']?>,datosgrafico<?php  echo $vars['zonamodulocod']?>);
		<?php  }?>	
    });
    </script>
    <div id="container_<?php  echo $vars['zonamodulocod']?>"></div>
</div>
<?php  