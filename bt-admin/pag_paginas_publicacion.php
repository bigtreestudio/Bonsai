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
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oPaginasPublicacion = new cPaginasPublicacion($conexion);
$oPaginasWorkflowRoles = new cPaginasWorkflowRoles($conexion);
$oPaginas = new cPaginas($conexion);
$oPaginasMultimedia = new cPaginasMultimedia($conexion,"");
$oMultimedia = new cMultimedia($conexion,"");


if (!isset($_GET['pagcod']) || $_GET['pagcod']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['pagcod'],"NumericoEntero"))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("pagcod"=>$_GET['pagcod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

if(!$oPaginas->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;

$datospagina = $conexion->ObtenerSiguienteRegistro($resultado);


if(!$oPaginasPublicacion->EsPaginaPublicada($datospagina,$resultadopagpub,$numfilas))
	return false;

$datospaginapublicada = $conexion->ObtenerSiguienteRegistro($resultadopagpub);


$paginaduplicada = false;
if ($datospagina['pagcopiacod']!="")
	$paginaduplicada = true;

$pagcod = $datospagina['pagcod'];
$pagtitulo =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datospagina['pagtitulo'],ENT_QUOTES);
$pagsubtitulo =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datospagina['pagsubtitulo'],ENT_QUOTES);
$pagtitulocorto =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datospagina['pagtitulocorto'],ENT_QUOTES);
$pagcopete = $datospagina['pagcopete'];
$pagcuerpo = $datospagina['pagcuerpo'];
$pagcodsuperior = $datospagina['pagcodsuperior'];
$muestramenu = $datospagina['muestramenu'];
$catnom = $datospagina['catnom'];
$datos['pagestadocod'] = PAGPUBLICADA;
$estado = $datospagina['pagestadodesc'];
$pagtitulosuperior = $datospagina['pagtitulosuperior'];
$url = $datospaginapublicada['pagdominio'];
if ($pagtitulosuperior=="")
	$pagtitulosuperior = "Raiz";


if (!$paginaduplicada)
{
	$datos['rolcod'] = $_SESSION['rolcod'];
	$datos['pagestadocod'] = PAGPUBLICADA;
	$datos['arregloestadofinal'][] = PAGDESPUBLICADA;
	if(!$oPaginasWorkflowRoles->ObtenerAccionesRol($datos,$resultadoacciones,$numfilasacciones))
		return false;	
	
	$accioneliminar = false;
	if($oPaginasWorkflowRoles->TieneAccionEliminar($datos,$nombrebotoneliminar,$paginaworkflowcoddel))
	{	
		FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_eliminar.php",array("pagcod"=>$pagcod,"paginaworkflowcod"=>$paginaworkflowcoddel,"accion"=>1),$geteliminar,$md5eliminar);
		$accioneliminar = true;
	}
	
	$acciondespublicar = false;
	if($oPaginasWorkflowRoles->TieneAccionDespublicar($datos,$nombrebotondespublicar,$paginaworkflowcodpub))
	{	
		FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_despublicar.php",array("pagcod"=>$pagcod,"paginaworkflowcod"=>$paginaworkflowcodpub,"accion"=>1),$getdespublicar,$md5despublicar);
		$acciondespublicar = true;
	}
}
?>
<script type="text/javascript">
	jQuery(document).ready(function(){$('#tabs').tabs();});
</script>
<link href="modulos/pag_paginas/css/paginas.css" rel="stylesheet" title="style" media="all" />
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>P&aacute;gina Publicada</h2>
</div>


<div class="clear fixalto">&nbsp;</div>
<div class="datospagina">

	<div class="msgaccionpagina">&nbsp;</div>
    <div class="menubarra">
        <ul class="accionespagina">
            <?php  
			if (!$paginaduplicada)
			{
                $class = "left";			
                $i = 1;
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
                    if ($i==$numfilasacciones && $i>1)
                        $class="right";
						
					FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_bajar_publicacion.php",array("pagcod"=>$pagcod,"paginaworkflowcod"=>$fila['paginaworkflowcod'],"accion"=>1),$getbajar,$md5bajar);
					?>
                        <li class="states"><a class="boton base" href="pag_paginas_bajar_publicacion.php?<?php  echo $getbajar?>"  class="<?php  echo $class?>" id="<?php  echo $fila['paginaestadocodfinal']?>" rel="<?php  echo $fila['paginaworkflowcod']?>" ><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['paginaaccion'],ENT_QUOTES);?></a></li>
                        <?php 
                    $i++;
                    $class = "middle";
                }
				 if ($acciondespublicar){?>
					<li class="states botonesaccion"><a class="left boton rojo" href="pag_paginas_despublicar.php?<?php  echo $getdespublicar?>" onclick="if (!confirm('Esta seguro que desea despublicar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotondespublicar,ENT_QUOTES);?></a></li>
				 <?php  }?>
                 <?php  if ($accioneliminar){?>
                    <li class="states botonesaccion"><a class="left boton rojo" href="pag_paginas_eliminar.php?<?php  echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
				<?php  }?>
            <?php  }?>
            <li class="states botonesaccion"><a class="left boton base" href="pag_paginas.php" title="Volver">Volver</a></li>
        </ul>
    </div>
    <div class="clear fixalto">&nbsp;</div>


	<div class="form">
    <form action="javascript:void(0)" method="post" name="formulario">
    <div class="ancho_5">
        <div class="datosgenerales">
            <div style="font-size:14px; text-align:right">
                <label>Estado: <span id="estadonombre" style="font-weight:normal"><?php  echo $estado?></span></label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div style="font-size:13px;">
                <label>Url Web:  <input type="text" name="url" id="url" size="50" style="color:#000;" disabled="disabled" value="/<?php  echo $url;?>" /> <a href="<?php  echo DOMINIOWEB?><?php  echo $url;?>" title="Link" target="_blank">Link</a></label> 
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
                <label>Muestra menu lateral:</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
            <?php  if ($muestramenu=="1"){?>
           		<input type="text" name="muestramenu" id="muestramenu" class="full" disabled="disabled" maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree("SI",ENT_QUOTES);?>" />
   		    <?php  }?>
           
            <?php  if ($muestramenu=="0"){?>
           		<input type="text" name="muestramenu" id="muestramenu" class="full" disabled="disabled" maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree("NO",ENT_QUOTES);?>" />
            <?php  }?>
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
    <div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_4">
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


        </div>    
    </form>  
    
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div style="height:50px;">&nbsp;</div>
<?php  
 $oEncabezados->PieMenuEmergente();
?>