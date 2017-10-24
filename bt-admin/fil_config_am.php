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


if (!isset($_GET['filecod']) || $_GET['filecod']=="" || !is_numeric($_GET['filecod']))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("filecod"=>$_GET['filecod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$oFilesConfig=new cFilesConfig($conexion);
if(!$oFilesConfig->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;
	
$datosFile = $conexion->ObtenerSiguienteRegistro($resultado);	
//----------------------------------------------------------------------------------------- 	
FuncionesPHPLocal::ArmarLinkMD5("fil_config_upd.php",array("filecod"=>$_GET['filecod']),$get_upd,$md5_upd);


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$fileexists = false;
if (file_exists($datosFile['fileubic']))
{
	$fileexists = true;
	$filedetalle = file_get_contents($datosFile['fileubic']);
}
?>
<script type="text/javascript" src="modulos/file_config/js/file_am.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Editar Archivo <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFile['filenombre'],ENT_QUOTES);?> (<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFile['filetipodesc'],ENT_QUOTES);?>)</h2>
</div>

<div class="clearboth">&nbsp;</div>

<div style="text-align:left;" class="clearfix">
    <form  method="post" class="general_form" action="fil_config.php" name="formfile" id="formfile">
        <div >
            <div>
                 <h2 style="font-size:14px; font-weight:bold">El archivo se encuentra en &quot;<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosFile['fileubic'],ENT_QUOTES);?>&quot;</h2>
            </div>
            <div style="clear:both; height:10px;">&nbsp;</div>
            <div style="font-size:11px; color:#F00">
            	<b>Nota Importante:</b>&nbsp;Recuerde que cualquier modificaci&oacute;n en los archivos puede afectar
                directamente tanto el portal como en el admnistrador.  Testee los cambios en un ambiente de testing 
                previo a realizarlos.
            </div>
            <div style="clear:both; height:10px;">&nbsp;</div>
            <div class="clearboth" style="height:10px;">&nbsp;</div>
            <div>
                <label>Detalle del archivo:</label>
            </div>
            <div class="clearboth" style="height:1px;">&nbsp;</div>
            <div>
            	<textarea name="filedetalle" id="filedetalle" rows="20" cols="30" class="full"><?php  echo  $filedetalle?></textarea>
            </div>
            <div style="clear:both; height:10px;">&nbsp;</div>
            <div class="ancho_10">
                <div class="menubarra">
                     <ul>
                        <?php  if ($fileexists){?>
                        	<li><a class="left" href="javascript:void(0)" onclick="Actualizar()">Guardar</a></li>
                        <?php  }?>
                        <li><a class="left" href="fil_config.php" >Volver</a></li>
                    </ul>
                </div>
            </div>   
            <div class="clearboth">&nbsp;</div>
		</div>
        <input type="hidden" name="filecod" id="filecod" value="<?php  echo $datosFile['filecod']?>" />
        <input type="hidden" name="md5" id="md5" value="<?php  echo $md5_upd?>" />
        <input type="hidden" name="accion" id="accion" value="2" />
	</form>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
