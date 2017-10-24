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

$oEncabezados = new cEncabezados($conexion);

$oNoticiasAcciones = new cNoticiasAcciones($conexion);
$oNoticiasPublicacion = new cNoticiasPublicacion($conexion);
$oNoticias = new cNoticias($conexion);
$oCategorias=new cCategorias($conexion);
$oNoticiasMultimedia = new cNoticiasMultimedia($conexion,"");
$oNoticiasPermisos = new cNoticiasPermisos($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 
if (!isset($_POST['noticiacod']) || $_POST['noticiacod']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['noticiacod'],"NumericoEntero"))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}

/*
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("noticiacod"=>$_GET['noticiacod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
*/
if(!$oNoticias->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;

$datos['usuariocod'] = $_SESSION['usuariocod'];
if(!$oNoticiasAcciones->ObtenerAccionesPermitidasxRol($datos,$resultadoacciones,$numfilasacciones))
	return false;

$datosnoticia = $conexion->ObtenerSiguienteRegistro($resultado);

	
$puededuplicar = false;
$datosbusqueda['noticiacod'] = $datosnoticia['noticiacod'];
$datosbusqueda['usuariocod'] = $_SESSION['usuariocod'];
if ($oNoticiasPermisos->PuedeBajaraEdicion($datosbusqueda))
	$puededuplicar = true;
	
	
$noticiacod = $datosnoticia['noticiacod'];
$catnom =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['catnom'],ENT_QUOTES);
$noticiatitulo =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES);
$noticiatitulocorto =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulocorto'],ENT_QUOTES);
$noticiahrefexterno = $datosnoticia['noticiahrefexterno'];
$noticiacopete = $datosnoticia['noticiacopete'];
$noticiacuerpo = $datosnoticia['noticiacuerpo'];
$noticiavolanta =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiavolanta'],ENT_QUOTES);
$noticiaautor =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiaautor'],ENT_QUOTES);
$noticiafecha= FuncionesPHPLocal::ConvertirFecha($datosnoticia['noticiafecha'],"aaaa-mm-dd","dd/mm/aaaa");
$noticiahora=substr($datosnoticia['noticiafecha'],11,2);
$noticiamin=substr($datosnoticia['noticiafecha'],14,2);
$noticiaestadocod = $datosnoticia['noticiaestadocod'];
$noticiabloqusuario = $datosnoticia['noticiabloqusuario'];
$noticiacopiacodorig = $datosnoticia['noticiacopiacodorig'];
$noticiacopiacod = $datosnoticia['noticiacopiacod'];
$usuariodioalta = $datosnoticia['usuariodioalta'];
$noticiafalta = $datosnoticia['noticiafalta'];
$estado = $datosnoticia['noticiaestadodesc'];
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['noticiaestadocod'] = $noticiaestadocod ;



$noticialatcarga="-34.651285198954135";//lat
$noticialngcarga="-58.77685546875";//long
$noticialat="";//lat
$noticialng="";//long
$noticiazoom=10;
$noticiatype="google.maps.MapTypeId.ROADMAP";
$noticiamuestramapa = $datosnoticia['noticiamuestramapa'];

$noticiadireccion = $datosnoticia['noticiadireccion'];
if ($datosnoticia['noticialat']!="")
	$noticialat = $datosnoticia['noticialat'];
if ($datosnoticia['noticialng'])
	$noticialng = $datosnoticia['noticialng'];
	
$ubicarmapa = false;	
if ($datosnoticia['noticialat']!="")
{
	$noticialat = $datosnoticia['noticialat'];
	$noticialatcarga=$datosnoticia['noticialat'];
}
if ($datosnoticia['noticialng'])
{
	$noticialngcarga=$datosnoticia['noticialng'];
	$noticialng = $datosnoticia['noticialng'];
}
if ($datosnoticia['noticialat']!="" && $datosnoticia['noticialng']!="")
{	
	$ubicarmapa = true;
	$noticiazoom = $datosnoticia['noticiazoom'];
	$noticiatype = $datosnoticia['noticiatype'];
	$noticiadireccion = $datosnoticia['noticiadireccion'];
}



$oTags = new cNoticiasTags($conexion);
if(!$oTags->BuscarTagsxNoticia($datosnoticia,$resultadotags,$numfilastags))
	return false;
	
$arreglotags = array();
while ($filatags = $conexion->ObtenerSiguienteRegistro($resultadotags))
	$arreglotags[] = $filatags['noticiatag'];
	
$tags = implode(", ",$arreglotags);	

if ($tags!="")
	$tags .=", ";

$oNoticiasTemas = new cNoticiasNoticiasTemas($conexion);
if(!$oNoticiasTemas->BuscarxCodigoNoticia($datosnoticia,$resultadotemas,$numfilastemas))
	return false;
	
$arreglotemas = array();
while ($filatemas = $conexion->ObtenerSiguienteRegistro($resultadotemas))
	$arreglotemas[] = $filatemas['tematitulo'];
	
$temas = implode(", ",$arreglotemas);	

if ($temas!="")
	$temas .=", ";	


$oNoticiasCategorias=new cNoticiasCategorias($conexion);
if(!$oNoticiasCategorias->BuscarCategoriasxNoticia($datosnoticia,$resultadocatrel,$numfilascatrel))
	return false;

$arreglocatrel = array();	
while ($filacatrel = $conexion->ObtenerSiguienteRegistro($resultadocatrel))
		$arreglocatrel[] = $filacatrel['catnom'];		

$catrel = implode(", ",$arreglocatrel);	

if ($catrel!="")
	$catrel .=", ";
	
?>
<script type="text/javascript">
	jQuery(document).ready(function(){$('#tabs').tabs();});
</script>

<div class="clear fixalto">&nbsp;</div>
<div id="DetalleNoticiaAm">
	<div class="datosnoticia">
	<div class="msgaccionnoticia">&nbsp;</div>
    <div class="menubarra">
        <ul class="accionesnoticia">
            <?php  
			if (!$noticiaduplicada)
			{
                $class = "left";			
                $i = 1;
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
                    if ($i==$numfilasacciones && $i>1)
                        $class="right";
						
					FuncionesPHPLocal::ArmarLinkMD5("not_noticias_bajar_publicacion.php",array("noticiacod"=>$noticiacod,"noticiaworkflowcod"=>$fila['noticiaworkflowcod'],"accion"=>1),$getbajar,$md5bajar);
					?>
                        <li class="states botonesaccion"><a class="boton base" href="not_noticias_bajar_publicacion.php?<?php  echo $getbajar?>"  class="<?php  echo $class?>" id="<?php  echo $fila['noticiaestadocodfinal']?>" rel="<?php  echo $fila['noticiaworkflowcod']?>" ><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiaaccion'],ENT_QUOTES);?></a></li>
                        <?php 
                    $i++;
                    $class = "middle";
                }
				 if ($acciondespublicar){?>
					<li ><a class="boton rojo" href="not_noticias_despublicar.php?<?php  echo $getdespublicar?>" onclick="if (!confirm('Esta seguro que desea despublicar la noticia?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotondespublicar,ENT_QUOTES);?></a></li>
				<?php  }?>
				<?php  if ($accioneliminar){?>
					<li ><a class="boton rojo"  href="not_noticias_eliminar.php?<?php  echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la noticia?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
				<?php  }?>
            <?php  }?>
            <li class="states botonesaccion"><a class="boton base" href="not_noticias.php">Volver</a></li>
        </ul>
    </div>
    <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Volanta</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
        	    <?php  echo $noticiavolanta;?>
            </div>
            <div class="clearboth aire_menor">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Titulo</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
        	    <?php  echo $noticiatitulo;?>
            </div>
            <div class="clearboth aire_menor">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Titulo Corto</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
        		<?php  echo $noticiatitulocorto;?>
            <div class="clearboth aire_menor">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Temas</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
                 <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($temas,ENT_QUOTES);?>
            <div class="clearboth aire_menor">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Bajada</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
                <div style="max-height:90px; overflow-y:auto;">
                   <?php  echo $noticiacopete?>
                </div>
            </div> 
            <div class="clearboth aire_menor">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Texto</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
                <div style="max-height:250px; overflow-y:auto;">
                   <?php  echo $noticiacuerpo?>
                </div>  
            </div>
            <div class="clearboth aire_menor">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Link Externo</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
        	    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiahrefexterno,ENT_QUOTES);?>

            <div class="clearboth aire_menor">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Tags - (Palabras Claves)</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
                 <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($tags,ENT_QUOTES);?>
            <div class="clearboth aire_menor">&nbsp;</div>
		
        
        <div class="clear aire_vertical">&nbsp;</div>
        <div class="menubarra">
			<div class="msgaccionnoticia">&nbsp;</div>
            <ul class="accionesnoticia">
				<?php  
                if (!$noticiaduplicada)
                {
					$conexion->MoverPunteroaPosicion($resultadoacciones,0);
                    $class = "left";			
                    $i = 1;
                    while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
                        if ($i==$numfilasacciones && $i>1)
                            $class="right";
                            
                        FuncionesPHPLocal::ArmarLinkMD5("not_noticias_bajar_publicacion.php",array("noticiacod"=>$noticiacod,"noticiaworkflowcod"=>$fila['noticiaworkflowcod'],"accion"=>1),$getbajar,$md5bajar);
                        ?>
                            <li class="states botonesaccion"><a class="boton base" href="not_noticias_bajar_publicacion.php?<?php  echo $getbajar?>"  class="<?php  echo $class?>" id="<?php  echo $fila['noticiaestadocodfinal']?>" rel="<?php  echo $fila['noticiaworkflowcod']?>" ><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiaaccion'],ENT_QUOTES);?></a></li>
                            <?php 
                        $i++;
                        $class = "middle";
                    }
					 if ($acciondespublicar){?>
						<li class="states botonesaccion"><a class="boton rojo" href="not_noticias_despublicar.php?<?php  echo $getdespublicar?>" onclick="if (!confirm('Esta seguro que desea despublicar la noticia?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotondespublicar,ENT_QUOTES);?></a></li>
						<?php  
						}
                     if ($accioneliminar){?>
                        <li class="states botonesaccion"><a class="boton rojo" href="not_noticias_eliminar.php?<?php  echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la publicacion?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
                    <?php  }?>
                <?php  }?>
                <li class="states botonesaccion"><a class="boton base" href="not_noticias.php">Volver</a></li>
            </ul>
        </div>
        <div class="clear fixalto">&nbsp;</div>
            <div class="datosadicionales">
                <h3 class="adicionales">Datos adicionales</h3>
                <div class="clearboth">&nbsp;</div>
                <div>
                    <h4>Noticias Relacionadas</h4>
					<?php  
                    $oNoticiasRelacionadas = new cNoticiasNoticias($conexion);
                    if(!$oNoticiasRelacionadas->BuscarNoticiasRelacionadasxNoticia($datosnoticia,$resultado,$numfilas)) {
                        die();
                    }
                    if ($numfilas>0)
                    {
                        echo '<ul class="noticiasrel">';
                        while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                        {
                            $destacado="";
                            if ($fila['noticiaimportante']==1)
                                $destacado = " class='notdestacada'";
                            echo '<li '.$destacado.'>'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES).'</li>';
                        }
                        echo '</ul>';	
                    }else
						echo "<div style='margin-left:40px; margin-top:10px;'><b>Sin Noticias Relacionadas</b></div>";
                    ?>
                </div>                 
                <div class="clearboth" style="height:20px;">&nbsp;</div>

                <div>
                    <h4>Galerias Relacionadas</h4>
					<?php  
					$oNoticiasGalerias = new cNoticiasGalerias($conexion);
                    if(!$oNoticiasGalerias->BuscarGaleriasRelacionadasxNoticia($datosnoticia,$resultado,$numfilas)) {
                        die();
                    }
                    if ($numfilas>0)
                    {
                        echo '<ul class="noticiasrel">';
                        while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                        {
                            $destacado="";
                            if ($fila['galeriaimportante']==1)
                                $destacado = " class='notdestacada'";
                            echo '<li '.$destacado.'>'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriatitulo'],ENT_QUOTES).'</li>';
                        }
                        echo '</ul>';	
                    }else
						echo "<div style='margin-left:40px; margin-top:10px;'><b>Sin Galerias Relacionadas</b></div>";
                    ?>
                </div>                 
                <div class="clearboth fixalto">&nbsp;</div>

                
			</div>
</div>

<?php  

if(!$oNoticiasMultimedia->BuscarMultimediaFotosxCodigoNoticia($datosnoticia,$resultadofotos,$numfilasfotos))
	die();

if(!$oNoticiasMultimedia->BuscarMultimediaVideosxCodigoNoticia($datosnoticia,$resultadovideos,$numfilasvideos))
	die();

if(!$oNoticiasMultimedia->BuscarMultimediaAudiosxCodigoNoticia($datosnoticia,$resultadoaudios,$numfilasaudios))
	die();

?>
<div class="datosextranoticia">

    <div class="multimedia">
	    <div id="tabs">
            <h3>Archivos Multimedia</h3>
            <ul>
                <li><a href="#fotos">Fotos</a></li>
                <li><a href="#videos">Videos</a></li>
                <li><a href="#audios">Audios</a></li>
            </ul>
            <div id="fotos">
                    <?php  
                    if ($numfilasfotos>0)
                    {
                    ?>
                        <ul id="multimedia_fotos">
                        <?php  
                        while ($fila = $conexion->ObtenerSiguienteRegistro($resultadofotos))
                        {
                            ?>
                            <li id="multimedia_<?php  echo $fila['multimediacod']?>" class="sortable_multimedia_audios">
                                <div class="float-left anchoimagen">
                                    <img src="<?php  echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" class="imagen_multimedia" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
                                </div>
                                <div class="float-left anchodescripcion">
                                    <div class="descripcion">
                                        <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
                                    </div>
                                     <div class="clear fixalto">&nbsp;</div><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);?>
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
						echo '<b>No hay fotos cargadas</b>';
					}
					?>
                &nbsp;
            </div>
            <div id="videos">
                    <?php  
                    if ($numfilasvideos>0)
                    {
                    ?>
                        <ul id="multimedia_videos">
                        <?php  
                        while ($fila = $conexion->ObtenerSiguienteRegistro($resultadovideos))
                        {
                            ?>
                            <li id="multimedia_<?php  echo $fila['multimediacod']?>" class="sortable_multimedia_audios">
                                <div class="float-left anchoimagen">
                                    <div class="play"><img src="images/play_large.png" alt="Play" /></div>
                                    <img src="<?php  echo $oMultimedia->DevolverDireccionThumbImgYoutube($fila['multimediaidexterno'])?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
                                </div>
                                <div class="float-left anchodescripcion">
                                    <div class="descripcion">
                                        <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
                                    </div>
                                     <div class="clear fixalto">&nbsp;</div><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);?>
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
						echo '<b>No hay videos cargados</b>';
					}
					?>
				&nbsp;
            </div>
            <div id="audios">
                    <?php  
                    if ($numfilasaudios>0)
                    {
                    ?>
                        <ul id="multimedia_audios">
                        <?php  
                        while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoaudios))
                        {
                            ?>
                            <li id="multimedia_<?php  echo $fila['multimediacod']?>" class="sortable_multimedia_audios">
                                <div class="float-left anchoimagen">
                                    <img src="<?php  echo $oMultimedia->DevolverDireccionThumbImgAudio();?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" />
                                </div>
                                <div class="float-left anchodescripcion">
                                    <div class="descripcion">
                                        <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
                                    </div>
                                     <div class="clear fixalto">&nbsp;</div><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);?>
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
						echo '<b>No hay audios cargados</b>';
					}
					?>
            	&nbsp;
            </div>
			
            <div class="labeldatoscomplementarios">
                <label>Estado:</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div id="estadonoticia">
                <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($estado,ENT_QUOTES);?>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Fecha:</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
                 <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiafecha,ENT_QUOTES);?>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Hora:</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
                <div class="float-left">
                  	<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiahora,ENT_QUOTES);?>
                </div>               
                <div class="brisa_horizontal float-left">&nbsp;</div>
                <div class="float-left">
	                <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiamin,ENT_QUOTES);?>
                 <span class='textonombredatos' style="font-size:8px;">&nbsp;(mm-ss) </span>
                </div>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Autor</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
                  <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiaautor,ENT_QUOTES);?>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Muestra el Mapa</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="aire">
            	<?php  if ($noticiamuestramapa==1) {echo 'SI';} else {echo "NO";}?>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Secci&oacute;n Principal</label>
            </div>
            <div class="clearboth fixalto">&nbsp;</div>
            <div class="aire">
            	<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catnom,ENT_QUOTES);?>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div class="labeldatoscomplementarios">
                <label>Secciones Relacionadas</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
                 <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catrel,ENT_QUOTES);?>
            <div class="clearboth fixalto">&nbsp;</div>
            <div class="aire">
            	
            </div>
       
            <div id="tabsMapa">
                <h3>Ubicaci&oacute;n de la noticia</h3>
                <div id="divGoogleMaps"  style="display:block;position:relative;width:90% !important; margin-left:5%;height:400px !important; margin:auto;"></div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="labeldatoscomplementarios">
                    <label>Latitud</label>
                </div>
                <div class="clearboth fixalto">&nbsp;</div>
                <div class="aire">
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticialat,ENT_QUOTES);?>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="labeldatoscomplementarios">
                    <label>Longitud</label>
                </div>
                <div class="clearboth fixalto">&nbsp;</div>
                <div class="aire">
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticialng,ENT_QUOTES);?>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="labeldatoscomplementarios">
                    <label>Direcci&oacute;n</label>
                </div>
                <div class="clearboth fixalto">&nbsp;</div>
                <div class="aire">
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($noticiadireccion,ENT_QUOTES);?>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                
			</div>
			<script language="javascript">
                var ObjMapa;
                $(document).ready(function() {
                    ObjMapa = $("#divGoogleMaps").mapaBigTree({
                            'zoom':	<?php  echo $noticiazoom?>,
                            'lat':	<?php  echo $noticialatcarga?>,
                            'long':	<?php  echo $noticialngcarga?>,
                            'tipo': <?php  echo $noticiatype?>,
                            'MultipleMarkers':  false					
                        }
                    );
                    ObjMapa.Inicializate();
                    <?php  
                    if ($ubicarmapa)
                    	{?>ObjMapa.AddMarker(<?php  echo $noticialat?>,<?php  echo $noticialng?>);<?php  }
                    ?>
                });
    
            </script>
            
        </div>
    </div>
                
</div>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div style="height:50px;">&nbsp;</div>
<?php  
 $oEncabezados->PieMenuEmergente();
?>