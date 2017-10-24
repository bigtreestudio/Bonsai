<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oGalerias = new cGalerias($conexion,"");
$oMultimediaConjuntos= new cMultimediaConjuntos($conexion);
$oMultimedia = new cMultimedia($conexion,"noticias/");

$esmodif = false;
if (isset($_GET['galeriacod']) && $_GET['galeriacod']!="")
{
	$esmodif = true;
	if (!$oGalerias->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Galeria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosgalerias = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$galeriacod="";
$galeriatitulo = "";
$galeriadesc ="";
$multimediaconjuntocod='';
$catdominio = "";
$semuestramenu="1";
$multimediacod ="";
$onclick = "return Insertar();";

$oMenu= new cTapasMenu($conexion);
$oMenuTipo = new cTapasMenuTipos($conexion);
$menutipocod="";
$menucod = "";	
$catcod ="";
$multimediaconjuntodesc ="";
$datostipo = array();
$datostipo["menutipocod"] ="";

if(!$oMenuTipo->Buscar($datostipo,$resultadoTipos,$numfilasTipos))
	return false;
if ($esmodif)
{
	
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$galeriacod = $datosgalerias['galeriacod'];
	$galeriatitulo = $datosgalerias['galeriatitulo'];
	$galeriadesc = $datosgalerias['galeriadesc'];
	$albumcod = $datosgalerias['albumcod'];
	$galeriaestadocod = $datosgalerias['galeriaestadocod'];
	$galeriaorden = $datosgalerias['galeriaorden'];
	$multimediaconjuntocod=$datosgalerias['multimediaconjuntocod'];
	$catcod= $datosgalerias['catcod'];
	$multimediaconjuntodesc = $datosgalerias['multimediaconjuntodesc'];
	$multimediacod=$datosgalerias['multimediacod'];
	$onclick = "return Modificar();";
		
	$dominiogaleria = $datosgalerias['galeriadominio'];
	if($datosgalerias["menutipocod"]==0){
		$datosgalerias["menucod"]="";
		$datosgalerias["menutipocod"]="";
		
		if(!$oMenuTipo->Buscar($datosgalerias,$resultado,$numfilasTipos))
			return false;
	}else{
		$menucod = $datosgalerias["menucod"];
		$menutipocod = $datosgalerias['menutipocod'];
		
		if(!$oMenuTipo->BuscarxCodigo($datosgalerias,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, tipo de menu inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	}	
		
		
	$datostipo = $conexion->ObtenerSiguienteRegistro($resultado);
	if(!$oMenu->BuscarxTipo($datostipo,$resultado,$numfilas))
		return false;}


$nivel = 1;
function CargarSubMenu($arbol,$nivel,$menucod)
{
	$margen = $nivel *10; 
	foreach($arbol as $fila)
	{
		?>
        <option <?php  if ($fila['menucod']==$menucod) echo 'selected="selected"'?>  value="<?php  echo $fila['menucod']?>"><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['menudesc']),ENT_QUOTES)?></option>
		<?php 
        			if (isset($fila['subarbol']) && count($fila['subarbol'])>0)
                    {
                        $nivel ++;
                        CargarSubMenu($fila['subarbol'],$nivel);
                        $nivel --;
                    }?>
          
		<?php 	
	}
}

$oMenu-> ArmarArbol($datostipo,"",$arbolmenu);

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
<link rel="stylesheet" type="text/css" href="modulos/mul_multimedia/css/estilos.css" />
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="modulos/gal_galerias/js/gal_galerias_am.js?v=1.1"></script>
<script type="text/javascript" src="js/multimediaSelectorFotos.js"></script>	
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Galeria</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">

        <div class="form">
            <form action="gal_galerias.php" method="post" name="formalta" id="formalta" >
                <div class="ancho_5">
                    <div>
                        <label>Titulo</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="galeriatitulo"  id="galeriatitulo" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($galeriatitulo,ENT_QUOTES)?>" size="90" maxlength="255">
                    </div>
                    <?php  if ($esmodif){?>
                        <div class="clearboth aire_menor">&nbsp;</div>
                        <div>
                            <label>Url de la Galeria</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" style="background-color:#E9E9E9" readonly="readonly" name="galeriadominio"  id="galeriadominio" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($dominiogaleria,ENT_QUOTES)?>" size="90">
                        </div>
                    <?php  }?>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Tipo de Galeria</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                    	<?php if($esmodif) {?>
                        		<input type="text" style="background-color:#E9E9E9" name="multimediaconjuntodesc"  id="multimediaconjuntodesc" readonly="readonly" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($multimediaconjuntodesc,ENT_QUOTES)?>" >
                                <input  type="hidden" name="multimediaconjuntocod"  id="multimediaconjuntocod"  value="<?php  echo  $multimediaconjuntocod?>" >
                        <?php }else{?>
                   		<select name="multimediaconjuntocod" id="multimediaconjuntocod">
                        	<option <?php  if ($multimediaconjuntocod==FOTOS) echo 'selected="selected"'?> value="<?php  echo FOTOS?>">Fotos</option>
                        	<option <?php  if ($multimediaconjuntocod==VIDEOS) echo 'selected="selected"'?> value="<?php  echo VIDEOS?>">Videos</option>
                        </select>
                        <?php }?>
                    </div>                    
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                    	<label>Categor&iacute;a</label>
                	</div>
                	<div class="clearboth brisa_vertical">&nbsp;</div>
                	<div>
						<?php
                            $oCategorias=new cCategorias($conexion);
                            $catsuperior="";
                            $estadocombocat = "";
                            if($esmodif == false)
                                $estadocombocat = ACTIVO;
                            if (!$oCategorias->ArmarArbolCategorias($catsuperior,$arbol,$estadocombocat))
                                $mostrar=false;
                            $arreglocategoriasSeleccionado = array();
                            if ($catcod!="")
                                $arreglocategoriasSeleccionado[$catcod] = $catcod;
                        ?>
                        
                        <select name="catcod" id="catcod">
                            <option value="">Seleccione una categor&iacute;a...</option>
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
                   <div class="clearboth brisa_vertical">&nbsp;</div>
                   <div class="clear" style="height:10px;">&nbsp;</div>
                    <div>
                        <label>Tipo de Men&uacute;:</label>
                    </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>
                    <div>
                        <select data-placeholder="Todos el tipo de menu..." name="menutipocod" id="menutipocod" style="width:400px;" class="chzn-select"  onchange="return CargarMenu()">
                             <option value="">Seleccione un tipo...</option>
                            <?php 
                               while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoTipos))
                               {
                                   ?>
                                    <option <?php  if ($fila['menutipocod']==$menutipocod) echo 'selected="selected"'?>  value="<?php  echo $fila['menutipocod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['menutipodesc']),ENT_QUOTES)?></option>
                                    <?php  
                                }
                                ?>
                         </select>
                    </div>                    

                   <div class="clear" style="height:10px;">&nbsp;</div>
                    <div>
                        <label>Menu:</label>
                    </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>
                    <div id="Menus">
                        <?php  if ($esmodif){?>
                        <select data-placeholder="Todos los menu..." name="menucod" id="menucod" style="width:400px;" class="chzn-select" >
                             <option value="">Sin men&uacute;...</option>
                            <?php 
                                if(count($arbolmenu)>0)
								{
									foreach($arbolmenu as $fila)
									{
									   
									   ?>
										<option <?php  if ($fila['menucod']==$menucod) echo 'selected="selected"'?>  value="<?php  echo $fila['menucod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['menudesc']),ENT_QUOTES)?></option>
										<?php  
										if (isset($fila['subarbol']))
										{
											$nivel = "---";
											CargarSubMenu($fila['subarbol'],$nivel,$menucod);
										}
									}
								}
                                ?>
                         </select>
                         <?php  }else{?>
                            <select data-placeholder="Todos los menu..." name="menucod" id="menucod" style="width:400px;" class="chzn-select"  >
                                 <option value="" selected="selected">Sin men&uacute;...</option>
                             </select>
                         <?php  }?>
                    </div>  

                     <div class="clearboth brisa_vertical">&nbsp;</div>                  
                    <div>
                        <label>Descripci&oacute;n</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <textarea name="galeriadesc" class="textarea full" id="galeriadesc" rows="5" cols="40"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($galeriadesc,ENT_QUOTES)?></textarea>
                    </div>
                   <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="<?php   echo $onclick ?>">Guardar</a></div></li>
                            <?php if($esmodif){?>
                            <li><div class="ancho_boton aire"><a class="boton azul" href="gal_galerias_multimedia_am.php?galeriacod=<?php echo $galeriacod?>">Editar Galeria</a></div></li>
                            <? }?>
                            <li><div class="ancho_boton aire"><a class="boton base" href="gal_galerias.php">Volver</a></div></li>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                <div class="msgaccionupd">&nbsp;</div>
            </div>
            		<div class="clearboth">&nbsp;</div>
                </div>
                
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_4">
                
        
                                <div style="margin-bottom:10px;"><label for="multimediacod">Imagen</label></div>
                                <div class="menubarra">
                                    <ul>
                                        <li><div class="ancho_boton aire"><a class="boton azul" href="javascript:void(0)" onclick="return SeleccionarMultimediaRepositorioFotos('multimediacod')">Seleccione una Im&aacute;gen</a></div></li>
                                    </ul>
                                    <div class="clearboth">&nbsp;</div>
                                </div>
                                  <div class="ancho_8"><input type="hidden" name="multimediacod" id="multimediacod" value="<?php   echo $multimediacod?>" /></div>
                                  <div class="clearboth brisa_vertical">&nbsp;</div>
                                  <div id="multimediapreview_multimediacod">
                                    <?php   $titulomultimedia_multimediacod = "" ?>                            
                                    <?php   if ($multimediacod!=""){                            
                                            $datosBusqueda["multimediacod"] = $multimediacod;
                                        if(!$oMultimedia->BuscarMultimediaxCodigo($datosBusqueda,$resultado,$numfilas))
                                                return false;
                                            $datosMultimedia = $conexion->ObtenerSiguienteRegistro($resultado);
                                            $html = $oMultimedia->VisualizarArchivoSimpleMultimedia($datosMultimedia);
                                            echo $html;
                                            $titulomultimedia_multimediacod = $datosMultimedia["multimedianombre"];
                                            if($datosMultimedia["multimediatitulo"]!="")
                                            $titulomultimedia_multimediacod = $datosMultimedia["multimediatitulo"];
                                            ?>
                                            <?php   }?><?php   $oculto_multimediacod='style="display:none"';
                                            if ($esmodif && $multimediacod!=""){$oculto_multimediacod='';} ?>
                                            <a id="multimediaeliminar_multimediacod" <?php   echo $oculto_multimediacod; ?>  href="javascript:void(0)" onclick="return EliminarMultimediaRepositorioFotos('multimediacod','galeriacod')"><img src="images/cross.gif"  alt="Eliminar"></a>
                                            <div><?php   echo utf8_encode($titulomultimedia_multimediacod); ?></div>
                                            <div class="clearboth aire_vertical">&nbsp;</div>
                                    </div>
                                <div class="txt">Recuerde <strong>guardar</strong> para que se realicen los cambios</div>
                    </div>
                <div class="clearboth">&nbsp;</div>
                <input type="hidden" name="galeriacod" id="galeriacod" value="<?php  echo $galeriacod?>" />
            </form>
        </div>
 	</div>
<div class="clearboth">&nbsp;</div>
<?php 
$oEncabezados->PieMenuEmergente();

?>