<?php  

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$CantidadTotal = 5;
$Titulo = "";
$catcod="";

if (isset($vars['pagmodulocod']) || isset($vars['catmodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->CantidadTotal))
		$CantidadTotal = $objDataModel->CantidadTotal;
	
	if (isset($objDataModel->Titulo))
		$Titulo = $objDataModel->Titulo;

	if (isset($objDataModel->catcod))
		$catcod = $objDataModel->catcod;						
}

function MostrarArbolCategorias($arbol,$left,$arregloinsertados,$borde,$puedeeditar)
{
	foreach ($arbol as $fila)
	{
		if ($fila['catestado']==ACTIVO)
		{
			$muestrocheckeditar = false;
			$checked = '';
			if (array_key_exists($fila['catcod'],$arregloinsertados)){
				$muestrocheckeditar=true;
				$checked='checked="checked"';
			}
			$fontsize = "11px;";
			if ($left==0)
					$fontsize="12px";
			?>	
			<div>
				<?php  if (!$puedeeditar){ 
                        if ($muestrocheckeditar){?>
                            <label style="font-size:<?php  echo $fontsize?>" for="catcod_<?php  echo $fila['catcod']?>">-- <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)?></label>
                        <?php  } ?>
                <?php  }else{ ?>
                    <input type="checkbox" style="border:none" <?php  echo $checked?>  name="catcod_<?php  echo $fila['catcod']?>" id="catcod_<?php  echo $fila['catcod']?>" value="<?php  echo $fila['catcod']?>" />
                    <label style="font-size:<?php  echo $fontsize?>" for="catcod_<?php  echo $fila['catcod']?>"><span class="bordearbol"><?php  echo $borde?></span>&nbsp;<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES)?></label>
                    
                <?php 	}?>
            </div>
			<div class="clearboth" style="height:2px;">&nbsp;</div>
			<?php  		
			$left = $left + 1;
			$borde = $borde . "--";
			if (isset($fila['subarbol']) && count($fila['subarbol'])>0)
				MostrarArbolCategorias($fila['subarbol'],$left,$arregloinsertados,$borde,$puedeeditar);
			$left = $left - 1;	
			$borde = substr($borde,0,strlen($borde)-2);
		}
	}
	return true;
}	


function CargarCategorias($catcod,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"';?>  value="<?php  echo $fila['catcod']?>"><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php  
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($catcod,$fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}

?>
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	$("#ColorTitulo").miniColors();
	
});
</script>
<div style="text-align:left;">
	<div style="float:left; width:10%">
    	<label>Titulo:</label>
    </div>
	<div style="float:left; width:50%;">
    	<input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($Titulo),ENT_QUOTES)?>" id="Titulo" name="Titulo" maxlength="150" size="50" />
    </div>
    <div style="clear:both">&nbsp;</div>    
    <div style="float:left; width:20%">
    	<label>Cantidad Noticias:</label>
    </div>
	<div style="float:left; width:20%;">
    	<select name="CantidadTotal" id="CantidadTotal">
		<?php  for($i=1;$i<20;$i++){?>
        	<option value="<?php  echo $i?>" <?php  if ($i==$CantidadTotal) echo 'selected="selected"'?>><?php  echo $i?></option>
        <?php  }?>
        </select>
    </div>
    <div style="clear:both">&nbsp;</div>

    <div style="float:left; width:10%">
    	<label>Categoria:</label>
    </div>
	<div style="float:left; width:30%;">
                    <?php 
                        $oCategorias=new cCategorias($conexion);
                        $catsuperior="";
                        if (!$oCategorias->ArmarArbolCategorias($catsuperior,$arbol))
                            $mostrar=false;
					
					?>
                   
                    
                    <select name="catcod" id="catcod" style=" width:100%;">
                        <option value="">Seleccione una Categor&iacute;a...</option>
                    <?php 
                        foreach($arbol as $fila)
                        {
						
                            ?>
                            <option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php  echo $fila['catcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                            <?php  
                            if (isset($fila['subarbol']))
                            {
                                $nivel = "---";
                                CargarCategorias($catcod,$fila['subarbol'],$nivel);
                            }
                        }
                        ?>
                     </select>    
                 </div>     

    
    
    <div style="clear:both">&nbsp;</div>
     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            </li>
        </ul>
    </div></div>