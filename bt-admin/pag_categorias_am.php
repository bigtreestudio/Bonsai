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



$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

$oPaginasCategorias = new cPaginasCategorias($conexion,"");
$oPlantillas = new cPlantillas($conexion,"");
$oMenu= new cTapasMenu($conexion);

if(isset($_POST['catsuperior']) && $_POST['catsuperior']!="")
	$catsuperior= $_POST['catsuperior'];
else
	$catsuperior ="";
//print_r($_POST);
if (isset($_POST['catcod']) && $_POST['catcod'])
{
	$esmodif = true;
	if (!$oPaginasCategorias->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datoscategorias = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
//$titulo = "Alta de Ciudad - ";
$catcod="";
$catnom = "";
$catdominio = "";
$catdesc ="";
$catestado= ACTIVO;
$planthtmlcod='';
$numfilasplantillas=0;
$onclick = "return InsertarCategorias();";

$menutipocod="";
$menucod = "";	
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
	$catdesc = $datoscategorias['catdesc'];
	$catestado=$datoscategorias['catestado'];
	$onclick = "return ModificarCategorias();";


	if($datoscategorias["menutipocod"]==0){
		$datoscategorias["menucod"]=="";
		$datoscategorias["menutipocod"]=="";
		
		if(!$oMenuTipo->Buscar($datoscategorias,$resultado,$numfilasTipos))
			return false;
	}else{
		$menucod = $datoscategorias["menucod"];
		$menutipocod = $datoscategorias['menutipocod'];
		
		if(!$oMenuTipo->BuscarxCodigo($datoscategorias,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, tipo de menu inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	}	
		
	$datostipo = $conexion->ObtenerSiguienteRegistro($resultado);
	if(!$oMenu->BuscarxTipo($datostipo,$resultado,$numfilas))
		return false;


}

$datos=array();
$PlantillasHtml = new cPlantillasHtml($conexion);

if (!$PlantillasHtml->BusquedaAvanzada($datos,$resultado,$numfilasplantillas))
	return false;

if ($numfilasplantillas==1){
	$datosplantilla = $conexion->ObtenerSiguienteRegistro($resultado);
}

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
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>

    <div style="text-align:left">
        <div class="form ">
            <form action="ciudades.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Nombre</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    
                    <div>
                        <input type="text" name="catnom"  id="catnom" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catnom,ENT_QUOTES)?>" size="90" maxlength="255">
                    </div>
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
                            <select data-placeholder="Todos los menu..." name="menucod" id="menucod" style="width:400px;" class="chzn-select"  >
                                 <option value="" selected="selected">Sin men&uacute;...</option>
                             </select>
                         <?php  }?>
                    </div>                    

                    <div class="clearboth aire_menor">&nbsp;</div>
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
                    <div class="clearboth aire_menor">&nbsp;</div>                                      
                    <div>
                        <label>Descripci&oacute;n</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <textarea name="catdesc" class="textarea full" id="catdesc" rows="5" cols="40"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catdesc,ENT_QUOTES)?></textarea>
                    </div>
        
                    <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
            	<input type="hidden" name="catsuperior" id="catsuperior" value="<?php  echo $catsuperior?>" />
                <input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod?>" />
        
            </form>
        </div>
    </div>

