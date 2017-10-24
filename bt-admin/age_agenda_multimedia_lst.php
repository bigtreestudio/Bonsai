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

$oAgendaMultimedia = new cAgendaMultimedia($conexion,"");

$oMultimedia = new cMultimedia($conexion,"");

$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();

if (isset($_POST['agendacod']) && $_POST['agendacod']!="")
{
	$datos = $_POST;
	$datos['rolcod'] = $_SESSION['rolcod'];
	$datos['usuariocod']=$_SESSION['usuariocod'];
	$puedeeditar = false;

	switch($datos['tipo'])
	{
		case 1:
			
			if(!$oAgendaMultimedia->BuscarMultimediaFotosxCodigoEvento($datos,$resultado,$numfilas))
				die();
	
			if($numfilas>0)
			{	
				?>
				<ul id="multimedia_fotos">
				<?php  
				while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
				{
					?>
					<li id="multimedia_<?php  echo $fila['multimediacod']?>" class="sortable_multimedia_fotos">
						
							<div class="float-left anchoorden orden" style="cursor:move">
								<div style="text-align:left">
									<img src="images/up.png" alt="Ordenar" />
								</div>
								<div style="text-align:left">
									<img src="images/down.png" alt="Ordenar" />
								</div>
							</div>
						
						<div class="float-left anchoimagen">
							<img src="<?php  echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" class="imagen_multimedia" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
						</div>
						<div class="float-left anchodescripcion">
							<div class="descripcion">
								<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
							</div>
							<div class="linkeliminar">
								<a href="javascript:void(0)" onclick="EliminarMultimedia(<?php  echo $fila['multimediacod']?>,<?php  echo $fila['multimediaconjuntocod']?>,'#fotos')">
									<img src="images/cross.gif" alt="Eliminar" />
								</a>
							</div>
							<div class="clear fixalto">&nbsp;</div>
							<textarea name="multimediadesc_<?php  echo $fila['multimediacod']?>" class="textarea full" id="multimediadesc_<?php  echo $fila['multimediacod']?>" cols="10" rows="2"  onchange="ModificarDescripcionMultimedia(<?php  echo $fila['multimediacod']?>)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES)?></textarea>

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
					<b>No hay fotos cargadas</b>
				<?php  
			}
			break;	
	
		case 2:
			
			if(!$oAgendaMultimedia->BuscarMultimediaVideosxCodigoEvento($datos,$resultado,$numfilas))
				die();
			if($numfilas>0)
			{
			?>
			<ul id="multimedia_videos">
			<?php  
			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
			{
				?>
				<li id="multimedia_<?php  echo $fila['multimediacod']?>" class="sortable_multimedia_videos">
					<div class="float-left anchoorden orden" style="cursor:move">
						<div style="text-align:left">
							<img src="images/up.png" alt="Ordenar" />
						</div>
						<div style="text-align:left">
							<img src="images/down.png" alt="Ordenar" />
						</div>
					</div>
					<div class="float-left anchoimagen">
						<div class="play"><img src="images/play_large.png" alt="Play" /></div>
						<img src="<?php  echo $oMultimedia->DevolverDireccionThumbImgYoutube($fila['multimediaidexterno'])?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
					</div>
					<div class="float-left anchodescripcion">
						<div class="descripcion">
							<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
						</div>
						<div class="linkeliminar">
							<a href="javascript:void(0)" onclick="EliminarMultimedia(<?php  echo $fila['multimediacod']?>,<?php  echo $fila['multimediaconjuntocod']?>,'#videos')">
								<img src="images/cross.gif" alt="Eliminar" />
							</a>
						</div>
						<div class="clear fixalto">&nbsp;</div>
						<textarea name="multimediadesc_<?php  echo $fila['multimediacod']?>" class="textarea full" id="multimediadesc_<?php  echo $fila['multimediacod']?>" cols="10" rows="2" onchange="ModificarDescripcionMultimedia(<?php  echo $fila['multimediacod']?>)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES)?></textarea>
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
					<b>No hay videos cargados</b>
				<?php  
			}
			break;	
	
		case 3:
			
			if(!$oAgendaMultimedia->BuscarMultimediaAudiosxCodigoEvento($datos,$resultado,$numfilas))
				die();
			if ($numfilas>0)
			{
			?>
			<ul id="multimedia_audios">
			<?php  
			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
			{
				?>
				<li id="multimedia_<?php  echo $fila['multimediacod']?>" class="sortable_multimedia_audios">
					<div class="float-left anchoorden orden" style="cursor:move">
						<div style="text-align:left">
							<img src="images/up.png" alt="Ordenar" />
						</div>
						<div style="text-align:left">
							<img src="images/down.png" alt="Ordenar" />
						</div>
					</div>
					<div class="float-left anchoimagen">
						<img src="<?php  echo $oMultimedia->DevolverDireccionThumbImgAudio();?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
					</div>
					<div class="float-left anchodescripcion">
						<div class="descripcion">
							<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
						</div>
						<div class="linkeliminar">
							<a href="javascript:void(0)" onclick="EliminarMultimedia(<?php  echo $fila['multimediacod']?>,<?php  echo $fila['multimediaconjuntocod']?>,'#audios')">
								<img src="images/cross.gif" alt="Eliminar" />
							</a>
						</div>
					  
						<div class="clear fixalto">&nbsp;</div>
						<textarea name="multimediadesc_<?php  echo $fila['multimediacod']?>" class="textarea full" id="multimediadesc_<?php  echo $fila['multimediacod']?>" cols="10" rows="2" onchange="ModificarDescripcionMultimedia(<?php  echo $fila['multimediacod']?>)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES)?></textarea>
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
					<b>No hay audios cargados</b>
				<?php  
			}
			break;	
	
	}

}


?>