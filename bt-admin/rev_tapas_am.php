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

$esmodif = false;

$oRevistaTapas= new cRevistaTapas($conexion);
$oRevistaTapasTipos= new cRevistaTapasTipos($conexion);

if (isset($_GET['revtapacod']) && $_GET['revtapacod']!="")
{
	$revtapacod=$_GET['revtapacod'];
	$esmodif = true;
	if (!$oRevistaTapas->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Tapa inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosrevtapas = $conexion->ObtenerSiguienteRegistro($resultado);	

}

$botonejecuta = "BtAlta";
$boton = "Alta";

$revtapacod="";
$revtapatipocod="";
$revtapatitulo = "";
$revtapadesc ="";
$revtapaestado = "";
$revtapafecha = date("d/m/Y");
$revtapatarget = "_self";
$revtapalink = "";
$revtapanumero = "";
$revtapaarchnombre="";
$revtapaarchubic="";
$revtapaarchsize="";
$onclick = "return InsertarRevTapa();";

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$revtapacod=$datosrevtapas['revtapacod'];
	$revtapatipocod=$datosrevtapas['revtapatipocod'];
	$revtapatitulo=$datosrevtapas['revtapatitulo'];
	$revtapadesc=$datosrevtapas['revtapadesc'];
	$revtapaestado=$datosrevtapas['revtapaestado'];
	$revtapaarchnombre=$datosrevtapas['revtapaarchnombre'];
	$revtapaarchubic=$datosrevtapas['revtapaarchubic'];
	$revtapaarchsize=$datosrevtapas['revtapaarchsize'];
	$revtapafecha = FuncionesPHPLocal::ConvertirFecha($datosrevtapas['revtapafecha'],"aaaa-mm-dd","dd/mm/aaaa");
	$revtapatarget = $datosrevtapas['revtapatarget'];
	$revtapalink = $datosrevtapas['revtapalink'];
	$revtapanumero =$datosrevtapas['revtapanumero'];
	$onclick = "return ModificarRevTapa();";
}

//busco los tipos de tapas
$datos=array(
	'revtapatipoestado'=>ACTIVO.",".NOACTIVO
);
if (!$oRevistaTapasTipos->BusquedaAvanzada($datos,$resultadoTipos,$numfilasTipos))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar tipos de tapas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
}
?>
<link rel="stylesheet" type="text/css" href="modulos/rev_tapas/css/estilos.css" />
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="modulos/rev_tapas/js/rev_tapas_am.js"></script>
<script type="text/javascript" src="modulos/rev_tapas/js/rev_tapas_tapa_multimedia.js"></script>

<script type="text/javascript">var sizeLimitFile = <?php  echo TAMANIOARCHIVOS;?>;</script>
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>

<?php 
if ($esmodif){?>
	<script type="text/javascript">
    jQuery(document).ready(function(){
        CrearBtUploadBanner("mul_multimedia_bt_subir_img","#mul_multimedia_previsualizar");
    }); 
    </script>
<?php  } ?>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Tapa</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div class="form Areas_Datos"> 
    <div style="text-align:left">
            <form action="are_areas.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales ancho_5">
                    <div>
                        <label>Titulo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="revtapatitulo"  id="revtapatitulo" class="full" value="<?php  echo $revtapatitulo?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Tipo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="revtapatipocod" id="revtapatipocod">
							<option value="">Seleccione un tipo de tapa</option>
                            <?php  if ($numfilasTipos>0){
                            	while ($rsFilaTipo=$conexion->ObtenerSiguienteRegistro($resultadoTipos))
								{
									$selected="";
									if ($revtapatipocod==$rsFilaTipo["revtapatipocod"])
										$selected=" selected";
									echo '<option value="'.$rsFilaTipo["revtapatipocod"].'" '.$selected.'>'.$rsFilaTipo["revtapatiponombre"].'</option>';
								}
                            }?>
                        </select>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>N&uacute;mero:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
            			<input type="text" value="<?php  echo $revtapanumero?>" id="revtapanumero" name="revtapanumero" maxlength="7" size="10" />
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Fecha:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="revtapafecha" id="revtapafecha"  value="<?php  echo $revtapafecha?>" size="10" maxlength="10">
                        <span class='textonombredatos' style="font-size:8px;">&nbsp;(DD/MM/AAAA) </span>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Link:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="revtapalink"  id="revtapalink" class="full" value="<?php  echo $revtapalink?>" size="90" maxlength="255">
                    </div> 
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>          
                        <label>Target:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="revtapatarget" id="revtapatarget">
                            <option value="_blank" <?php  if ($revtapatarget=="_blank") echo 'selected="selected"'?> >Abrir en otra ventana</option>
                            <option value="_self" <?php  if ($revtapatarget=="_self") echo 'selected="selected"'?>>Abir en la misma ventana</option>
                        </select>
                    </div>                        
  					<div class="clearboth aire_menor">&nbsp;</div>

                     <label>Descripci&oacute;n:</label>
                        
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <textarea name="revtapadesc" id="revtapadesc" class="textarea full rich-text" rows="13" cols="40"  wrap="hard"><?php  echo $revtapadesc;?></textarea>
                        </div>                    
                        <div class="clearboth aire_menor">&nbsp;</div>
    
                        <div class="menubarra">
                            <ul>
                                <li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                                <li><a class="boton base" href="rev_tapas.php" title="Volver">Volver</a></li>
                            </ul>
                        </div>
                    <input type="hidden" name="revtapacod" id="revtapacod" value="<?php  echo $revtapacod?>" />
                </div>
                </form>
    </div>
 </div>

    <div class="Areas_Imagenes">
        <div>
            (*)Las imágenes se le guardaran o modificaran automáticamente cuando las suba. No precisa oprimir el botón Guardar.
        </div>
    
        <div class="clear aire_vertical">&nbsp;</div>
        <div id="BotonMultimediaDiv" >
            <div id="mul_multimedia_bt_subir_img" style="width:150px; float:left"></div> 
            <input type="hidden" name="mul_multimedia_size_inferior" id="mul_multimedia_size_inferior"  value="" />
            <input type="hidden" name="mul_multimedia_name_inferior" id="mul_multimedia_name_inferior"  value="" />
            <input type="hidden" name="mul_multimedia_file_inferior" id="mul_multimedia_file_inferior"  value="" />
            <input type="hidden" name="revtapacod" id="revtapacod"  value="<?php  echo  $revtapacod?>" />

        </div>
        <div id="mul_multimedia_previsualizar" >
             <?php  if ($esmodif && $revtapaarchubic!=""){ ?>

            <div class="imagen" >
                <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA."/tapas/N/".$revtapaarchubic?>" title="Imagen <?php  echo $revtapaarchnombre?> " />
            </div>
          <?php  } ?>
        </div>
          <?php   $oculto2='style="display:none"';
            if ($esmodif && $revtapaarchubic!=""){$oculto2='';} ?>
            <div class="menubarra" <?php  echo $oculto2?> id="HtmlBtEliminar1">
                <ul>
                    <li><a class="left boton rojo" name="Eliminar Tapa" value="Eliminar Tapa" href="javascript:void(0)"  onclick="EliminarImagen(<?php  echo $revtapacod ?>);">Eliminar Tapa</a></li>
                </ul>
            </div>                     
           
      </div>
 

<div class="clear aire_vertical">&nbsp;</div>

      
<?php  if ($esmodif){?>
            <div class="menubarra">
                <ul>
                    <li><a class="left boton verde" title="Añadir imagenes de pagina/s" href="javascript:void(0)"  onclick="AltaRevTapaMultimedia(<?php  echo $revtapacod?>)">A&ntilde;adir im&aacute;genes de p&aacute;gina/s</a></li>
                    <li><a class="left boton verde" title="Añadir imagenes de pagina/s" href="javascript:void(0)"  onclick="PublicarFlip(<?php  echo $revtapacod?>)">Publicar Flip</a></li>
                </ul>
            </div>
            <form action="javascript:void(0)" name="formrevtapamultimedia" id="formrevtapamultimedia" method="post">
            	 <input type="hidden" name="revtapacod" id="revtapacod" value="<?php  echo $revtapacod?>" />
            </form>
            <div id="Popup"></div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div style="font-size:10px;"><b>Recuerde:</b>Si elimina una im&aacute;gen se republica autom&aacute;ticamente.</div>
            <div id="LstRevTapaMultimedia" style="width:100%;" class="clearfix">
                <table id="ListarRevTapaMultimedia" style="width:100%"></table>
            </div>                 
            <div class="clearboth brisa_vertical">&nbsp;</div>      
        </div>
</div> 

<?php  }?>
 <div class="clear fixalto">&nbsp;</div>

<?php  
 $oEncabezados->PieMenuEmergente();
?>
