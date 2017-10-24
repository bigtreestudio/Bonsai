<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$PageFacebook = "";
$ShowFaces = 1;
$Stream =0;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);

	if (isset($objDataModel->PageFacebook))
		$PageFacebook  = $objDataModel->PageFacebook;
	if (isset($objDataModel->ShowFaces))
		$ShowFaces  = $objDataModel->ShowFaces;
	if (isset($objDataModel->Stream))
		$Stream  = $objDataModel->Stream;
	
}

?>
<div class="facebook_box tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<?php  if ($PageFacebook!=""){?>
        <div id="fb-root"></div>
		<div class="fb-like-box" data-href="<?php  echo $PageFacebook?>" data-width="345" data-colorscheme="light" data-show-faces="<?php  echo $ShowFaces?>" data-header="false" data-stream="<?php  echo $Stream?>" data-show-border="false"></div>
    <?php  }else{?>
    	<div style="clear:both; height:20px;">&nbsp;</div>
    <?php  }?>
</div>
<script type="text/javascript">jQuery(document).ready(function(){ try{ FB.XFBML.parse(); }catch(ex){} });</script>
<?php  