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

$oPlantillas=new cPlantillasHtml($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$planthtmlcod="";
$planthtmldesc="";
$planthtmldisco="";
$planthtmlheader = "";
$planthtmlfooter = "";
$planthtmldefault = 0;
$accion = 1;
$edit = false;
$funcionJs="return Insertar()";
$boton = "botonalta";
$botontexto = "Alta de Plantilla HTML";
$esbaja  = false;

if (isset($_GET['planthtmlcod']) && $_GET['planthtmlcod']!="")
{
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("planthtmlcod"=>$_GET['planthtmlcod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	
	$planthtmlcod = $_GET['planthtmlcod'];
	if (!$oPlantillas->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la plantilla html por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosencontrados = $conexion->ObtenerSiguienteRegistro($resultado);	


	$funcionJs="return Actualizar()";
	$edit = true;
	$boton = "botonmodif";
	$accion = 2;
	$botontexto = "Actualizar Plantilla HTML";

	$planthtmlcod = $datosencontrados['planthtmlcod']; 
	$planthtmldesc = $datosencontrados['planthtmldesc']; 
	$planthtmldisco = $datosencontrados['planthtmldisco']; 
	$planthtmlheader = $datosencontrados['planthtmlheader']; 
	$planthtmlfooter = $datosencontrados['planthtmlfooter']; 
	$planthtmldefault = $datosencontrados['planthtmldefault']; 
}

FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_upd.php",array("planthtmlcod"=>$planthtmlcod),$getupd,$md5upd);

?>
<link href="modulos/tap_plantillas/css/plantillas_html_am.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/tap_plantillas/js/tap_plantillas_html_am.js"></script>

<div id="contentedor_modulo">
	<div id="contenedor_interno">
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2 id="titulo">Plantilla HTML</h2>
</div>  
<div class="form">
<form action="tap_plantillas_html_upd.php" method="post" name="formulario" id="formulario">
    <input type="hidden" name="planthtmlcod" id="planthtmlcod" value="<?php  echo $planthtmlcod;?>" />
    <input type="hidden" name="md5" id="md5" value="<?php  echo $md5upd;?>" />
    <input type="hidden" name="accion" id="accion" value="<?php  echo $accion?>">
	<div class="ancho_10">
         <div class="ancho_10">
            <div class="datosgenerales">
                <div>
                    <label>Descripci&oacute;n:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="planthtmldesc" id="planthtmldesc" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmldesc,ENT_QUOTES);?>" />
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Es plantilla Default:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="ancho_9">
                 <div class="ancho_1">
                     <label class="radio_label" for="planthtmldefault_no">No</label>
                	 <input type="radio" style="width:20%; margin-top:8px" name="planthtmldefault" <?php  if ($planthtmldefault==0) echo 'checked="checked"'?>  id="planthtmldefault_no" value="0" />
                  </div>
                  <div class="ancho_1">
                     <label class="radio_label" for="planthtmldefault_si">Si</label>
                	 <input type="radio" style="width:20%; margin-top:8px" name="planthtmldefault" <?php  if ($planthtmldefault==1) echo 'checked="checked"'?> id="planthtmldefault_si" value="1" />
                  </div>
                </div>
            
                   <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Ubicaci&oacute;n plantilla:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="planthtmldisco" id="planthtmldisco" class="full" maxlength="255" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmldisco,ENT_QUOTES);?>" />
                </div>                        
                
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>HTML Header</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <textarea name="planthtmlheader" id="planthtmlheader" class="textarea full" rows="10" cols="40" ><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmlheader,ENT_QUOTES);?></textarea>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>

                <div>
                    <label>HTML Footer</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <textarea name="planthtmlfooter" id="planthtmlfooter" class="textarea full" rows="10" cols="40" ><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmlfooter,ENT_QUOTES);?></textarea>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>

           </div>
        </div>

        <div style="clear:both">&nbsp;</div>
    </div>
    <div class="clear aire_vertical">&nbsp;</div>
    <div class="msgaccionhtml">&nbsp;</div> 
    <div class="ancho_10">
     <div class="menubarra">
         <ul>
                <li><a class="left boton verde" href="javascript:void(0)" onclick="<?php  echo $funcionJs?>"><?php  echo $botontexto?></a></li>
                <?php  if ($edit) {?>
                	<li><a class="left boton rojo" href="javascript:void(0)" onclick="Eliminar(<?php  echo $planthtmlcod;?>)">Eliminar</a></li>
                <?php  }?>
                <li><a class="left boton base" href="tap_plantillas_html.php">Volver sin guardar</a></li>
        </ul>
     </div>
  </div>
</form>
<div>
<div class="clear aire_vertical">&nbsp;</div>
<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>