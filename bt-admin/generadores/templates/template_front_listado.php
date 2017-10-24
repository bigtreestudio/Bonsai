<?php 
$campoestado = "";
if($vars['campoEstado']!="" && $vars['TipoEliminacion']==1)
	$campoestado = $vars['campoEstado'];
	
?>
<script type="text/javascript" src="<? echo DOMINIORAIZSITE?>js/<?php   echo $vars['archivo']?>.js"></script>
<div class="formFiltros" id="DetallePagina">
	<div class="breadcrumb">
    	<ul>
        	<li>
            	<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($vars['titulo']),ENT_QUOTES); ?>
            </li>
        </ul>
    </div>
	<div class="leftcolumn_large">
        <div class="dataform">
            <h1>
				<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($vars['titulo']),ENT_QUOTES); ?>
            </h1>
            <div class="copete"></div>
        	<form action="<? echo DOMINIORAIZSITE?><?php   echo $vars['archivo']?>_busqueda.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
                 <?php   
            	if ($vars['tieneBusquedaAvanzada']){
        
                $i = 1;
                foreach($vars['camposBusquedaAvanzada'] as $clave=>$Campos)
                {
                   	
				    $esFecha=false;
                    $cantidadCampo = 255;
					$campotipo = $vars['otroscampos']['camposaltatipo_'.$Campos];
					
                    if (substr($vars['camposTabla'][$Campos]['Type'],0,4)=="date" || substr($vars['camposTabla'][$Campos]['Type'],0,8)=="datetime")
                    {	
                        $cantidadCampo = 10;
                        $esFecha=true;
                    }
                    if (substr($vars['camposTabla'][$Campos]['Type'],0,7)=="varchar")
                        $cantidadCampo = substr($vars['camposTabla'][$Campos]['Type'],8,strpos($vars['camposTabla'][$Campos]['Type'],")")-8);
                        
                    if (substr($vars['camposTabla'][$Campos]['Type'],0,8)=="smallint")
                        $cantidadCampo = substr($vars['camposTabla'][$Campos]['Type'],9,strpos($vars['camposTabla'][$Campos]['Type'],")")-9);
                    
                    if (substr($vars['camposTabla'][$Campos]['Type'],0,3)=="int")
                        $cantidadCampo = substr($vars['camposTabla'][$Campos]['Type'],4,strpos($vars['camposTabla'][$Campos]['Type'],")")-4);
    				
						
					if($i==1)
					{
					?>
						<div class="form-group clearfix">
					<?php
					}
					
					if($Campos!=$campoestado)
					{
					?>
					<?php if($campotipo==12){ 
                            $tabla = $vars['otroscampos']['tabla_'.$Campos];
                            $campo = $vars['otroscampos']['campofk_'.$Campos];
                            $desc = $vars['otroscampos']['campodesc_'.$Campos];
                            $NombreFuncion = $tabla."SP"; 
                    ?>
                    <div class="col-md-4">
                            <label><?php   echo htmlentities($vars['otroscampos']["nombrecampobusqueda_".$Campos],ENT_QUOTES);?>:</label>
                            <select class="form-control input-md" name="<? echo $Campos?>" id="<? echo $Campos?>">
                                <option value="">Todos...</option>
                                <?php
                                    echo "\n\t\t\t\t\t\t\t<?php while(\$filaCombo = \$conexion->ObtenerSiguienteRegistro(\$result_".$tabla.")){?>\n";
                                        echo "\t\t\t\t\t\t\t\t<option <?php if (isset(\$_SESSION['BusquedaAvanzada']['".$Campos."']) && \$filaCombo['".$campo."']==\$_SESSION['BusquedaAvanzada']['".$Campos."']) echo 'selected=\"selected\"'?> value=\"<?php echo \$filaCombo['".$campo."']?>\"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree(\$filaCombo['".$desc."'],ENT_QUOTES);?></option>\n";
                                    echo "\t\t\t\t\t\t\t<?php }?>\n";
                                    ?>
                            </select>

                     </div>
                     <?php }else{?>
                            
	                 <div class="col-md-4">
                        <label><?php   echo htmlentities($vars['otroscampos']["nombrecampobusqueda_".$Campos],ENT_QUOTES);?>:</label>
                        <input name="<?php   echo $Campos?>" id="<?php   echo $Campos?>" class="form-control input-md<?php   echo ($esFecha)?" fechacampo":""?>" type="text"  maxlength="<?php   echo $cantidadCampo?>" size="60" value="<?php echo "<?php echo (isset(\$_SESSION['BusquedaAvanzada']['".$Campos."'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree(\$_SESSION['BusquedaAvanzada']['".$Campos."'],ENT_QUOTES) : '';?>"; ?>" />
                     </div>
						<?php }?>
                    <?php
                    }
                    if ($i==3)
                    {
                        ?>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <?php   	
                        $i=1;
                    }else
                        $i++;
					
					}
				 }?>
                <div class="clearboth">&nbsp;</div>               
                <div class="col-md-5 row">
                    <input type="button" name="Enviar" class="btn btn-info btn-block"  value="Buscar" onclick="return Buscar(1)" />
                </div>   
                <div class="clearboth">&nbsp;</div>               
            </form> 
        </div>    
        <div class="clearboth">&nbsp;</div>
        	<table class="table">
            <thead>
                <tr>
                    <th class="archivo"> 
                        
                    </th>
                    <th class="denominacion">
                        Denominación
                    </th>
                    <th class="prestador">
                        Prestador
                    </th>
                </tr>
            </thead>
            <tbody id="resultadoslst"> 
              <? echo "<? include(\"".$vars['archivo']."_lst_ajax.php\")?>"; ?>
        	</tbody>
        	</table>
        	
        </div>  
        <div class="clearboth">&nbsp;</div>               
                      
    </div><!-- Cierre leftcolumn-->
	<div class="rightcolumn">
    	
    </div>
	<div class="clearboth"></div>
</div>