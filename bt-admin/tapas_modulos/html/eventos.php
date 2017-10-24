<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oAgenda = new cAgenda($vars['conexion']);


$CantidadEventos = 5;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->CantidadEventos) && is_int((int)$objDataModel->CantidadEventos))
		$CantidadEventos = $objDataModel->CantidadEventos;
}

$datosbusqueda['fechainicio'] = date('Y-m-d');
$datosbusqueda['limit'] = "LIMIT 0,".($CantidadEventos+7);

if(!$oAgenda->BuscarAgendaBusquedaFechaMayor($datosbusqueda,$resultadoagenda,$numfilas))
	return false;

?>
<div class="moduloeventos tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<div class="tituloeventos">
    	<h2>Pr&oacute;ximos eventos</h2>
    </div>
    <div class="eventoslst clearfix"  id="eventoslst_<?php  echo $vars['zonamodulocod']?>">
    	<ul>
        	<?php  while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadoagenda)){?>
            	<li>
                	<a href="javascript:void(0)" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['agendatitulo'],ENT_QUOTES)?>">
                    	<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['agendatitulo'],ENT_QUOTES)?>
                    </a>
                    <div class="eventofecha">
                    	<?php  echo FuncionesPHPLocal::ConvertirFecha($fila['agendafdesde'],"aaaa-mm-dd","dd/mm/aaaa")?> - <?php  echo $fila['horainicio'];?>Hs. - 
						<?php  echo FuncionesPHPLocal::ConvertirFecha($fila['agendafhasta'],"aaaa-mm-dd","dd/mm/aaaa")?> - <?php  echo $fila['horafin'];?>Hs.
                    </div>
            	</li>
            <?php  }?>
        </ul>
    </div>
	<script type="text/javascript">
        $(function() {$("#eventoslst_<?php  echo $vars['zonamodulocod']?>").jCarouselLite({vertical: true,hoverPause:true,visible: <?php  echo (int)$CantidadEventos?>,auto:100,speed:1000});});
    </script>
    
</div>
<?php  