<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$Calendario = 0;
?>
	<script type="text/javascript">
	$(function() {
		$( "#agendadatepicker1" ).datepicker({
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateText, inst) { 
					window.location = '<?php  echo DOMINIORAIZSITE?>agenda/' + dateText;
				}
		});
	});
	</script>

<?php  	

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);

		$Calendario = 1;
}
?>
<div style="text-align:left;">
    <div class="caja_agenda">
        <h3>Eventos</h3>
        <div style="margin-left:250px; margin-top:15px">
        	<div id="agendadatepicker1"></div>
        </div>
    </div>    
    
    <div style="clear:both">&nbsp;</div>

     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            </li>
        </ul>
    </div></div>
    
    <?php  
	$oAgenda = new cAgenda($conexion);

?>