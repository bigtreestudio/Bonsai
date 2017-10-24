<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js"></script>
<script type="text/javascript" src="modulos/<?php   echo $vars['archivo']?>/js/<?php   echo $vars['archivo']?>_am.js"></script>
<?php 

$TieneMultimediaFoto ="0";
$TieneMultimediaVideo ="0";
$TieneMultimediaAudio ="0";
$TieneMultimediaArchivo = "0";
if ($vars['otroscampos']['TieneMultimedia']==1)
{
	foreach ($vars['otroscampos']['campoalta'] as $camposAlta)
	{
		switch ($vars['otroscampos']['camposaltatipo_'.$camposAlta])
		{
			case "8": $TieneMultimediaFoto ="1";
			break;	
			case "9": $TieneMultimediaVideo ="1";
			break;	
			case "10": $TieneMultimediaAudio ="1";
			break;
			case "11": $TieneMultimediaArchivo ="1";
?>
<script type="text/javascript" src="js/multimediaSelectorArchivos.js"></script>	
<?php  
			break;		
		}
	}
	
}
if($TieneMultimediaFoto=="1")
{
?>
<script type="text/javascript" src="js/multimediaSelectorFotos.js"></script>	
<?php  
}

if($TieneMultimediaVideo=="1")
{
?>
<script type="text/javascript" src="js/multimediaSelectorVideos.js"></script>	
<?php  
}

if($TieneMultimediaAudio=="1")
{
?>
<script type="text/javascript" src="js/multimediaSelectorAudios.js"></script>	
<?php  
}
if($TieneMultimediaArchivo=="1")
{
?>
<script type="text/javascript" src="js/multimediaSelectorArchivos.js"></script>	
<?php  
}

?>
<?php if($vars['classpanel']=="1"){?>
<div class="panel-style space">
<?php }?>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2><?php echo htmlentities($vars['otroscampos']['titulopantalla'],ENT_QUOTES); ?></h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
	<div class="form">
   		
        <div class="col-md-7 col-xs-12 col-sm-6">
			<form action="<?php   echo $vars['archivo']?>.php" method="post" name="formalta" id="formalta" >
				<?php   echo"\n";
                    $arrayoculto = array();
                    foreach ($vars['otroscampos']['campoalta'] as $camposAlta){
                        echo "\t\t\t";
                        $campotipo = $vars['otroscampos']['camposaltatipo_'.$camposAlta];
                        switch($campotipo)
                        {
                            case "7":
                                    $arrayoculto[$camposAlta]= $camposAlta;
                            case "8":
                            case "9":
                            case "10":
                            case "11":
                            break;
                            
                            case "12":
                                $tabla = $vars['otroscampos']['tabla_'.$camposAlta];
                                $campo = $vars['otroscampos']['campofk_'.$camposAlta];
                                $desc = $vars['otroscampos']['campodesc_'.$camposAlta];
                                $NombreFuncion = $tabla."SP";
                                ?>
                                <div class="form-group clearfix">
                                    <label for="<?php   echo $camposAlta?>"><?php   echo htmlentities($vars['otroscampos']['camposaltadesc_'.$camposAlta],ENT_QUOTES)?></label>
                                    <select class="form-control input-md" name="<? echo $camposAlta?>" id="<? echo $camposAlta?>">
                                        <option value="">Seleccione un <?php echo htmlentities($vars['otroscampos']['camposaltadesc_'.$camposAlta],ENT_QUOTES)?></option>
                                <?
                                echo "\n\t\t\t\t\t\t\t<?php while(\$filaCombo = \$conexion->ObtenerSiguienteRegistro(\$result_".$tabla.")){?>\n";
                                    echo "\t\t\t\t\t\t\t\t<option <?php if (\$filaCombo['".$campo."']==\$".$camposAlta.") echo 'selected=\"selected\"'?> value=\"<?php echo \$filaCombo['".$campo."']?>\"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree(\$filaCombo['".$desc."'],ENT_QUOTES);?></option>\n";
                                echo "\t\t\t\t\t\t\t<?php }?>\n";
                                ?>
                                    </select>
                                </div><div class="clearboth brisa_vertical">&nbsp;</div>
                                <?
                            break;
        
                            default:
                            ?><div class="form-group clearfix"><label for="<?php   echo $camposAlta?>"><?php   echo htmlentities($vars['otroscampos']['camposaltadesc_'.$camposAlta],ENT_QUOTES)?></label><?php   echo "\n\t\t\t";
        
                            if ($camposAlta!=$vars['codigo'])
                            {
                                $esFecha=false;
                                $cantidadCampo = 255;
                                if (substr($vars['camposTabla'][$camposAlta]['Type'],0,4)=="date" || substr($vars['camposTabla'][$camposAlta]['Type'],0,8)=="datetime")
                                {	
                                    $cantidadCampo = 10;
                                    $esFecha=true;
                                }
                                if (substr($vars['camposTabla'][$camposAlta]['Type'],0,7)=="varchar")
                                    $cantidadCampo = substr($vars['camposTabla'][$camposAlta]['Type'],8,strpos($vars['camposTabla'][$camposAlta]['Type'],")")-8);
                                    
                                if (substr($vars['camposTabla'][$camposAlta]['Type'],0,8)=="smallint")
                                    $cantidadCampo = substr($vars['camposTabla'][$camposAlta]['Type'],9,strpos($vars['camposTabla'][$camposAlta]['Type'],")")-9);
                                
                                if (substr($vars['camposTabla'][$camposAlta]['Type'],0,3)=="int")
                                    $cantidadCampo = substr($vars['camposTabla'][$camposAlta]['Type'],4,strpos($vars['camposTabla'][$camposAlta]['Type'],")")-4);
                            
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="0")
                                    { ?><input type="text" class="form-control input-md<?php   echo ($esFecha)?" fechacampo":""?>" maxlength="<?php   echo $cantidadCampo?>" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>" value="<?php   echo "<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree(\$".$camposAlta.",ENT_QUOTES)?>"?>" /><?php   echo "\n\t\t\t";?><?php   }
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="1")
        
                                    { ?><textarea class="form-control input-md" rows="6" cols="20" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>"><?php   echo "<?php   echo \$".$camposAlta."?>"?></textarea><?php   echo "\n\t\t\t";?><?php   }
        
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="2")
        
                                    { ?><textarea class="form-control input-md rich-text" rows="6" cols="20" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>"><?php   echo "<?php   echo \$".$camposAlta."?>"?></textarea><?php   echo "\n\t\t\t";?><?php   }
        
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="3")
        
                                    { ?>
                                            <div class="col-md-3 row">
                                                <div style="padding:5px">
                                                    <!--<strong>Frase Wide:</strong> (FW)texto (A)autor(A)(FW) <br /><br />-->
                                                    <strong>Foto Centro:</strong> @fotoC@ <br /><br />
                                                    <strong>Foto Izq:</strong> @fotoI@ <br /><br />
                                                    <strong>Foto Der:</strong> @fotoD@ <br /><br />
                                                    <strong>Video Centro:</strong> @videoC@ <br /><br />
                                                    <strong>Video Izq:</strong> @videoI@ <br /><br />
                                                    <strong>Video Der:</strong> @videoD@ <br /><br />
                                                </div> 	
                                            </div>
                                            <div class="col-md-9 row">
                                                <textarea class="form-control input-md rich-text-avanzado" rows="6" cols="20" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>"><?php   echo "<?php   echo \$".$camposAlta."?>"?></textarea>
                                            </div>
                                        <?php   echo "\n\t\t\t";?><?php  
                                    }
        
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="4")
        
                                    { ?><input type="text" class="form-control input-md fechacampo" maxlength="10" name="<?php   echo $camposAlta?>"  id="<?php   echo $camposAlta?>" value="<?php   echo "<?php   echo \$".$camposAlta."?>"?>" /><?php   echo "\n\t\t\t";?><?php   }
        
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="5")
        
                                    { ?><input type="radio" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>_si" <?php   echo "<?php   if (\$".$camposAlta."==1) echo 'checked=\"checked\"'?>"?>><label for="<?php   echo $camposAlta?>_si">Si</label><?php   echo "\n\t\t\t";?><input type="radio" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>_no" <?php   echo "<?php   if (\$".$camposAlta."==0) echo 'checked=\"checked\"'?>"?>><label for="<?php   echo $camposAlta?>_no">No</label><?php   echo "\n\t\t\t";?><?php   }
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="6")
                                    { ?><select class="form-control input-md" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>"><option value="1"<?php   echo "<?php   if (\$".$camposAlta."==1) echo 'selected=\"selected\"'?>"?>>Si</option><?php   echo "\n\t\t\t";?><option value="0"<?php   echo "<?php   if (\$".$camposAlta."==0) echo 'selected=\"selected\"'?>"?>>No</option></select><?php   echo "\n\t\t\t";?><?php   }
                                    echo "\n\t\t\t";?><?php   echo"\n\r";
        
                            }
                            ?>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <? 
                            break;
                        }
                     }?>
    
					<?php   echo "\n" ?>
                    <input type="hidden" name="<?php   echo $vars['codigo']?>" id="<?php   echo $vars['codigo']?>" value="<?php   echo "<?php   echo \$".$vars['codigo']."?>"?>" />
                    <?php if(count($arrayoculto)>0){
                        foreach($arrayoculto as $campooculto)
                        { ?>
                             <input type="hidden" name="<?php   echo $campooculto?>" id="<?php   echo $campooculto?>" value="<?php   echo "<?php   echo \$".$campooculto."?>"?>" />
                        <?php }
                    }?>
    
                	<div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="<?php   echo "<?php   echo \$onclick ?>" ?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="<?php   echo $vars['archivo']?>.php">Volver</a></div></li>
                        	<?php if($vars['tienePdf']=="1"){?>
							<?php   echo 'if(\$esmodif){';?>
                            <li><div class="ancho_boton aire"><a class="boton base" href="<?php   echo $vars['archivo']?>_pdf.php?<?php echo $vars['codigo'] ?>=\$<?php echo $vars['codigo']?>" target="_blank" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Visualizar pdf</a></div></li>
                           <?php   echo '}';?>
                           <?php  } ?>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                    <div class="msgaccionupd">&nbsp;</div>
                    <div class="menubarra pull-right">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton azul" href="<?php   echo $vars['archivo']?>_am.php">Crear nuevo <?php   $vars['otroscampos']['titulopantalla'];?></a></div></li>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>    
                    
                </div>
                <div class="clearboth">&nbsp;</div>
        	</form>
        </div>
        
        <div class="col-md-5 col-xs-12 col-sm-6">
		<?php   
		if ($vars['otroscampos']['TieneMultimedia']==1)	
		{
			echo "\n\r";
			?>
            <form action="<?php   echo $vars['archivo']?>.php" method="post" name="formaltamultimediasimple" id="formaltamultimediasimple" >
			<?php
            foreach ($vars['otroscampos']['campoalta'] as $camposAlta){
                echo "\t\t\t";
				if ($camposAlta!=$vars['codigo'])
				{ 
					$campotipo = $vars['otroscampos']['camposaltatipo_'.$camposAlta];
					if($campotipo==8 || $campotipo==9 || $campotipo==10 || $campotipo==11)
					{
						switch($campotipo)
						{
							case "8":
								$OnclickFuncionSeleccionar = 'return SeleccionarMultimediaRepositorioFotos(\''.$camposAlta.'\')';
								$OnclickFuncionEliminar = 'return EliminarMultimediaRepositorioFotos(\''.$camposAlta.'\',\''.$vars['codigo'].'\')';		
								$MensajeFuncionSeleccionar = 'Seleccione una Im&aacute;gen';
							break;
							case "9":
								$OnclickFuncionSeleccionar = 'return SeleccionarMultimediaRepositorioVideos(\''.$camposAlta.'\')';
								$OnclickFuncionEliminar = 'return EliminarMultimediaRepositorioVideos(\''.$camposAlta.'\',\''.$vars['codigo'].'\')';		
								$MensajeFuncionSeleccionar = "Seleccione un Video";
							break;
							case "10":
								$OnclickFuncionSeleccionar = 'return SeleccionarMultimediaRepositorioAudios(\''.$camposAlta.'\')';
								$OnclickFuncionEliminar = 'return EliminarMultimediaRepositorioAudios(\''.$camposAlta.'\',\''.$vars['codigo'].'\')';		
								$MensajeFuncionSeleccionar = "Seleccione un Audio";
							break;
							case "11":
								$OnclickFuncionSeleccionar = 'return SeleccionarMultimediaRepositorioArchivos(\''.$camposAlta.'\')';
								$OnclickFuncionEliminar = 'return EliminarMultimediaRepositorioArchivos(\''.$camposAlta.'\',\''.$vars['codigo'].'\')';		
								$MensajeFuncionSeleccionar = "Seleccione un Archivo";
							break;
						}//fin  swich
					?><div style="margin-bottom:10px;"><label for="<?php   echo $camposAlta?>"><?php   echo htmlentities($vars['otroscampos']['camposaltadesc_'.$camposAlta],ENT_QUOTES)?></label></div>
            		  	<div class="menubarra">
                            <ul>
                                <li><div class="ancho_boton aire"><a class="boton azul" href="javascript:void(0)" onclick="<?php   echo $OnclickFuncionSeleccionar;?>"><?php   echo $MensajeFuncionSeleccionar;?></a></div></li>
                            </ul>
                            <div class="clearboth">&nbsp;</div>
                        </div>
                          <div><input type="hidden" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>" value="<?php   echo "<?php   echo \$".$camposAlta."?>"?>" /></div>
                          <div class="clearboth brisa_vertical">&nbsp;</div>
                          <div id="multimediapreview_<?php   echo $camposAlta?>">
							<?php   echo '<?php   $titulomultimedia_'.$camposAlta.' = "" ?>';?>
                            <?php   echo "\n" ?>
							<?php   echo '<?php   if ($'.$camposAlta.'!=""){'?>
                            <?php   echo "\n\t\t\t\t\t\t\t\t\t";
                                echo '$datosBusqueda["multimediacod"] = $'.$camposAlta.';';
                                echo "\n\t\t\t\t\t\t\t\t";
                                echo 'if(!$oMultimedia->BuscarMultimediaxCodigo($datosBusqueda,$resultado,$numfilas))';
                                echo "\n\t\t\t\t\t\t\t\t\t";
                                echo '	return false;';
                                echo "\n\t\t\t\t\t\t\t\t\t";
                                echo '$datosMultimedia = $conexion->ObtenerSiguienteRegistro($resultado);';
								echo "\n\t\t\t\t\t\t\t\t\t";
                                echo '$html = $oMultimedia->VisualizarArchivoSimpleMultimedia($datosMultimedia);';
				 				echo "\n\t\t\t\t\t\t\t\t\t";
								echo 'echo $html;';
								echo "\n\t\t\t\t\t\t\t\t\t";
								echo '$titulomultimedia_'.$camposAlta.' = $datosMultimedia["multimedianombre"];';
								echo "\n\t\t\t\t\t\t\t\t\t";
								echo 'if($datosMultimedia["multimediatitulo"]!="")';
								echo "\n\t\t\t\t\t\t\t\t\t";
	                            echo '$titulomultimedia_'.$camposAlta.' = $datosMultimedia["multimediatitulo"];';
								echo "\n\t\t\t\t\t\t\t\t\t";
                                echo '?>';
                                echo "\n\t\t\t\t\t\t\t\t\t";
                            	echo "<?php   }?>";
								echo '<?php   $oculto_'.$camposAlta.'=\'style="display:none"\';';
								echo "\n\t\t\t\t\t\t\t\t\t";
								echo 'if ($esmodif && $'.$camposAlta.'!=""){$oculto_'.$camposAlta.'=\'\';} ?>';
								echo "\n\t\t\t\t\t\t\t\t\t".'<a id="multimediaeliminar_'.$camposAlta.'" <?php   echo $oculto_'.$camposAlta.'; ?>  href="javascript:void(0)" onclick="'.$OnclickFuncionEliminar.'"><img src="images/cross.gif"  alt="Eliminar"></a>';
								echo "\n\t\t\t\t\t\t\t\t\t";
								echo '<div><?php   echo utf8_encode($titulomultimedia_'.$camposAlta.'); ?></div>';
								echo "\n\t\t\t\t\t\t\t\t\t";
								echo '<div class="clearboth aire_vertical">&nbsp;</div>';
								echo "\n\t\t\t\t\t\t\t";?></div><?php  
								echo "\n\t\t";
						}//fin if
					}//fin if
				}//fin foreach
				?>
				</form>
                <?php
		} // fin if
		?>
                <div class="txt">Recuerde <strong>guardar</strong> para que se realicen los cambios</div>
                <?php 
					if ($vars['TieneClaseMultimedia']==1)	
					{
						 echo "<?	\$oMultimediaFormulario = new cMultimediaFormulario(\$conexion,\"".strtoupper($vars['PrefijoConfigMultimedia'])."\",\$".$vars['codigo'].");\n\r";
						 echo "echo \$oMultimediaFormulario->CargarBotonera();\n\r";
						 echo "echo \$oMultimediaFormulario->CargarListado();\n\r";
						 echo "?>\n\r";
					}
				
				
				?>
                
                
            </div>
        <div class="clearboth">&nbsp;</div>
    </div>
    <div class="clearboth">&nbsp;</div>
</div>
<?php if($vars['classpanel']=="1"){?>
</div>
<?php }?>