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

$oFormulariosTipos= new cFormulariosTipos($conexion);



if (isset($_POST['formulariotipocod']) && $_POST['formulariotipocod']!="")
{

	$esmodif = true;
	if (!$oFormulariosTipos->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Formulario inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosformulario = $conexion->ObtenerSiguienteRegistro($resultado);	

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
//SACAR CATCOD Y ENCUESTATIPOCOD!
$formulariotipodesc  = "";

$onclick = "return InsertarFormulariosTipos();";

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$formulariotipodesc=$datosformulario['formulariotipodesc'];
	$onclick = "return ModificarFormulariosTipos();";
	if($datosformulario["menutipocod"]==0){
		$datosformulario["menucod"]="";
		$datosformulario["menutipocod"]="";
		
		if(!$oMenuTipo->Buscar($datosformulario,$resultado,$numfilasTipos))
			return false;
	}else{
		$menucod = $datosformulario["menucod"];
		$menutipocod = $datosformulario['menutipocod'];
		
		if(!$oMenuTipo->BuscarxCodigo($datosformulario,$resultado,$numfilas))
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
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>

    <div style="text-align:left">
        <div class="form">
            <form action="enc_encuestas.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Nombre:</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariotipodesc"  id="formulariotipodesc" class="full" value="<?php  echo $formulariotipodesc?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>    
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
                    </div>                     <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="formulariotipocod" id="formulariotipocod" value="<?php  echo $_POST['formulariotipocod']?>" />

                
            </form>
        </div>
    </div>
