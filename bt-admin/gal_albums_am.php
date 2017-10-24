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

$oAlbums = new cAlbums($conexion,"");


if(isset($_POST['albumsuperior']) && $_POST['albumsuperior']!="")
	$albumsuperior= $_POST['albumsuperior'];
else
	$albumsuperior ="";
//print_r($_POST);
$albumdominio="";
if (isset($_POST['albumcod']) && $_POST['albumcod'])
{
	$esmodif = true;
	if (!$oAlbums->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Albums.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosalbums = $conexion->ObtenerSiguienteRegistro($resultado);
	
	$albumdominio = $datosalbums['albumdominio'];
}
$oMenu= new cTapasMenu($conexion);
$oMenuTipo = new cTapasMenuTipos($conexion);
$menutipocod="";
$menucod = "";	
$datostipo = array();
$datostipo["menutipocod"] ="";

if(!$oMenuTipo->Buscar($datostipo,$resultadoTipos,$numfilasTipos))
	return false;
	
$botonejecuta = "BtAlta";
$boton = "Alta";
//$titulo = "Alta de Ciudad - ";
$albumcod="";
$albumtitulo="";
$albumestadocod="";
$onclick = "return InsertarAlbums();";
if ($esmodif)
{
	$albumcod=$datosalbums['albumcod'];
	$albumtitulo=$datosalbums['albumtitulo'];
	$albumestadocod=$datosalbums['albumestadocod'];
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$onclick = "return ModificarAlbum();";

	if($datosalbums["menutipocod"]==0){
		$datosalbums["menucod"]="";
		$datosalbums["menutipocod"]="";
		
		if(!$oMenuTipo->Buscar($datosalbums,$resultado,$numfilasTipos))
			return false;
	}else{
		$menucod = $datosalbums["menucod"];
		$menutipocod = $datosalbums['menutipocod'];
		
		if(!$oMenuTipo->BuscarxCodigo($datosalbums,$resultado,$numfilas))
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
<link rel="stylesheet" type="text/css" href="modulos/gal_albums/css/gal_albums.css" />
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>

    <div style="text-align:left">
        <div class="form">
            <form action="gal_albums.php" method="post" name="formulario" id="formulario" onsubmit="return false;">
                <div class="datosgenerales">
                    <div>
                        <label>Titulo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="albumtitulo"  id="albumtitulo" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($albumtitulo,ENT_QUOTES)?>" size="90" maxlength="255">
                    </div>
                    <?php  if ($esmodif){?>
                        <div class="clearboth aire_menor">&nbsp;</div>
                        <div>
                            <label>Url de la &Aacute;lbum</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" style="background-color:#E9E9E9" readonly="readonly" name="albumdominio"  id="albumdominio" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($albumdominio,ENT_QUOTES)?>" size="90">
                        </div>
                    <?php  }?>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Estado:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                         <div class="float-left">
                         	<select name="albumestadocod"  id="albumestadocod">
                                <option value="<?php  echo ACTIVO?>" <?php  if($albumestadocod==ACTIVO)echo 'selected="selected"';?> >Publicada</option>
                                <option value="<?php  echo NOACTIVO?>" <?php  if($albumestadocod==NOACTIVO)echo 'selected="selected"';?>>No Publicada</option>
                            </select>
                        </div>
                    <div class="clearboth aire_menor">&nbsp;</div> 
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
                    <div class="clear" style="height:10px;">&nbsp;</div>
                     <div class="clearboth brisa_vertical">&nbsp;</div>                  
                    <div class="menubarra">
                        <ul>
                            <li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
            	<input type="hidden" name="albumsuperior" id="albumsuperior" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($albumsuperior,ENT_QUOTES)?>" />
                <input type="hidden" name="albumcod" id="albumcod" value="<?php  echo $albumcod?>" />
        
            </form>
        </div>
    </div>
