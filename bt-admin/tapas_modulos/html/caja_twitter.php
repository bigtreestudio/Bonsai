<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);

}

?>
<div class="tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="caja_twitter">
		<div class="tituloTwitter">Seguime</div>
    	<div class="accesoTwitter">
        	<div class="iconoTwitter">
            	<a href="https://twitter.com/gmmarangoni" target="_blank" title="Ir al twitter de Gustavo Marangoni">Twitter de Gustavo Marangoni</a>
            </div>
        </div>
        <div class="twittermarangoni">
            <a href="https://twitter.com/gmmarangoni" target="_blank" title="Twitter de Gustavo Marangoni">@gmmarangoni</a>
        </div>
    </div>
    <div class="timelinetwitter">
		<a class="twitter-timeline" href="https://twitter.com/gmmarangoni" data-widget-id="463072769387683840">Tweets por @gmmarangoni</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    </div>
</div>
<?php  