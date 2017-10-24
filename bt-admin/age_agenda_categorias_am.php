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

$oCategorias = new cAgendaCategorias($conexion,"");
$oPlantillas = new cPlantillas($conexion,"");


if(isset($_POST['catsuperior']) && $_POST['catsuperior']!="")
	$catsuperior= $_POST['catsuperior'];
else
	$catsuperior ="";
//print_r($_POST);
if (isset($_POST['catcod']) && $_POST['catcod'])
{
	$esmodif = true;
	if (!$oCategorias->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datoscategorias = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
//$titulo = "Alta de Ciudad - ";
$catcod="";
$catnom = "";
$catdesc ="";
$planthtmlcod='';
$numfilasplantillas=0;
$onclick = "return InsertarCategorias();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	//$titulo = "Modificación de Ciudad - ". FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscategorias['provinciadesc'],ENT_QUOTES).")";
	$catcod = $datoscategorias['catcod'];
	$catnom = $datoscategorias['catnom'];
	$catestado = $datoscategorias['catestado'];
	$planthtmlcod=$datoscategorias['planthtmlcod'];
	$catdesc = $datoscategorias['catdesc'];
	$onclick = "return ModificarCategorias();";
}

$datos=array();
$PlantillasHtml = new cPlantillasHtml($conexion);

if (!$PlantillasHtml->BusquedaAvanzada($datos,$resultado,$numfilasplantillas))
	return false;

if ($numfilasplantillas==1){
	$datosplantilla = $conexion->ObtenerSiguienteRegistro($resultado);
}
?>
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />

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
                            <li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
            	<input type="hidden" name="catsuperior" id="catsuperior" value="<?php  echo $catsuperior?>" />
                <input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod?>" />
                <input type="hidden" name="catestado" id="catestado" value="<?php  echo $catestado?>" />

            </form>
        </div>
    </div>
