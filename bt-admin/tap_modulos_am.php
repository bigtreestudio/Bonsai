<? 

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

$oTapasModulosConfeccionar= new cTapasModulos($conexion);
$oTapasModulosTipos= new cTapasModulosTipos($conexion);

if (isset($_POST['modulocod']) && $_POST['modulocod']!="")
{
	$modulocod=$_POST['modulocod'];
	$esmodif = true;
	if (!$oTapasModulosConfeccionar->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Modulo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosmodulos = $conexion->ObtenerSiguienteRegistro($resultado);	

}


$botonejecuta = "BtAlta";
$boton = "Alta";
$modulocod="";
$modulodesc = "";
$moduloarchivo ="";
$catcod = "";
$modulotipocod="";
$moduloaccesodirecto ='0';
$moduloicono = '';

$onclick = "return InsertarModulo();";

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$modulocod=$datosmodulos['modulocod'];
	$catcod=$datosmodulos['catcod'];
	$catdesc=$datosmodulos['catdesc'];
	$modulodesc=$datosmodulos['modulodesc'];
	$moduloarchivo=$datosmodulos['moduloarchivo'];
	$moduloestado=$datosmodulos['moduloestado'];
	$modulotipocod=$datosmodulos['modulotipocod'];
	$moduloaccesodirecto=$datosmodulos['moduloaccesodirecto'];
	$moduloicono = $datosmodulos['moduloicono'];	
	
	$onclick = "return ModificarModulo();";
}
?>
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />

    <div style="text-align:left">
        <div class="form">
            <form action="tap_modulos.php" method="post" name="formulario" onsubmit="return false;" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Nombre del m&oacute;dulo:</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
                        <input type="text" name="modulodesc"  id="modulodesc" class="full" value="<?=$modulodesc?>" size="70" maxlength="255">
                    </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>                    
                    <div>
                        <label>Tipo:</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
						 <? 
                            $oTapasModulosTipos->BuscarSP($spnombre,$sparam);
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","modulotipocod","modulotipocod","modulotipodesc",$modulotipocod,"Seleccione un tipo...",$regactual,$seleccionado,1,"",false,false);
                  			?>  
                   </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>                    
                    <div>
                        <label>Categor&iacute;a:</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
						 <? 
                            $oTapasModulosConfeccionar->TapasModulosConfeccionarCategoriasSP($spnombre,$sparam);
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","catcod","catcod","catdesc",$catcod,"Seleccione una categoria...",$regactual,$seleccionado,1,"",false,false);
                  			?>  
                   </div>
                   <div class="clear" style="height:5px;">&nbsp;</div>                    
                    <div>
                        <label>Archivo:</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
                        <input type="text" name="moduloarchivo"  id="moduloarchivo" class="full" value="<?=$moduloarchivo?>" size="70" maxlength="255">
                    </div>         
                   <div class="clear" style="height:5px;">&nbsp;</div>
                   <div class="clear" style="height:5px;">&nbsp;</div>                    
                    <div>
                        <label>Acceso Directo:</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
                           <label class="radio_label">SI</label>
                          <input type="radio" name="moduloaccesodirecto" class="radio" id="moduloaccesodirecto_SI" value="1" <? if ($moduloaccesodirecto=="1"){echo "checked";}?>/>
                          <label class="radio_label">NO</label>
                          <input type="radio" tabindex="6" class="radio" name="moduloaccesodirecto" id="moduloaccesodirecto_NO" value="0" <? if ($moduloaccesodirecto=="0"){echo "checked";}?> />
                    </div>         
                   <div class="clear" style="height:5px;">&nbsp;</div>
                    <div>
                        <label>Icono:</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
                        <input type="text" name="moduloicono"  id="moduloicono" class="full" value="<?=$moduloicono?>" size="70" maxlength="255">
                    </div>         
                   <div class="clear" style="height:5px;">&nbsp;</div>                    
                    <div class="menubarra">
                        <ul>
                            <li><a class="boton verde" name="<? echo $botonejecuta?>" value="<? echo $boton?>" href="javascript:void(0)"  onclick="<? echo $onclick?>">Guardar</a></li>
                            <li><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="modulocod" id="modulocod" value="<?=$modulocod?>" />
                <input type="hidden" name="moduloestado" id="moduloestado" value="<?=$moduloestado?>" />


            </form>
        </div>
    </div>
