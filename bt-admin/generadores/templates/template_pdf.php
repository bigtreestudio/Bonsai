<div class="ancho_10">
				<?php   echo"\n";
                    foreach ($vars['otroscampos']['campoalta'] as $camposAlta){
                        $campotipo = $vars['otroscampos']['camposaltatipo_'.$camposAlta];
                        switch($campotipo)
                        {
                            case "7":
                            case "8":
                            case "9":
                            case "10":
                            case "11":
                            break;
                            
                            case "12":
                                ?>
                                <?
								echo"\n\t\t";
                                echo '<div class="ancho_3 negrita">'.utf8_encode($vars['otroscampos']['camposaltadesc_'.$camposAlta]).'<div>';
                                echo"\n\t\t";
								echo '<div class="ancho_7 txtrespuesta">';
								echo "'.FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_encode(\$datos['".$campo."desc']),ENT_QUOTES).'";
								echo '</div>';
								echo"\n\t\t";
							   ?>
                                </div>
                                <div class="clearboth brisa_vertical">&nbsp;</div>
                                <?
                            break;
        
                            default:
                            echo '<div class="ancho_3 negrita">'.utf8_encode($vars['otroscampos']['camposaltadesc_'.$camposAlta]).'</div>';
                           	echo"\n\t\t";
							echo '<div class="ancho_7 txtrespuesta">';
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
                                { 
                                    echo "'.FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_encode(\$datos['".$camposAlta."']),ENT_QUOTES).'";
                                }
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="1")
          						{ 
                                    echo "'.utf8_encode(\$datos['".$camposAlta."']).'";
                                }
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="2")
        						{ 
                                    echo "'.utf8_encode(\$datos['".$camposAlta."']).'";
                                }
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="3")
                                {                                    
									echo "'.utf8_encode(\$datos['".$camposAlta."procesado']).'";

                                }
        
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="4")
                                {                                    
									echo "'.FuncionesPHPLocal::ConvertirFecha(\$datos['".$camposAlta."'],'aaaa-mm-dd','dd/mm/aaaa').'";

                                }
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="5")
        						{ ?>
                                <input type="radio" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>_si" <?php   echo "<?php   if (\$".$camposAlta."==1) echo 'checked=\"checked\"'?>"?>><label for="<?php   echo $camposAlta?>_si">Si</label><?php   echo "\n\t\t\t";?><input type="radio" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>_no" <?php   echo "<?php   if (\$".$camposAlta."==0) echo 'checked=\"checked\"'?>"?>><label for="<?php   echo $camposAlta?>_no">No</label><?php   echo "\n\t\t\t";?>
								<?php   }
                                if ($vars['otroscampos']['camposaltatipo_'.$camposAlta]=="6")
                                { ?>
                                <select class="form-control input-md" name="<?php   echo $camposAlta?>" id="<?php   echo $camposAlta?>"><option value="1"<?php   echo "<?php   if (\$".$camposAlta."==1) echo 'selected=\"selected\"'?>"?>>Si</option><?php   echo "\n\t\t\t";?><option value="0"<?php   echo "<?php   if (\$".$camposAlta."==0) echo 'selected=\"selected\"'?>"?>>No</option></select><?php   echo "\n\t\t\t";?>
								<?php   }
                                echo "\n\t\t\t";?><?php   echo"\n\r";
							
							}
                            echo '</div>';
							echo"\n\t\t";
							?>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <? 
                            break;
                        }
                     }?>
                <div class="clearboth">&nbsp;</div>
        	</form>
        </div>
       
