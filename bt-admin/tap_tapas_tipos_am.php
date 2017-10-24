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

$oTipos= new cTapasTipos($conexion);
$oMenu= new cTapasMenu($conexion);

if (isset($_POST['tapatipocod']) && $_POST['tapatipocod']!="")
{
	if (!FuncionesPHPLocal::ValidarContenido($conexion,$_POST['tapatipocod'],"NumericoEntero"))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar el tipo de tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	$esmodif = true;
	if (!$oTipos->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Tipo de tapa inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosencontrados = $conexion->ObtenerSiguienteRegistro($resultado);	
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$tapatipocod="";
$tapatipodesc="";
$tapatipoarchivo="";
$tapatipourlfriendly = "";
$tapatipoestado = "";
$tapatipohome = 0;
$menucod="";


$menutipocod="";
$menucod = "";	
$fondocod= "";
$onclick = "return InsertarTipo();";
$oMenuTipo = new cTapasMenuTipos($conexion);
$datostipo = array();
$datostipo["menutipocod"] ="";
if(!$oMenuTipo->Buscar($datostipo,$resultadoTipos,$numfilasTipos))
	return false;

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$tapatipocod=$datosencontrados['tapatipocod'];
	$tapatipodesc=$datosencontrados['tapatipodesc'];
	$tapatipoarchivo=$datosencontrados['tapatipoarchivo'];
	$tapatipourlfriendly=$datosencontrados['tapatipourlfriendly'];
	$tapatipoestado=$datosencontrados['tapatipoestado'];
	$tapatipohome=$datosencontrados['tapatipohome'];
	$fondocod=$datosencontrados['fondocod'];
	$onclick = "return ModificarTipo();";
	if($datosencontrados["menutipocod"]==0){
		$datosencontrados["menucod"]=="";
		$datosencontrados["menutipocod"]=="";
		
		if(!$oMenuTipo->Buscar($datosencontrados,$resultado,$numfilasTipos))
			return false;
	}else{
		$menucod = $datosencontrados["menucod"];
		$menutipocod = $datosencontrados['menutipocod'];
		
		if(!$oMenuTipo->BuscarxCodigo($datosencontrados,$resultado,$numfilas))
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
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>

    <div style="text-align:left">
        <div class="form">
            <form action="tap_tapas_tipos.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>
                    <div>
                        <input type="text" name="tapatipodesc"  id="tapatipodesc" class="full" value="<?php  echo $tapatipodesc?>" size="90" maxlength="255">
                    </div>
                   <div class="clear" style="height:10px;">&nbsp;</div>
                    <div>
                        <label>Url:</label>
                    </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>
                    <div>
                        <input type="text" name="tapatipourlfriendly"  id="tapatipourlfriendly" class="full" value="<?php  echo $tapatipourlfriendly?>" size="90" maxlength="255">
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
                   <div class="clear" style="height:10px;">&nbsp;</div>
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
                   <div class="clear" style="height:15px;">&nbsp;</div>
                   <div>
                        <label>Es Portada Home (index - Inicio):</label>
                   </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>
                   <div>
                        <div class="ancho_1">
                        	<input type="radio" style="width:20px;float:right" name="tapatipohome" id="tapatipohome_0" value="0" <?php  if ($tapatipohome==0) echo 'checked="checked"'; ?>  /><label for="tapatipohome_0">No</label>
                        </div>
                        <div class="ancho_1">
                        	<input type="radio" style="width:20px; float:right" name="tapatipohome" id="tapatipohome_1" value="1" <?php  if ($tapatipohome==1) echo 'checked="checked"'; ?> /><label for="tapatipohome_1">Si</label>
                   		</div>
                   </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>
                   <div style="font-size:10px;">Solo podr&aacute; existir solo una portada home</div>
                   <div class="clear" style="height:10px;">&nbsp;</div>

                    <div class="menubarra">
                        <ul>
                            <li><a class="left boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="tapatipocod" id="tapatipocod" value="<?php  echo $tapatipocod?>" />
                
            </form>
        </div>
    </div>
