<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oAgenda = new cAgenda($conexion);

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);

}
?>
	<script type="text/javascript">
	$(function() {
		$( "#agendadatepicker" ).datepicker({
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateText, inst) { 
					window.location = '<?php  echo DOMINIORAIZSITE?>agenda/' + dateText;
				}
		});
	});
	</script>
    <div class="agenda_home tap_modules"  id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
    <?php  echo $vars['htmledit']?>
         <div id="agendadatepicker"></div>
            <div class="vermas"><a href="<?php  echo DOMINIORAIZSITE?>agenda" title="Ver todos los eventos">Ver todos</a></div>
    		<div class="clearboth"></div>
    </div>    
    
    
<?php  	
?>