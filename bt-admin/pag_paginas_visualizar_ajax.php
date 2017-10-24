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
$oPaginasMultimedia = new cPaginasMultimedia($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 
$oPaginas = new cPaginas($conexion);

if (!isset($_POST['pagcod']) || $_POST['pagcod']=="")
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}


$pagcod = $_POST['pagcod'];
if (!$oPaginas->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la pagina por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datospagina = $conexion->ObtenerSiguienteRegistro($resultado);	

$pagcod = $datospagina['pagcod'];
$catcod = $datospagina['catcod'];
$catnom = $datospagina['catnom'];
$pagestadocod = $datospagina['pagestadocod'];	
$pagtitulo = $datospagina['pagtitulo'];
$pagsubtitulo = $datospagina['pagsubtitulo'];
$pagtitulocorto=$datospagina['pagtitulocorto'];
$pagcopete = $datospagina['pagcopete'];
$pagcuerpo = $datospagina['pagcuerpo'];
$estado = $datospagina['pagestadodesc'];
$pagtitulosuperior = $datospagina['pagtitulosuperior'];
if ($pagtitulosuperior=="")
	$pagtitulosuperior = "Raiz";

?>
<script type="text/javascript">
	jQuery(document).ready(function(){$('#tabs').tabs();});
</script>
<div id="contentedor_modulo">
    <div class="menubarra">
        <div class="msgaccionnoticia">&nbsp;</div>
        <ul>               
            <li>
                <a class="left" href="pag_paginas.php">Volver</a>
            </li>
        </ul>
    </div>
    <div class="clear fixalto" style="height:50px;">&nbsp;</div>

	<form action="javascript:void(0)" method="post" name="formulario">
    <div class="ancho_10">
         <div class="ancho_5">
            <div class="datosgenerales">
                <div style="font-size:14px; text-align:right">
                    <label>Estado: <span id="estadonombre" style="font-weight:normal"><?php  echo $estado?></span></label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <label>T&iacute;tulo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="pagtitulo" id="pagtitulo" class="full" disabled="disabled" maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagtitulo,ENT_QUOTES);?>" />
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Sut&iacute;tulo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="pagsubtitulo" id="pagsubtitulo" class="full" disabled="disabled" maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagsubtitulo,ENT_QUOTES);?>" />
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>T&iacute;tulo corto:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="pagtitulocorto" id="pagtitulocorto" class="full" disabled="disabled" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagtitulocorto,ENT_QUOTES);?>" />
                </div>
                <div>
                    <label>Categor&iacute;a:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="catcod" id="catcod" class="full" disabled="disabled" maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catnom,ENT_QUOTES);?>" />
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Superior:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="pagtitulosuperior" id="pagtitulosuperior" class="full" disabled="disabled" maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagtitulosuperior,ENT_QUOTES);?>" />
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Copete:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <div style="max-height:120px; overflow-y:auto; background-color:#EBEBE4; border:1px solid #CCC">
                       <?php  echo $pagcopete?>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Cuerpo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <div style="max-height:120px; overflow-y:auto; background-color:#EBEBE4; border:1px solid #CCC">
                       <?php  echo $pagcuerpo?>
                    </div>
               </div>
           </div>
        </div>

       <div class="ancho_05">&nbsp;</div>
        <div class="ancho_4">

<?php 
if(!$oPaginasMultimedia->BuscarMultimediaFotosxCodigoPagina($datospagina,$resultadofotos,$numfilasfotos))
	die();

if(!$oPaginasMultimedia->BuscarMultimediaVideosxCodigoPagina($datospagina,$resultadovideos,$numfilasvideos))
	die();

if(!$oPaginasMultimedia->BuscarMultimediaAudiosxCodigoPagina($datospagina,$resultadoaudios,$numfilasaudios))
	die();

?>

<div class="datosextrapagina">

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
        </div>
      </div>             
 </div>      
            <h2 style="font-size:14px; font-weight:bold; margin-bottom:3px;">M&oacute;dulos Asignados</h2>
            <div>
                <div  class="clearfix" style="list-style:none; border:1px solid #CCC; border:2px #000 dashed; min-height:50px;">
					<ul>
						<?php 
                        $oPaginasModulos = new cPaginasModulos($conexion);
                        if(!$oPaginasModulos->BuscarxPagina($datospagina,$resultado,$numfilas))
                            return false;
                        
                        
                        while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                        {
                            ?>
                                <li style="border-bottom:1px dashed #CCC; padding:10px 0;">
                                    <div style=" text-align:center; font-weight:bold; font-size:14px">
                                        <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['modulodesc'],ENT_QUOTES);?>
                                    </div>
                                </li>
                            <?php  
                        }     
                        ?>   
                    </ul>   
                    <div class="clearboth">&nbsp;</div> 
                </div>
            </div>
        </div>
   <div class="clear aire_vertical">&nbsp;</div>
    </div>
    </form>
    <div class="menubarra">
        <div class="msgaccionnoticia">&nbsp;</div>
        <ul>               
            <li>
                <a class="left" href="pag_paginas.php">Volver</a>
            </li>
        </ul>
    </div>
    <div class="clear fixalto">&nbsp;</div>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<?php 
?>