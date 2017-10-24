<?php
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion); 

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oNoticiasAcciones = new cNoticiasWorkflowRoles($conexion);
$oNoticias = new cNoticias($conexion);

$estado = "Nueva";
$noticiacod="";
$noticiatitulo="";
$noticiatitulocorto="";
$noticiahrefexterno="";
$noticiacopete="";
$noticiacuerpo="";
$noticiavolanta="";
$noticiaautor="";
$noticiafecha=date("d/m/Y");
$noticiahora=date("H");
$noticiamin=date("i");
$noticiaestadocod="";
$noticiabloqusuario="";
$noticiacopiacodorig="";
$noticiacopiacod="";
$usuariodioalta="";
$noticiafalta="";

$noticiacomentarios="";
$noticiadestacada="";


$catcod="";
$noticiaedit = false;
$classtextarea='rich-text';
$editar="";
$puedeeditar=true;
$tags = "";
$noticiaestadocod = NOTBORRADOR;
$paiscod="";




$noticialatcarga="-34.651285198954135";//lat
$noticialngcarga="-58.77685546875";//long
$noticialat="";//lat
$noticialng="";//long
$noticiazoom=10;
$noticiatype="google.maps.MapTypeId.ROADMAP";
$noticiadireccion = "";
$noticiamuestramapa = 0;

$ubicarmapa = false;	


if (isset($_GET['noticiacod']) && $_GET['noticiacod']!="")
{
	
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("noticiacod"=>$_GET['noticiacod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}

	if(!$oNoticias->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;

	if($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}

	$noticiaedit = true;
	$datosnoticia = $conexion->ObtenerSiguienteRegistro($resultado);
	$noticiacod = $datosnoticia['noticiacod'];
	$catcod = $datosnoticia['catcod'];
	$noticiatitulo = FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES);
	$noticiatitulocorto = FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulocorto'],ENT_QUOTES);
	$noticiahrefexterno = $datosnoticia['noticiahrefexterno'];
	$noticiacopete = str_replace('<div class="space">&nbsp;</div>',"<p></p>",$datosnoticia['noticiacopete']);
	$noticiacuerpo = str_replace('<div class="space">&nbsp;</div>',"<p></p>",$datosnoticia['noticiacuerpo']);
	$noticiavolanta = FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiavolanta'],ENT_QUOTES);
	$noticiaautor = FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiaautor'],ENT_QUOTES);
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
	$noticiacomentarios=$datosnoticia['noticiacomentarios'];
	$noticiadestacada=$datosnoticia['noticiadestacada'];

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
	
	$noticiamuestramapa = $datosnoticia['noticiamuestramapa'];

	
	//BUSCO LOS TAGS DE LA NOTICIA
	$oTags = new cNoticiasTags($conexion);
	if(!$oTags->BuscarTagsxNoticia($datosnoticia,$resultadotags,$numfilastags))
		return false;
	
	$arreglotags = array();
	while ($filatags = $conexion->ObtenerSiguienteRegistro($resultadotags))
		$arreglotags[] = $filatags['noticiatag'];
		
	$tags = implode(", ",$arreglotags);	
	
	if ($tags!="")
		$tags .=", ";


	FuncionesPHPLocal::ArmarLinkMD5("not_noticias_bajar_publicacion.php",array("noticiacod"=>$datosnoticia['noticiacod'],"accion"=>2),$getdescartar,$md5descartar);
	
}


$datos['rolcod'] = $_SESSION['rolcod'];
$datos['noticiaestadocod'] = $noticiaestadocod;
if(!$oNoticiasAcciones->ObtenerAccionesRol($datos,$resultadoacciones,$numfilasacciones))
	return false;
	
$puedeeditar = false;		
if ($numfilasacciones>0)
	$puedeeditar = true;


$accioneliminar = false;
if($oNoticiasAcciones->TieneAccionEliminar($datos,$nombrebotoneliminar,$noticiaworkflowcoddel))
{	
	FuncionesPHPLocal::ArmarLinkMD5("not_noticias_eliminar.php",array("noticiacod"=>$noticiacod,"noticiaworkflowcod"=>$noticiaworkflowcoddel,"accion"=>1),$geteliminar,$md5eliminar);
	$accioneliminar = true;
}

$accionpublicar = false;
if($oNoticiasAcciones->TieneAccionPublicar($datos,$nombrebotonpublicar,$noticiaworkflowcodpub))
{	
	FuncionesPHPLocal::ArmarLinkMD5("not_noticias_publicar.php",array("noticiacod"=>$noticiacod,"noticiaworkflowcod"=>$noticiaworkflowcodpub,"accion"=>1),$getpublicar,$md5publicar);
	$accionpublicar = true;
}

function CargarTemas($titulo,$arreglotemas,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
        <option <? if (array_key_exists($fila['temacod'],$arreglotemas)) echo 'selected="selected"'?>  value="<? echo $fila['temacod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($titulo),ENT_QUOTES).$nivel.FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['tematitulo']),ENT_QUOTES)?></option>
		<? 
		if (isset($fila['subarbol']) && count ($fila['subarbol'])>0)
		{
			$nivel .= " &raquo;&raquo; ";
			CargarTemas($fila['tematitulo'],$arreglotemas,$fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-strlen("&raquo;&raquo;"));
		}
	}
}

function CargarCategorias($catnom,$arreglocategorias,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		 $catnom2 = $fila['catnom'];
		 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
			$catnom2 .="  (".$fila['estadonombre'].")";
		
		?>

        <option <? if (array_key_exists($fila['catcod'],$arreglocategorias)) echo 'selected="selected"'?>  value="<? echo $fila['catcod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom),ENT_QUOTES).$nivel.FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>
		<? 
		if (isset($fila['subarbol']) && count ($fila['subarbol'])>0)
		{
			$catnom = $catnom.html_entity_decode(" &raquo;&raquo; ").$catnom;
			CargarCategorias($catnom,$arreglocategorias,$fila['subarbol'],$nivel);
			//$nivel = substr($nivel,0,strlen($nivel)-strlen("&raquo;&raquo;"));
		}
	}
}

?>
<link href="modulos/not_noticias/css/noticias.css" rel="stylesheet" title="style" media="all" />
<link href="js/maps/estilos.css" rel="stylesheet" title="style" media="all" />
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript">var sizeLimitFile = <? echo TAMANIOARCHIVOS;?>;</script>
<script type="text/javascript">var sizeLimitFileAudio = <? echo TAMANIOARCHIVOSAUDIO;?>;</script>
<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/not_noticias/js/noticias_am.js"></script>
<script type="text/javascript" src="modulos/not_noticias/js/noticias_relacionadas.js"></script>
<script type="text/javascript" src="modulos/not_noticias/js/noticias_relacionadas_buscar_popup.js"></script>
<script type="text/javascript" src="modulos/not_noticias/js/noticias_galerias_relacionadas_buscar_popup.js"></script>
<script type="text/javascript" src="modulos/not_noticias/js/noticias_galerias_relacionadas.js"></script>
<script type="text/javascript" src="modulos/not_noticias/js/noticias_tags.js"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<? echo GOOGLEAPIKEY?>&callback=initMap"
  type="text/javascript"></script>
<script type="text/javascript" src="js/maps/googlemaps.js"></script>


<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Noticia</h2>
</div>
<div class="clear fixalto">&nbsp;</div>



<div class="form">
<div id="DetalleNoticiaAm">
    <div class="datosnoticia">
        <div class="msgaccionnoticia">&nbsp;</div>
        <div class="menubarra">
            <ul >
                <? 
                    $i = 1;
                    while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
                        if ($i==$numfilasacciones && $i>1)
                            ?>
                            <li ><a class="boton base " id="<? echo $fila['noticiaestadocodfinal']?>" rel="<? echo $fila['noticiaworkflowcod']?>" href="javascript:void(0)" onclick="Guardar(this)"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiaaccion'],ENT_QUOTES);?></a></li>
                            <?
                        $i++;
                    }
                ?>
				<? if ($accionpublicar && $noticiaedit){?>
                    <li><a class="boton verde" href="not_noticias_publicar.php?<? echo $getpublicar?>" onclick="if (!confirm('Esta seguro que desea publicar la noticia?')) return false;"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotonpublicar,ENT_QUOTES);?></a></li>
                <? }?>
                <? if ($accioneliminar && $noticiaedit){?>
                    <li><a class="boton rojo" href="not_noticias_eliminar.php?<? echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la publicacion?')) return false;"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
                <? }?>
                <? if ($noticiaedit){?>
                    <li><a class="boton base" href="not_noticias_salto.php?noticiacod=<? echo $noticiacod?>" target="_blank">Previsualizar</a></li>
                <? }?>
                <li><a class="boton base" href="not_noticias.php">Volver</a></li>
            </ul>
        </div>
        <div class="clear fixalto">&nbsp;</div>
        <form action="not_noticias.php" name="formnoticia" id="formnoticia" method="post">
            <input type="hidden" name="noticiacod" id="noticiacod" value="<? echo $noticiacod?>" />
            <input type="hidden" name="noticiaedit" id="noticiaedit" value="<? echo $noticiaedit?>" />
            <input type="hidden" name="noticiaestadocod" id="noticiaestadocod" value="<? echo $noticiaestadocod?>" />
            <input type="hidden" name="puedemodificar" id="puedemodificar" value="<? echo (int)$puedeeditar?>" />
                
                <div>
                    <label>Secci&oacute;n Principal</label>
                </div>
                <div class="clearboth fixalto">&nbsp;</div>
                <div class="aire ancho_5">
                    <?php
                        $oCategorias=new cCategorias($conexion);
                        $catsuperior="";
						$estadocombocat = "";
						if($noticiaedit == false)
							$estadocombocat = ACTIVO;
                        if (!$oCategorias->ArmarArbolCategorias($catsuperior,$arbol,$estadocombocat))
                            $mostrar=false;
						$arreglocategoriasSeleccionado = array();
						if ($catcod!="")
							$arreglocategoriasSeleccionado[$catcod] = $catcod;
					?>
                    
                    <select name="catcod" id="catcod" style=" width:100%;" class="chzn-select-categorias">
                        <option value="">Seleccione una secci&oacute;n...</option>
                    <?php 
                        foreach($arbol as $fila)
                        {

							 $catnom2 =  $catnom =$fila['catnom'];
							 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
								$catnom2 .="  (".$fila['estadonombre'].")";	
							?>

                            <option <?php if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php echo $fila['catcod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>

                            <?php 
                            if (isset($fila['subarbol']))
                            {
                                $nivel = " &raquo;&raquo; ";
                                CargarCategorias($catnom,$arreglocategoriasSeleccionado,$fila['subarbol'],$nivel);
                            }
                        }
                        ?>
                     </select>
				</div>
                <div class="clearboth aire_menor">&nbsp;</div>
                
                <div>
                    <label>Volanta</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="noticiavolanta" <? echo $editar ?> id="noticiavolanta" class="full" value="<? echo $noticiavolanta;?>" size="90" maxlength="135">
                    <div id="noticiavolantaCharCount" class="charCount">
                        Cantidad de caracteres:
                        <span class="counter">0</span>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Titulo</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="noticiatitulo" <? echo $editar ?> id="noticiatitulo" class="full" value="<? echo $noticiatitulo;?>" size="90" maxlength="200">
                    <div id="noticiatituloCharCount" class="charCount">
                        Cantidad de caracteres:
                        <span class="counter">0</span>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Titulo Corto</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="noticiatitulocorto" id="noticiatitulocorto" <? echo $editar ?> class="full" value="<? echo $noticiatitulocorto;?>" size="90" maxlength="80">
                    <div id="noticiatitulocortoCharCount" class="charCount">
                        Cantidad de caracteres:
                        <span class="counter">0</span>
                    </div>
                </div>
                <div class="clearboth aire_vertical">&nbsp;</div>
                <div>
                    <div>
                        <label>Temas asociados</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                    	<? 
							$oTemas = new cTemas($conexion);
							$oNoticiasTemas = new cNoticiasNoticiasTemas($conexion);
							$temacod = "";
							$oTemas->ArmarArbolTemas($temacod,$arbol);
							$arreglonoticias = array();
							if ($noticiaedit)
							{
								if(!$oNoticiasTemas->BuscarxNoticia($datosnoticia,$resultadoTemaNoticia,$numfilasTemaNoticia))
									return false;
								while($filaNoticias = $conexion->ObtenerSiguienteRegistro($resultadoTemaNoticia))
									$arreglonoticias[$filaNoticias['temacod']] = $filaNoticias['temacod'];
							}
						?>
                       <select data-placeholder="Seleccione los temas relacionados" tabindex="6" name="temacod[]" id="temacod[]" class="chzn-select full" multiple="multiple" <? if (!$puedeeditar) echo 'disabled="disabled"'?>  >
                        <?
                            foreach($arbol as $fila)
                            {
                                ?>
                                
                                <option <? if (array_key_exists($fila['temacod'],$arreglonoticias)) echo 'selected="selected"'?>  value="<? echo $fila['temacod']?>"><strong><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['tematitulo']),ENT_QUOTES)?></strong></option>
								<? 
                                if (isset($fila['subarbol']))
                                {
                                    $nivel = " -- ";
                                    CargarTemas($fila['tematitulo'],$arreglonoticias,$fila['subarbol'],$nivel);
                                }
								?>
                                
                                <? 
                            }
                            ?>
                         </select>
                         <div style="font-size:10px;">Seleccione los temas haciendo un click en el tema que desee.
                              <a href="javascript:void(0)" onclick="CrearTemaNuevo()"> Crear Nuevo</a>
                         </div>
                    </div>
				</div> 
                <div class="clearboth aire_menor">&nbsp;</div>               
                 	<div id="NuevoTema">
                         <div>
                            <label>Tema</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" name="tematitulo" id="tematitulo"  class="full" value="" size="90" maxlength="50">
                            <input type="hidden" name="temacodsuperior" id="temacodsuperior"  value="" >
                            <input type="hidden" name="temadesc" id="temadesc"  value="" >
                            <input type="hidden" name="temacolor" id="temacolor"  value="" >
                            <div id="tematituloCharCount" class="charCount">
                                Cantidad de caracteres:
                                <span class="counter">0</span>
                            </div>
                            <div class="menubarra">
                                <ul>
                                    <li class="botonesaccion"><a class="left" id="temanuevo" href="javascript:void(0)" onclick="GuardarTema()">Guardar</a></li>
                                </ul>
                            </div>    
                        </div>
                    </div>
	                <div class="clearboth aire_menor">&nbsp;</div>
                
                <div>
                    <label>Secciones Relacionadas</label>
                </div>
                <div class="clearboth fixalto">&nbsp;</div>
                <div>
					<?php  
                        $oCategorias = new cCategorias($conexion);
                        $oNoticiasCategorias = new cNoticiasCategorias($conexion);
                        $catcod = "";
                        $estadocombocat = "";
						if($noticiaestadocod == NOTBORRADOR)
							$estadocombocat = ACTIVO;
                        $oCategorias->ArmarArbolCategorias($catcod,$arbol,$estadocombocat);
                        $arreglocategorias = array();
                        if ($noticiaedit)
                        {
                            if(!$oNoticiasCategorias->BuscarCategoriasxNoticia($datosnoticia,$resultadoTemaCategoria,$numfilasTemaCategoria))
                                return false;
                            while($filaCategoria = $conexion->ObtenerSiguienteRegistro($resultadoTemaCategoria))
                                $arreglocategorias[$filaCategoria['catcod']] = $filaCategoria['catcod'];
                        }
                    ?>
                   <select data-placeholder="Seleccione las secciones relacionadas" tabindex="7" name="catcodrel[]" id="catcodrel[]" class="chzn-select-cateroria-rel full" multiple="multiple" <?php if (!$puedeeditar) echo 'disabled="disabled"'?>  >
                    <?php
                        foreach($arbol as $fila)
                        {

                           	 $catnom2 =  $catnom =$fila['catnom'];
							 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
								$catnom2 .="  (".$fila['estadonombre'].")";	
						   
                            ?>
                            <option <?php if (array_key_exists($fila['catcod'],$arreglocategorias)) echo 'selected="selected"'?>  value="<?php echo $fila['catcod']?>"><strong><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></strong></option>

                            <?php 
                            if (isset($fila['subarbol']))
                            {
                                $nivel = " &raquo;&raquo; ";
                                CargarCategorias($catnom,$arreglocategorias,$fila['subarbol'],$nivel);
                            }
                            ?>
                            <?php 
                        }
                        ?>
                     </select>
                     <div style="font-size:10px;">Seleccione las secciones haciendo un click en la secci&oacute;n que desee</div>
                </div>
                <div class="clearboth aire_vertical">&nbsp;</div>
                <div class="ancho_5">
                    <div>
                        <label>Tiene Comentarios</label>
                    </div>
                    <div class="clearboth fixalto">&nbsp;</div>
                    <div class="ancho_2">
                        <select name="noticiacomentarios" id="noticiacomentarios" >
                            <option value="1" <? if ($noticiacomentarios==1) echo 'selected="selected"'?> >Si</option>
                            <option value="0" <? if ($noticiacomentarios==0) echo 'selected="selected"'?> >No</option>
                        </select>
                    </div>
                </div>
                <div class="ancho_5">
                    <div>
                        <label>Es Destacada</label>
                    </div>
                    <div class="clearboth fixalto">&nbsp;</div>
                    <div class="ancho_2">
                        <select name="noticiadestacada" id="noticiadestacada">
                            <option value="1" <? if ($noticiadestacada==1) echo 'selected="selected"'?> >Si</option>
                            <option value="0" <? if ($noticiadestacada==0) echo 'selected="selected"'?> >No</option>
                        </select>
                    </div>
                </div>
                <div class="clearboth aire_vertical">&nbsp;</div>
                <div>
                    <label>Bajada</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                   <? if (!$puedeeditar){ ?> 
                    <div style="height:90px; overflow-y:auto;">
                       <? echo $noticiacopete?>
                    </div>
                   <? }else{ ?>
                    <textarea name="noticiacopete" id="noticiacopete"  class="textarea full rich-text" rows="5" cols="40" wrap="hard"><? echo $noticiacopete;?></textarea>
                    <div class="wordCountclass">
                        <div id="noticiacopeteWordCount" class="wordCount"></div>
                    </div>
                   <? } ?> 
                </div>
                
    
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Texto</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                   <? if (!$puedeeditar){ ?> 
                    <div style="height:90px; overflow-y:auto;">
                       <? echo $noticiacuerpo?>
                    </div>
                    <? }else{?> 
                    <div class="alineacion_arroba" >
                    	<div style="padding:5px">
                         	<strong>Frase Wide:</strong> (FW)texto (A)autor(A)(FW) <br /><br />
                            <strong>Foto Wide:</strong> @fotoC@ <br /><br />
                            <strong>Foto Izq:</strong> @fotoI@ <br /><br />
                            <strong>Foto Der:</strong> @fotoD@ <br /><br />
                        </div> 	
                    </div>
                    <div class="alineacion_cuerpo">
	                    <textarea name="noticiacuerpo" id="noticiacuerpo" class="textarea full rich-text-avanzado" rows="35" cols="40"  wrap="hard"><? echo $noticiacuerpo;?></textarea>
                        <div class="wordCountclass">
                            <div id="noticiacuerpoWordCount" class="wordCount"></div>
                        </div>
                    </div>
                                        <? } ?>  
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Link Externo</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text"  name="noticiahrefexterno" <? echo $editar ?> id="noticiahrefexterno" class="textarea full" value="<? echo $noticiahrefexterno;?>"  maxlength="255" onkeypress="SetearGuardadoAutomatico()">
                </div>
                <div style="font-size:11px; margin-top:2px;">
                    Ej: http://www.bigtree.com.ar
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Tags - (Palabras Claves)</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="ui-widget">
                    <input type="text" name="noticiatags" <? echo $editar ?> id="noticiatags" class="textarea full inputgral" value="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($tags,ENT_QUOTES)?>" />
                    <div class="wordCountclass">
                        <div id="noticiatagsWordCount" class="wordCount"></div>
                    </div>
                </div>
                <div style="font-size:11px; margin-top:2px;">
                    Ej: noticia, diario, a&ntilde;o nuevo 
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
            <div class="clear aire_vertical">&nbsp;</div>
            <div class="menubarraInferior">
                <div class="menubarra">
                    <ul class="accionesnoticia">
                        <? 
                            if ($puedeeditar)
                                $conexion->MoverPunteroaPosicion($resultadoacciones,0);
                            $i = 1;
                            while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
                                if ($i==$numfilasacciones && $i>1)
                                    ?>
                                    <li><a  class="boton base" id="<? echo $fila['noticiaestadocodfinal']?>" rel="<? echo $fila['noticiaworkflowcod']?>" href="javascript:void(0)" onclick="Guardar(this)"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiaaccion'],ENT_QUOTES);?></a></li>
                                    <?
                                $i++;
                            }
                        ?>
                        <? if ($accionpublicar && $noticiaedit){?>
                            <li><a class="boton verde" href="not_noticias_publicar.php?<? echo $getpublicar?>" onclick="if (!confirm('Esta seguro que desea publicar la noticia?')) return false;"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotonpublicar,ENT_QUOTES);?></a></li>
                        <? }?>
                        <? if ($accioneliminar && $noticiaedit){?>
                            <li><a class="boton rojo" href="not_noticias_eliminar.php?<? echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la publicacion?')) return false;"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
                        <? }?>
                        <? if ($noticiaedit){?>
                            <li><a class="boton base" href="not_noticias_salto.php?noticiacod=<? echo $noticiacod?>" target="_blank">Previsualizar</a></li>
                        <? }?>
                        <li><a class="boton base" href="not_noticias.php">Volver</a></li>
                    </ul>
                </div>
                <div class="msgaccionupd msgaccionnoticia">&nbsp;</div>
            </div>    
            <div class="clear fixalto">&nbsp;</div>
                <div id="datos_adicionales">
                    <h3>Datos adicionales</h3>
                    <ul>
                        <li><a href="#noticias_relacionadas">Noticias Relacionadas</a></li>
                        
                        <? /*<li><a href="#galerias_relacionadas">Galerias Relacionadas</a></li>*/?>
                    </ul>
                    <div id="noticias_relacionadas" style="border-bottom:2px solid #CCC; width:100%">
                       <? if ($puedeeditar){ ?>
                            <div>
                                No encontr&aacute;s la noticia, ingres&aacute; a nuestra b&uacute;squeda avanzada haciendo click <a href="javascript:void(0)" onclick="return AbrirPopupNoticiaRelacionas()" title="B&uacute;squeda Avanzada"><b>aqu&iacute;</b></a>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                           
                            <div class="float-left" style="width:60%">
                                <input type="text" class="full" name="noticiafinder" id="noticiafinder" value="" />
                                <input type="hidden" name="noticiacodrel" id="noticiacodrel" value="" />
                            </div>
                           
                            <div class="menubarra float-left" style="width:40%">
                                <ul>
                                    <li><a class="left" href="javascript:void(0)" onclick="return AgregarNoticiaRelacionadaAutocomplete()">Agregar noticia relacionada</a></li>
                                </ul>
                            </div>
                        <?	} ?>
                        <div class="clearboth fixalto">&nbsp;</div>
                        <div id="msgnoticiarelacionada"></div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div id="LstNoticiasRel" style="width:100%;" class="clearfix">
                            <table id="ListadoNoticiasRelacionadas" style="width:100%"></table>
                        </div>                 
                        <div class="clearboth fixalto">&nbsp;</div>
                        <div style="font-size:11px;">Si desea modificar el orden solo mantenga el mouse presionado sobre la noticia que 
                        desee cambiar y mueval&aacute; hacia el lugar deseado.</div>
                        <div class="clearboth fixalto">&nbsp;</div>
                        
                    </div>
                    <? /*
                    <div id="galerias_relacionadas" style="border-bottom:2px solid #CCC">
                       <? if ($puedeeditar){ ?>
                            <div>
                                No encontr&aacute;s la galeria, ingres&aacute; a nuestra b&uacute;squeda avanzada haciendo click <a href="javascript:void(0)" onclick="return AbrirPopupGaleriaRelacionada()" title="B&uacute;squeda Avanzada"><b>aqu&iacute;</b></a>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                           
                            <div class="float-left" style="width:60%">
                                <input type="text" class="full" name="galeriafinder" id="galeriafinder" value="" />
                                <input type="hidden" name="galeriacodrel" id="galeriacodrel" value="" />
                            </div>
                           
                            <div class="menubarra float-left" style="width:40%">
                                <ul>
                                    <li><a class="left" href="javascript:void(0)" onclick="return AgregarGaleriaRelacionadaAutocomplete()">Agregar galeria relacionada</a></li>
                                </ul>
                            </div>
                        <?	} ?>   
                         <div class="clearboth fixalto">&nbsp;</div>
                        <div id="msggaleriarelacionada"></div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div id="LstGaleriasRel" style="width:100%;" class="clearfix">
                            <table id="ListadoGaleriasRelacionadas" style="width:100%"></table>
                        </div>          
                    </div>
					*/ ?>
                    <div class="clearboth fixalto">&nbsp;</div>
    
                        
                
                </div>
        </form>
    </div>
    <div class="datosextranoticia">
		<?	$oMultimediaFormulario = new cMultimediaFormulario($conexion,"NOT",$noticiacod);
         if ($puedeeditar){ 
			echo $oMultimediaFormulario->CargarBotonera();
          } ?> 

        
            <? echo $oMultimediaFormulario->CargarListado();?>
           <div class="datosextra">
           <form action="not_noticias.php" name="formnoticiaextra" id="formnoticiaextra" method="post">
                <div class="labeldatoscomplementarios">
                    <label>Estado:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div id="estadonoticia">
                    <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($estado,ENT_QUOTES);?>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="labeldatoscomplementarios">
                    <label>Fecha:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="noticiafecha" id="noticiafecha" class="medium" <? echo $editar ?> value="<? echo $noticiafecha?>" size="10" maxlength="10">
                    <span class='textonombredatos' style="font-size:8px;">&nbsp;(DD/MM/AAAA) </span>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="labeldatoscomplementarios">
                    <label>Hora:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <div class="float-left">
                        <select name="noticiahora" <? echo $editar ?>>
                            <? for ($i=0; $i<24; $i++){ 
                            $hora = str_pad($i,2,0,STR_PAD_LEFT);
                            ?>
                                <option <? echo $editar ?><? if ($noticiahora==$hora) echo 'selected="selected"'?>  name="<? echo $hora?>"><? echo $hora?></option>
                            <? }?>
                        </select>
                    </div>
                    <div class="brisa_horizontal float-left">&nbsp;</div>
                    <div class="float-left">
                        <select name="noticiaminutos" <? echo $editar ?>>
                            <? for ($i=0; $i<60; $i++){ 
                            $min = str_pad($i,2,0,STR_PAD_LEFT);
                            ?>
                                <option <? if ($noticiamin==$min) echo 'selected="selected"'?> name="<? echo $min?>"><? echo $min?></option>
                            <? }?>
                        </select>
                        <span class='textonombredatos' style="font-size:8px;">&nbsp;(mm-ss) </span>
                    </div>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="labeldatoscomplementarios">
                    <label>Autor</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="noticiaautor" class="full" <? echo $editar ?> value="<? echo $noticiaautor;?>" size="90" maxlength="255">
                </div>
                <div class="clearboth aire_vertical">&nbsp;</div>
                <div>
                    <label>Muestra el Mapa</label>
                </div>
                <div class="clearboth fixalto">&nbsp;</div>
                <div class="ancho_2">
                    <select name="noticiamuestramapa" id="noticiamuestramapa">
                    	<option value="1" <? if ($noticiamuestramapa==1) echo 'selected="selected"'?> >Si</option>
                    	<option value="0" <? if ($noticiamuestramapa==0) echo 'selected="selected"'?> >No</option>
                    </select>
				</div>
                
                <div class="clearboth aire_vertical">&nbsp;</div>
			</form>
            <div class="tabsMapa">
                <form action="not_noticias.php" name="formmapa" id="formmapa" method="post">
                    <h3>Ubicaci&oacute;n de la noticia</h3>
                    <div class="tabsMapaFondo">
                    <div id="divGoogleMaps"  style="display:block;position:relative;width:90% !important; margin-left:5%;height:400px !important; margin:auto;"></div>
                    <div class="ancho_4">
                        <div class="labeldatoscomplementarios">
                            <label>Latitud</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" name="noticialat" id="noticialat" class="full" <? echo $editar ?> value="<? echo $noticialat;?>" size="90" maxlength="255">
                        </div>
                    </div>
                    <div class="ancho_1">&nbsp;</div>
                    <div class="ancho_4">
                        <div class="labeldatoscomplementarios">
                            <label>Longitud</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" name="noticialng" id="noticialng" class="full" <? echo $editar ?> value="<? echo $noticialng;?>" size="90" maxlength="255">
                        </div>
                    </div>
                    <div class="clearboth aire_vertical">&nbsp;</div>
                    <div class="ancho_10">
                        <div class="labeldatoscomplementarios">
                            <label>Direcci&oacute;n</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" name="noticiadireccion" id="noticiadireccion" class="full" <? echo $editar ?> value="<? echo $noticiadireccion;?>" size="90" maxlength="255">
                        </div>
                    </div>
                    <div class="clearboth aire_vertical">&nbsp;</div>
                    <div id="suggest_list"></div>
                    </div>

                    <input type="hidden" name="noticiatype" id="noticiatype" value="<? echo $noticiatype ;?>" />  
                    <input type="hidden" name="noticiazoom" id="noticiazoom" value="<? echo $noticiazoom ;?>" />  
				</form>
				<script language="javascript">
				/*
                var ObjMapa;
                $(document).ready(function() {
                    ObjMapa = $("#divGoogleMaps").mapaBigTree({
                            <? if ($puedeeditar){?>
								'IdBuscador': {'idBuscador':'noticiadireccion', 'idBuscadorListado':'suggest_list'},
							<? }?>
                            'beforeSelected':  CargarIds,	
                            'onChangeZoom':  ModificarZoom,	
                            'onChangeMapType':  CargarTipoMapa,	
                            'zoom':	<? echo $noticiazoom?>,
                            'lat':	<? echo $noticialatcarga?>,
                            'long':	<? echo $noticialngcarga?>,
                            'tipo': <? echo $noticiatype?>,
                            'MultipleMarkers':  false					
                        }
                    );
                    ObjMapa.Inicializate();
                    ObjMapa.AcceptAddMarkerButton("rightclick");
                    <? 
                    if ($noticiaedit && $ubicarmapa)
                    	{?>ObjMapa.AddMarker(<? echo $noticialat?>,<? echo $noticialng?>);<? }
                    ?>
                });
				*/
    
                function CargarIds(e,location)
                {
                    $("#noticialat").val(location.lat());
                    $("#noticialng").val(location.lng());
                }
                
                function ModificarZoom(zoom)
                {
                    $("#noticiazoom").val(zoom);
                }
                function CargarTipoMapa(type)
                {
                    $("#noticiatype").val(type);
                }
                </script>
            </div>
            
            
        </div>
    </div>
    </div>

    <div id="PopupGaleriasRelacionadas"></div>
    <div id="PopupNoticiasRelacionadas"></div>
    <div class="clear aire_vertical">&nbsp;</div>
    <div style="height:50px;">&nbsp;</div>
</div>    
<? 
 $oEncabezados->PieMenuEmergente();
?>