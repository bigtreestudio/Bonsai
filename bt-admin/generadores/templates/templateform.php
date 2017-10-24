<?php  

$campoestado = "";
if($vars['campoEstado']!="" && $vars['TipoEliminacion']==1)
	$campoestado = $vars['campoEstado'];
	
?>
<script type="text/javascript" src="modulos/<?php   echo $vars['archivo']?>/js/<?php   echo $vars['archivo']?>.js"></script>
<?php if($vars['classpanel']=="1"){?>
<div class="panel-style space">
<?php }?>	
<div class="form">
    <form action="<?php   echo $vars['archivo']?>.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h2><?php   echo htmlentities($vars['titulo'],ENT_QUOTES);?></h2>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
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
        </div>
        <?php
        if($campoestado!="")
		{?>
			<input type="hidden" name="<?php echo $campoestado?>" id="<?php echo $campoestado?>" value="<?php echo '<?php echo ACTIVO.",".NOACTIVO ?>'?>" /> 	
		<?php }
		?>
    </form>   
</div> 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra pull-left">
    <ul>
    	<li><div class="ancho_boton aire"><a class="boton verde" href="<?php   echo $vars['archivo']?>_am.php">Crear nuevo <?php   echo $vars['titulo']?></a></div></li>
        <li><div class="ancho_boton aire"><a class="boton azul" href="javascript:void(0)" onclick="gridReload()">Buscar</a></div></li>
    	<li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar</a></div></li>
    </ul>
</div>
<?php if($vars['tieneCsv']=="1"){?>
<div class="menubarra pull-right">
    <ul>
        <li><div class="ancho_boton aire"><a class="boton verde" href="<?php   echo $vars['archivo']?>_csv.php">Exportar CSV</a></div></li>
    </ul>
    <div class="clearboth">&nbsp;</div>
</div>    
<?php }?>


<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstDatos" style="width:100%;">
       <table id="listarDatos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<?php if($vars['classpanel']=="1"){?>
</div>
<?php  }?>	
