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

$oTapas= new cTapas($conexion);

if (isset($_POST['tapacod']) && $_POST['tapacod']!="")
{
	$esmodif = true;
	if (!$oTapas->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Tapa inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datostapas = $conexion->ObtenerSiguienteRegistro($resultado);	

}

$botonejecuta = "BtAlta";
$boton = "Alta";
$tapacod="";
$tapanom = "";
$tapatipodesc ="";
$plantdesc='';
$tapaestado = "";
$plantcod="";
$tapatipocod="";
$onclick = "return InsertarTapas();";

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$tapacod=$datostapas['tapacod'];
	$tapanom=$datostapas['tapanom'];
	$tapatipodesc=$datostapas['tapatipodesc'];
	$plantdesc=$datostapas['plantdesc'];
	$tapaestado=$datostapas['tapaestado'];
	$plantcod=$datostapas['plantcod'];
	$tapatipocod=$datostapas['tapatipocod'];
	$onclick = "return ModificarTapas();";
}
?>
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />

    <div style="text-align:left">
        <div class="form">
            <form action="tap_tapas.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Nombre:</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <input type="text" name="tapanom"  id="tapanom" class="full" value="<?php  echo $tapanom?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Plantilla:</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
						 <?php  
						 if (!$esmodif)
						 {
                            $oPlantillas = new cPlantillas($conexion);
                            $oPlantillas->PlantillasSP($spnombre,$sparam);
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","plantcod","plantcod","plantdesc",$plantcod,"Seleccione un tipo...",$regactual,$seleccionado,1,"",false,false);
						 }else
						 {
							echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datostapas['plantdesc'],ENT_QUOTES);	 
							 
						 }
                     	 ?>                     
                   </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                     <div>
                        <label>Tapa Tipo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
         
                     <div>
						 <?php  
                            $oTapasTipos = new cTapasTipos($conexion);
                            $oTapasTipos->TapasTiposSP($spnombre,$sparam);
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","tapatipocod","tapatipocod","tapatipodesc",$tapatipocod,"Seleccione un tipo...",$regactual,$seleccionado,1,"",false,false);
					  	?>                     
                    </div>
                   
                   <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="tapaestado" id="tapaestado" value="<?php  echo $tapaestado?>" />
                <input type="hidden" name="tapacod" id="tapacod" value="<?php  echo $tapacod?>" />
            </form>
        </div>
    </div>
