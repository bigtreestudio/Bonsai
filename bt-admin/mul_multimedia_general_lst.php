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


$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$datos = $_POST;
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['rolcod'] = $_SESSION['rolcod'];

$oMultimedia = new cMultimediaFormulario($conexion,$datos['prefijo'],$datos['codigo']);
if (!$oMultimedia->CargarListadoMultimedia($datos,$arreglo))
	return false;

$tipo = $oMultimedia->getTipoMultimedia();


$arregloTipos[FOTOS]['id'] = "multimedia_fotos";
$arregloTipos[FOTOS]['idSortable'] = "sortable_multimedia_fotos";
$arregloTipos[FOTOS]['txt'] = "Sin im&aacute;genes cargadas";
$arregloTipos[FOTOS]['iconoVideo'] = false;
$arregloTipos[FOTOS]['tienePreview'] = false;
$arregloTipos[FOTOS]['url'] = false;

$arregloTipos[VIDEOS]['id'] = "multimedia_videos";
$arregloTipos[VIDEOS]['idSortable'] = "sortable_multimedia_videos";
$arregloTipos[VIDEOS]['txt'] = "Sin videos cargados";
$arregloTipos[VIDEOS]['iconoVideo'] = true;
$arregloTipos[VIDEOS]['tienePreview'] = true;
$arregloTipos[VIDEOS]['url'] = false;

$arregloTipos[AUDIOS]['id'] = "multimedia_audios";
$arregloTipos[AUDIOS]['idSortable'] = "sortable_multimedia_audios";
$arregloTipos[AUDIOS]['txt'] = "Sin audios cargados";
$arregloTipos[AUDIOS]['iconoVideo'] = false;
$arregloTipos[AUDIOS]['tienePreview'] = true;
$arregloTipos[AUDIOS]['url'] = false;

$arregloTipos[FILES]['id'] = "multimedia_archivos";
$arregloTipos[FILES]['idSortable'] = "sortable_multimedia_archivos";
$arregloTipos[FILES]['txt'] = "Sin archivos cargados";
$arregloTipos[FILES]['iconoVideo'] = false;
$arregloTipos[FILES]['tienePreview'] = false;
$arregloTipos[FILES]['url'] = true;


if(count($arreglo)>0)
{	

	?>
	<ul id="<?php  echo $arregloTipos[$datos['tipo']]['id']?>">
	<?php  
		foreach ($arreglo as $fila)
		{
		?>
		<li id="multimedia_<?php  echo $fila['multimediacod']?>" class="<?php  echo $arregloTipos[$datos['tipo']]['idSortable']?>">
			<?php  if($fila['puedeeditar'] && $tipo['tieneorden']){?>
				<div class="float-left anchoorden orden" style="cursor:move">
					<div style="text-align:left">
						<img src="images/up.png" alt="Ordenar" />
					</div>
					<div style="text-align:left">
						<img src="images/down.png" alt="Ordenar" />
					</div>
				</div>
			<?php  } ?>
			<div class="float-left anchoimagen">
            	<?php  if ($arregloTipos[$datos['tipo']]['iconoVideo']){?>
            		<div class="play"><img src="images/play_large.png" alt="Play" /></div>
                <?php  }?>
				<img src="<?php  echo $fila['multimediaimg']?>" class="imagen_multimedia" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" <?php  if($arregloTipos[$datos['tipo']]['url'])echo "onclick='return AbrirPopupDominio(".$fila['multimediacod'].")'" ?>  />
			
               
				<?php  if ($arregloTipos[$datos['tipo']]['tienePreview'] && $fila['puedeeditar']){?>
                	<div class="linkpreview clearfix">
                        <a  class="preview" href="javascript:void(0)" title="Subir preview de imagen" onclick="return SeleccionarSubirMultimediaPreview(<?php  echo $fila['multimediacod']?>)">
                            <img src="images/camera.png" />
                        </a>
                        <a class="crosspreview" href="javascript:void(0)" title="Eliminar Preview de imagen" onclick="return EliminarPreview(<?php  echo $fila['multimediacod']?>)">
                            <img src="images/camera_delete.png" alt="Eliminar" />
                        </a>
                    </div>
					<div class="clear fixalto">&nbsp;</div>
				<?php  }?>
            
            </div>
			<div class="float-left anchodescripcion">
				<?php  if ($tipo['tienetitulo'] && $fila['puedeeditar']){?>
					<input type="text" maxlength="255" value="<?php  echo   FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediatitulo'],ENT_QUOTES);?>"  class="full" id="multimediatitulo_<?php  echo $fila['multimediacod']?>" name="multimediatitulo_<?php  echo $fila['multimediacod']?>" onchange="ModificarTituloListadoMultimedia(<?php  echo $fila['multimediacod']?>)" />
					<div class="clear fixalto">&nbsp;</div>
				<?php  }elseif ($tipo['tienetitulo']){?>
					 <div class="clear fixalto">&nbsp;</div><?php 
					 echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediatitulo'],ENT_QUOTES);
				}?>
				
				<?php  if ($tipo['tienedesc'] && $fila['puedeeditar']){?>
					<textarea name="multimediadesc_<?php  echo $fila['multimediacod']?>" class="textarea full" id="multimediadesc_<?php  echo $fila['multimediacod']?>" cols="10" rows="2"  onchange="ModificarDescripcionListadoMultimedia(<?php  echo $fila['multimediacod']?>)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES)?></textarea>
					<div class="clear fixalto">&nbsp;</div>
				<?php  }elseif ($tipo['tienedesc']){?>
					 <div class="clear fixalto">&nbsp;</div><?php 
					 echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);
				}?>
				
				<?php  if ($tipo['tienehome'] && $fila['puedeeditar']){?>
					<input type="checkbox" class="chkHome" style="width:20px !important; margin-top:8px" onclick="MultimediaSoloHome(<?php  echo $fila['multimediacod']?>)" <?php  if ($fila['home']==1) echo 'checked="checked"'?>  name="enhome_<?php  echo $fila['multimediacod']?>" value="1" id="enhome_<?php  echo $fila['multimediacod']?>" />
                    <label class="labelHome" for="enhome_<?php  echo $fila['multimediacod']?>" value="1" id="enhome_<?php  echo $fila['multimediacod']?>">Solo Home</label>
					<div class="clear fixalto">&nbsp;</div>
				<?php  }elseif($tipo['tienehome']){ echo ($fila['home']==1)?'Solo Home':'';}?>

			</div>
			<div class="clear fixalto">&nbsp;</div>
            <div class="footermultimedia">
                <div class="descripcion">
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
                </div>
                <?php  if($fila['puedeeditar']){?>
                <div class="linkeliminar">
                    <a href="javascript:void(0)" onclick="EliminarMultimedia(<?php  echo $fila['multimediacod']?>,<?php  echo $fila['multimediaconjuntocod']?>)">
                        <img src="images/cross.gif" alt="Eliminar" />
                    </a>
                </div>
                <?php  }?>
            </div>    
			<div class="clear fixalto">&nbsp;</div>
            
		</li>
		<?php  
		
	}	
	?>
	</ul>
	<?php  
}else
{
	?>	
		<b><?php  echo $arregloTipos[$datos['tipo']]['txt']?></b>
	<?php  
}

?>