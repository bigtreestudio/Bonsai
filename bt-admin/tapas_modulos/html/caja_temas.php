<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);

}

?>
<div class="tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="box_temas">
		<ul>
        	<li><a href="/lo-que-hago_t2" class="loquehago" title="Lo que hago">lo que hago</a></li>
        	<li class="margender"><a href="/radio" class="podcast" title="podcast">podcast</a></li>
        	<li><a href="lo-que-digo_t1" class="loquedigo" title="lo que digo">lo que digo</a></li>
        	<li class="margender"><a href="/libro" class="libro" title="libro">libro</a></li>
        	<li><a href="/lo-que-pienso_t3" class="loquepienso" title="lo que pienso">lo que pienso</a></li>
        	<li class="margender"><a href="/futbol" class="futbol" title="futbol">futbol</a></li>
		</ul>        
    </div>
    <div class="clearboth"></div>
</div>
<?php  