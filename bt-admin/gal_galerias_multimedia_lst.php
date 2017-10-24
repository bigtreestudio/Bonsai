<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oGaleriasMultimedia = new cGaleriasMultimedia($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1'); 

$oGalerias = new cGalerias($conexion);
$oMultimedia = new cMultimedia($conexion,"/noticias");

if (isset($_POST['galeriacod']) && $_POST['galeriacod']!="")
{
	if(!$oGalerias->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	
	$galeriaedit = true;
	$datosgaleria = $conexion->ObtenerSiguienteRegistro($resultado);
	$galeriacod = $datosgaleria['galeriacod'];
	$galeriatitulo =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria['galeriatitulo'],ENT_QUOTES);
	$galeriadesc =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria['galeriadesc'],ENT_QUOTES);
	$estado = $datosgaleria['galeriaestadocod'];
	$multimediaconjuntocod = $datosgaleria['multimediaconjuntocod'];
}

switch($multimediaconjuntocod)
{
	case 1:
		
		if(!$oGaleriasMultimedia->BuscarMultimediaFotosxCodigoGaleria($datosgaleria,$resultado,$numfilas))
			die();
		?>

                <ul id="galeria_multimedia">
                <?php  
				$tabindex = 1;
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                {
					?>
                    	<li id="multimedia_<?php  echo $fila['multimediacod']?>" class="galeria_multimedia_item" >
                        <div class="accionessuperior">
                            <div class="orden mover" style="cursor:move;">
                                <img src="images/move_32.png" alt="Mover" />
                            </div>  
                            <div class="eliminarMultimedia">                     
                                <a href="javascript:void(0)" onclick="EliminarMultimedia(<?php  echo $fila['multimediacod']?>)">
                                    <img src="images/cross_32.png" alt="Eliminar" />
                                </a>
                            </div>
                        </div>
                        <div class="chk">
                        	<input type="checkbox" class="multcheck" name="multcheck_<?php  echo $fila['multimediacod']?>" id="multcheck_<?php  echo $fila['multimediacod']?>" value="<?php  echo $fila['multimediacod']?>" />
                        </div>
                        <div class="anchoimagen">
                            <img onclick="VisualizarMultimedia(<?php  echo $fila['multimediacod']?>)" src="<?php  echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" class="imagen_multimedia" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
                        </div>
						
                        <div class="txtDatosCarga">
                            <div class="nombrearchivo">
                                Titulo:
                                <input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galmultimediatitulo'],ENT_QUOTES)?>" style="width:96%"  name="multimediatitulo_<?php  echo $fila['multimediacod']?>" onchange="ModificarTituloMultimedia(<?php  echo $fila['multimediacod']?>)" id="multimediatitulo_<?php  echo $fila['multimediacod']?>"/>
                            </div>
                            <div class="clearboth fixalto">&nbsp;</div>
                            <div class="anchodescripcion">
                            <div class="descripcionarchivo">   
                               Descripci&oacute;n:
                             </div>  
                                <textarea name="multimediadesc_<?php  echo $fila['multimediacod']?>" tabindex="<?php  echo $tabindex?>" onchange="ModificarDescripcionMultimedia(<?php  echo $fila['multimediacod']?>)" id="multimediadesc_<?php  echo $fila['multimediacod']?>" cols="45" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galmultimediadesc'],ENT_QUOTES)?></textarea>
                            </div>
                        </div>
                           
						<div class="txtDatos">
                        	&nbsp;
                            <div class="clearboth fixalto">&nbsp;</div>
                         </div>                         
                        <div class="clear fixalto">&nbsp;</div>
                    </li>
                    <?php  
					$tabindex++;
                }	
                ?>
                </ul>
        <?php  
		break;	

	case 2:
		
		if(!$oGaleriasMultimedia->BuscarMultimediaVideosxCodigoGaleria($datosgaleria,$resultado,$numfilas))
			die();
		?>
               <ul id="galeria_multimedia">
                <?php  
				$tabindex = 1;
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                {
					?>
                 <div class="form">
                    <li id="multimedia_<?php  echo $fila['multimediacod']?>" class="galeria_multimedia_item">
                        <div class="accionessuperior">
                            <div class="orden mover" style="cursor:move;">
                                <img src="images/move_32.png" alt="Mover" />
                            </div>  
                            <div class="eliminarMultimedia">                     
                                <a href="javascript:void(0)" onclick="EliminarMultimedia(<?php  echo $fila['multimediacod']?>)">
                                    <img src="images/cross_32.png" alt="Eliminar" />
                                </a>
                            </div>
                        </div>
                        <div class="chk">
                        	<input type="checkbox" class="multcheck" name="multcheck_<?php  echo $fila['multimediacod']?>" id="multcheck_<?php  echo $fila['multimediacod']?>" value="<?php  echo $fila['multimediacod']?>" />
                        </div>
                        <div class="anchoimagen">
                            <img onclick="VisualizarMultimedia(<?php  echo $fila['multimediacod']?>)" src="<?php  echo $oMultimedia->DevolverDireccionImg($fila)?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
                            <div class="linkpreview clearfix">
                                <a  class="preview" href="javascript:void(0)" title="Subir preview de imagen" onclick="return BuscarPreview(<?php  echo $fila['multimediacod']?>)">
                                    <img src="images/camera.png" />
                                </a>
                                <a class="crosspreview" href="javascript:void(0)" title="Eliminar Preview de imagen" onclick="return EliminarPreview(<?php  echo $fila['multimediacod']?>)">
                                    <img src="images/camera_delete.png" alt="Eliminar" />
                                </a>
                            </div>
                            <div class="clear fixalto">&nbsp;</div>
                        </div>
						<div class="txtDatosCarga">
                            <div class="nombrearchivo">
                                Titulo:
                                <div class="fixalto">&nbsp;</div>
                                <input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galmultimediatitulo'],ENT_QUOTES)?>" name="multimediatitulo_<?php  echo $fila['multimediacod']?>" onchange="ModificarTituloMultimedia(<?php  echo $fila['multimediacod']?>)" id="multimediatitulo_<?php  echo $fila['multimediacod']?>"/>
                            </div>
                            <div class="clearboth fixalto">&nbsp;</div>
                            
                           <div class="anchodescripcion">
                                Descripci&oacute;n:
                                <div class="fixalto">&nbsp;</div>
                                <textarea name="multimediadesc_<?php  echo $fila['multimediacod']?>" tabindex="<?php  echo $tabindex?>" onchange="ModificarDescripcionMultimedia(<?php  echo $fila['multimediacod']?>)" id="multimediadesc_<?php  echo $fila['multimediacod']?>" cols="40" rows="3"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galmultimediadesc'],ENT_QUOTES)?></textarea>
                            </div>  
                         </div>                         
						<div class="txtDatos">
                        	&nbsp;
                            <div class="clearboth fixalto">&nbsp;</div>
                         </div>                         
                        <div class="clear fixalto">&nbsp;</div>
                    </li>
                    </div>
                    <?php  
					$tabindex++;
                }	
                ?>
                </ul>
        <?php  
		break;	

	case 3:
		
		if(!$oGaleriasMultimedia->BuscarMultimediaAudiosxCodigoGaleria($datosgaleria,$resultado,$numfilas))
			die();
		?>
                <ul id="galeria_multimedia">
                <?php  
				$tabindex=1;
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                {
                    ?>

                   <div class="form">
                    <li id="multimedia_<?php  echo $fila['multimediacod']?>" class="galeria_multimedia_item">
                        
                        <div class="accionessuperior">
                            <div class="orden mover" style="cursor:move;">
                                <img src="images/move_32.png" alt="Mover" />
                            </div>  
                            <div class="eliminarMultimedia">                     
                                <a href="javascript:void(0)" onclick="EliminarMultimedia(<?php  echo $fila['multimediacod']?>)">
                                    <img src="images/cross_32.png" alt="Eliminar" />
                                </a>
                            </div>
                        </div>
                        <div class="chk">
                        	<input type="checkbox" class="multcheck" name="multcheck_<?php  echo $fila['multimediacod']?>" id="multcheck_<?php  echo $fila['multimediacod']?>" value="<?php  echo $fila['multimediacod']?>" />
                        </div>
                        

                        <div class="anchoimagen">
                            <img onclick="VisualizarMultimedia(<?php  echo $fila['multimediacod']?>)" src="<?php  echo $oMultimedia->DevolverDireccionImg($fila);?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
                            <div class="linkpreview clearfix">
                                <a  class="preview" href="javascript:void(0)" title="Subir preview de imagen" onclick="return BuscarPreview(<?php  echo $fila['multimediacod']?>)">
                                    <img src="images/camera.png" />
                                </a>
                                <a class="crosspreview" href="javascript:void(0)" title="Eliminar Preview de imagen" onclick="return EliminarPreview(<?php  echo $fila['multimediacod']?>)">
                                    <img src="images/camera_delete.png" alt="Eliminar" />
                                </a>
                            </div>
                            <div class="clear fixalto">&nbsp;</div>
                        </div>
						<div class="txtDatosCarga">
                            <div class="nombrearchivo">
                                Titulo:
                                <input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galmultimediatitulo'],ENT_QUOTES)?>" style="width:96%"  name="multimediatitulo_<?php  echo $fila['multimediacod']?>" onchange="ModificarTituloMultimedia(<?php  echo $fila['multimediacod']?>)" id="multimediatitulo_<?php  echo $fila['multimediacod']?>"/>
                            </div>
                            <div class="clearboth fixalto">&nbsp;</div>
                            <div class="anchodescripcion">
                            <div class="descripcionarchivo">   
                               Descripci&oacute;n:
                             </div>  
                                <textarea name="multimediadesc_<?php  echo $fila['multimediacod']?>" tabindex="<?php  echo $tabindex?>" onchange="ModificarDescripcionMultimedia(<?php  echo $fila['multimediacod']?>)" class="textarea full" id="multimediadesc_<?php  echo $fila['multimediacod']?>" cols="40" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galmultimediadesc'],ENT_QUOTES)?></textarea>
                            </div>                   
                        </div>    
 						<div class="txtDatos">
                        	&nbsp;
                            <div class="clearboth fixalto">&nbsp;</div>
                         </div>                         
                        <div class="clear fixalto">&nbsp;</div>
                   </li>
               </div>

                    <?php  
                }	
				$tabindex++;
                ?>
                </ul>
        <?php  
		break;	
}
?>	
