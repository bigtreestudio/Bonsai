<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/maps/googlemaps.js"></script>

<?php  if ($mostrarmapa){?>
	<script language="javascript">
    var ObjMapa;
    $(document).ready(function() {
        ObjMapa = $("#divGoogleMaps").mapaBigTree({
                'zoom':	<?php  echo $oFormularios->getMapaZoom()?>,
                'lat':	<?php  echo $oFormularios->getLatitud()?>,
                'long':	<?php  echo $oFormularios->getLongitud()?>,
                'tipo': <?php  echo $oFormularios->getMapaTipo()?>,
                'MultipleMarkers':  false					
            }
        );
        ObjMapa.Inicializate();
        ObjMapa.AddMarker(<?php  echo $oFormularios->getLatitud()?>,<?php  echo $oFormularios->getLongitud()?>);
    });
    </script>
<?php  }?>

    <div class="caja_contactos">
        <h3>Contactos</h3>
        <ul>
            <?php  if(trim($oFormularios->getTelefono1())!=""){?>
            <li class="clearfix">
                <div class="datoscontacto">
                    <h4>Via telef&oacute;nica al <?php  echo $oFormularios->getTelefono1()?></h4>
                </div>
            </li>
            <?php  }?>
            <?php  if(trim($oFormularios->getDireccion())!=""){?>
            <li class="clearfix">
                <div class="datoscontacto">
                    <h4>Direcci&oacute;n: <?php  echo $oFormularios->getDireccion()?></h4>
                </div>
            </li>
            <?php  }?>
			<?php  if($oFormularios->getPiso()!=""){?>
                    <li class="clearfix">
                        <div class="datoscontacto">
                            <h4>Piso: <?php  echo $oFormularios->getPiso()?></h4>
                         </div>
                    </li>        
            <?php  }?>                
            <?php  if($oFormularios->getCodigoPostal()!=""){?>
                    <li class="clearfix">
                        <div class="datoscontacto">
                            <h4>C&oacute;digo Postal: <?php  echo $oFormularios->getCodigoPostal()?></h4>
                         </div>
                    </li>        
            <?php  }?>            

            <?php  if($oFormularios->getMail()!=""){?>
            <li class="clearfix">
                <div class="datoscontacto">
                    <h4>E-mail: <?php  echo $oFormularios->getMail()?></h4>
                 </div>
            </li>       
            <?php  }?> 
			<?php  if($oFormularios->getTelefono2()!=""){?>
                <li class="clearfix">
                    <div class="datoscontacto">
                        <h4>Tel&eacute;fono Auxiliar: <?php  echo $oFormularios->getTelefono2()?></h4>
                     </div>
                </li>        
            <?php  }?>
			<?php  if($oFormularios->getCelular()!=""){?>
                    <li class="clearfix">
                        <div class="datoscontacto">
                            <h4>Celular: <?php  echo $oFormularios->getCelular()?></h4>
                         </div>
                    </li>        
            <?php  }?>
            <?php  if($oFormularios->getWeb()!=""){?>
                    <li class="clearfix">
                        <div class="datoscontacto">
                            <h4>Web: 
                            	<a href="<?php  echo $oFormularios->getWeb()?>" target="_blank" title="Ir a <?php  echo $oFormularios->getWeb()?>">
									<?php  echo $oFormularios->getWeb()?>
                            	</a>    
                            </h4>
                         </div>
                    </li>        
            <?php  }?>
            <?php  if($oFormularios->getTwitter()!=""){?>
                    <li class="clearfix">
                        <div class="datoscontacto">
                            <h4>Twitter: 
								<a href="https://www.twitter.com/<?php  echo $oFormularios->getTwitter()?>" target="_blank" title="Twitter de <?php  echo $oFormularios->getTwitter()?>">
									<?php  echo $oFormularios->getTwitter()?>
                                </a>
                            </h4>
                         </div>
                    </li>        
            <?php  }?>
            <?php  if($oFormularios->getFacebook()!=""){?>
                    <li class="clearfix">
                        <div class="datoscontacto">
                            <h4>Facebook: <?php  echo $oFormularios->getFacebook()?>
                            </h4>
                         </div>
                    </li>        
            <?php  }?>
            
        </ul>
        <?php  if ($mostrarmapa){?>
            <div id="divGoogleMaps"></div>
        <?php  }?>
    </div>    
