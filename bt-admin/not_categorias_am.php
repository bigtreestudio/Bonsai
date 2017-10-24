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

$oPlantillas = new cPlantillas($conexion,"");
$oMenu= new cTapasMenu($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);


$esmodif = false;
$oCategorias = new cCategorias($conexion,"");


if(isset($_GET['catsuperior']) && $_GET['catsuperior']!="")
	$catsuperior= $_GET['catsuperior'];
else
	$catsuperior ="";
//print_r($_POST);
if (isset($_GET['catcod']) && $_GET['catcod'])
{
	$esmodif = true;
	if (!$oCategorias->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datoscategorias = $conexion->ObtenerSiguienteRegistro($resultado);
}
$catcolor= "#000000";
$botonejecuta = "BtAlta";
$boton = "Alta";
//$titulo = "Alta de Ciudad - ";
$catcod="";
$catnom = "";
$catdominio = "";
$catdesc ="";
$semuestramenu="1";
$onclick = "return InsertarCategorias();";
$planthtmlcod='';
$catdatajson = "";
$numfilasplantillas=0;

$menutipocod="";
$menucod = "";	
$fondocod="";


$imgubic = "";
$imgnombre = "";
$imgsize = "";



$oMenuTipo = new cTapasMenuTipos($conexion);
$datostipo = array();
$datostipo["menutipocod"] ="";
if(!$oMenuTipo->Buscar($datostipo,$resultadoTipos,$numfilasTipos))
	return false;
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	//$titulo = "Modificación de Ciudad - ". FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscategorias['provinciadesc'],ENT_QUOTES).")";
	$catcod = $datoscategorias['catcod'];
	$catnom = $datoscategorias['catnom'];
	$planthtmlcod=$datoscategorias['planthtmlcod'];
	$catdominio = $datoscategorias['catdominio'];
	$catdesc = $datoscategorias['catdesc'];
	$catcolor = $datoscategorias['catcolor'];
	$semuestramenu=$datoscategorias['semuestramenu'];
	$fondocod=$datoscategorias['fondocod'];

	$imgubic = $datoscategorias["imgubic"];
	$imgnombre = $datoscategorias["imgnombre"];
	$imgsize = $datoscategorias["imgsize"];


	$onclick = "return ModificarCategorias();";
	if ($datoscategorias['catdatajson']!="")
		$catdatajson = json_decode($datoscategorias['catdatajson']);

	if($datoscategorias["menutipocod"]==0 || $datoscategorias["menucod"]==0){
		$datoscategorias["menucod"]=="";
		$datoscategorias["menutipocod"]=="";
		if(!$oMenuTipo->Buscar($datoscategorias,$resultado,$numfilasTipos))
			return false;
	}else{
		$menucod = $datoscategorias["menucod"];
		$menutipocod = $datoscategorias['menutipocod'];
		
		if(!$oMenuTipo->BuscarxCodigo($datoscategorias,$resultado,$numfilas))
			return false;
	}	
	if ($numfilas>0)
	{
		$datostipo = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oMenu->BuscarxTipo($datostipo,$resultado,$numfilas))
			return false;
	}
}
$datos=array();
$PlantillasHtml = new cPlantillasHtml($conexion);

if (!$PlantillasHtml->BusquedaAvanzada($datos,$resultado,$numfilasplantillas))
	return false;

if ($numfilasplantillas==1)
	$datosplantilla = $conexion->ObtenerSiguienteRegistro($resultado);


$_SESSION['msgactualizacion'] = "";

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

$oMenu-> ArmarArbol($datostipo,"",$arbol);

?>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<link type="text/css" rel="stylesheet" href="modulos/not_categorias/css/not_categorias.css" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>
<script type="text/javascript" src="modulos/not_categorias/js/not_categorias_am.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	$("#catcolor").miniColors();
	
});
</script>
    <div class="form">
        <div >
            <form action="ciudades.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales ">
                    <div>
                        <label>Nombre</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div class="ancho_5">
                        <input type="text" name="catnom"  id="catnom" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catnom,ENT_QUOTES)?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div >
                        <label>Dominio</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div class="ancho_5">
                        <input type="text" name="catdominio"  id="catdominio" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catdominio,ENT_QUOTES)?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    
                    <div class="ancho_5 ">
                    
                        <div>
                            <label>Color</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" class="full-color" value="<?php  echo $catcolor?>" id="catcolor" name="catcolor" maxlength="7" size="10" />(Hexadecimal)
                        </div>
                       <div class="clear" style="height:10px;">&nbsp;</div>
                        <div>
                            <label>Tipo de Men&uacute;:</label>
                        </div>
                       <div class="clear" style="height:5px;">&nbsp;</div>
                        <div>
                            <select data-placeholder="Todos el tipo de menu..." name="menutipocod" id="menutipocod" style="width:100%;" class="chzn-select"  onchange="return CargarMenu()">
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
                            <select data-placeholder="Todos los menu..." name="menucod" id="menucod" style="width:100%;" class="chzn-select" >
                                 <option value="">Sin men&uacute;...</option>
                                <?php 
                                    foreach($arbol as $fila)
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
                                    ?>
                             </select>
                             <?php  }else{?>
                                <select data-placeholder="Todos los menu..." name="menucod" id="menucod" style="width:100%;" class="chzn-select"  >
                                     <option value="" selected="selected">Sin men&uacute;...</option>
                                 </select>
                             <?php  }?>
                        </div>                    
                        <div class="clear" style="height:10px;">&nbsp;</div>
                        <?php  /* 
                        <div>
                            <label>Fondo:</label>
                        </div>
                       <div class="clear" style="height:5px;">&nbsp;</div>
                       <div>
                            <?php  
                                    $oFondos=new cFondos($conexion);
                                    $oFondos->FondosSP($spnombre,$sparam);
                                    FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","fondocod","fondocod","fondodesc",$fondocod,"Fondos...",$regnousar,$selecnousar,1,"","width: 400px",false,false,"",false,"chzn-select",16); 
                                ?>
                        </div>                    
                        <div class="clear" style="height:10px;">&nbsp;</div>
                        */ ?>
                    <div>
                        <label>Plantilla General:</label>
                         <?php  
                            if($numfilasplantillas!=1){
                                $PlantillasHtml = new cPlantillasHtml($conexion);
                                $PlantillasHtml->PlantillasHtmlSP($spnombre,$sparam);
                                FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","planthtmlcod","planthtmlcod","planthtmldesc",$planthtmlcod,"Seleccione un html...",$regactual,$seleccionado,1,"",false,false);
                            }else{
                                echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosplantilla['planthtmldesc'],ENT_QUOTES);	
                                ?> <input type="hidden" name="planthtmlcod" id="planthtmlcod" value="<?php  echo $datosplantilla['planthtmlcod']?>" /><?php 
                            }
                            
                         ?>                     
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div>
                        <label>Visualizaci&oacute;n de noticias</label>
                        <?php  
                            $oVisualizaciones = new cVisualizaciones($conexion);
                            $datos['visualizaciontipocod'] = 1;
                            $visualizacioncod="";
                            if (isset($catdatajson->visualizacioncod))
                                $visualizacioncod = $catdatajson->visualizacioncod;
                            $oVisualizaciones->VisualizacionesSPxTipoActivos($datos,$spnombre,$sparam);
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","visualizacioncod","visualizacioncod","visualizaciondesc",$visualizacioncod,"Seleccione una visualizacion...",$regactual,$seleccionado,1,"",false,false);
                        ?>
                    </div>
                    <div class="clearboth">&nbsp;</div>
    
             
                        <input type="hidden" name="semuestramenu" id="semuestramenu" value="0" />
                        <?php  /* 
                        <div>
                            <label>Se muestra menu:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                             <div class="float-left">
                                <select name="semuestramenu" id="semuestramenu">
                                    <option value="1" <?php  if ($semuestramenu==1) {echo 'selected="selected"';}?>>Si</option>
                                    <option value="0"  <?php  if ($semuestramenu==0) {echo 'selected="selected"';}?>>No</option>
                                </select>
                            </div>
                        <div class="clearboth aire_menor">&nbsp;</div>    
                        */?>
                  </div>
                   </div>
                   <div class="ancho_2">&nbsp;</div>
                  <div class="ancho_3">
                  	<?php  if ($esmodif){?>
                        <div id="ImgFondo">
                            <?php  if (isset($imgubic ) && $imgubic!=""){  ?>
                                <img src="<?php  echo CARPETA_SERVIDOR_MULTIMEDIA."categorias/".$imgubic."";?>" />
                            <?php  }else{ ?>
                                &nbsp;
                            <?php  }?>
                        </div>
                        <div class="clearboth">&nbsp;</div>
                        <span>La imagen se guarda automaticamente</span>
                        <div class="clearboth">&nbsp;</div>
                        <div id="btn_subirImgMostrar" ></div> 
                        <input type="hidden" name="imagen" id="imagen" value="" />
                        <input type="hidden" name="size" id="size"  value="" />
                        <input type="hidden" name="name" id="name"  value="" />
                        <input type="hidden" name="file" id="file"  value="" />
                        <div class="clearboth">&nbsp;</div>
                    <?php  }?>
                    
                 </div>
                 <div class="clearboth aire_vertical">&nbsp;</div>    
                <div>
                    <label>Descripci&oacute;n</label>
                </div>
                <div class="clearboth" style="height:1px;">&nbsp;</div>
                <div>
                    <textarea name="catdesc" class="textarea full rich-text" id="catdesc" rows="5" cols="40"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catdesc,ENT_QUOTES)?></textarea>
                </div>
                <div class="clearboth aire_vertical">&nbsp;</div>    
            
 
                 <div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><a class="left boton base" href="not_categorias.php<?php  echo ($catsuperior!="")?"?catsuperior=".$catsuperior:""?>">Volver</a></li>
                            <li><a class="left boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                    <div class="msgaccionupd">&nbsp;</div>
                </div>
                <div class="clearboth">&nbsp;</div>
 		
            	<input type="hidden" name="catsuperior" id="catsuperior" value="<?php  echo $catsuperior?>" />
                <input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod?>" />
            </form>
            
        </div>
    </div>
<div class="clearboth">&nbsp;</div>

<?php  

$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";

?>