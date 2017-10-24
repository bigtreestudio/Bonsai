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

$oBanner = new cBanners($conexion);
$oBannerTipo = new cBannersTipos($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$act_des = "";	
$bannercod = "";
$bannerestado = "";	
$bannerdesc = "";
$bannerdesclarga = "";
$bannertipocod="";
$md5_upd = "";
$bannerurl = "";
$bannerorden = "";
$bannertarget = "";
$volver = "";
$bannertipodesc="";
$accion = 1;
$edit = false;
$funcionJs="return InsertarBanner()";
$boton = "botonalta";
$botontexto = "Alta de Banner";
$esbaja  = false;

if (isset($_GET['bannercod']) && $_GET['bannercod']!="")
{
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("bannercod"=>$_GET['bannercod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	
	$bannercod = $_GET['bannercod'];
	if (!$oBanner->BuscarBannerxCodigo($_GET,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el banner por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosbanner = $conexion->ObtenerSiguienteRegistro($resultado);	


	$funcionJs="return ActualizarBanner()";
	$edit = true;
	$boton = "botonmodif";
	$accion = 2;
	$botontexto = "Actualizar Banner";

	$bannertipocod = $datosbanner['bannertipocod']; 
	$bannerestado = $datosbanner['bannerestado'];
	$bannerdesc = $datosbanner['bannerdesc'];
	$bannerdesclarga = $datosbanner['bannerdesclarga'];
	$bannerurl = $datosbanner['bannerurl'];
	$bannertipodesc = $datosbanner['bannerTipoDescripcion'];	
	$bannerarchubic = $datosbanner['bannerarchubic'];	
	$bannerarchnombre = $datosbanner['bannerarchnombre'];	
	$bannerarchsize = $datosbanner['bannerarchsize'];	
	$alto = $datosbanner['banneralto'];	
	$ancho = $datosbanner['bannerancho'];	
	$bannertarget = $datosbanner['bannertarget'];	
	
	$mostrarbanner = false;
	if ($bannerarchubic!="")
		$mostrarbanner = true;
}

?>
<link href="modulos/ban_banners/css/banners_am.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/ban_banners/js/ban_banners_am.js"></script>

<div id="contentedor_modulo">
	<div id="contenedor_interno">
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2 id="titulo">Banner</h2>
</div> 
<div class="form"> 
<form action="ban_banners_upd.php" method="post" name="formulario" id="formulario">
    <input type="hidden" name="bannercod" id="bannercod" value="<?php  echo $bannercod;?>" />
    <input type="hidden" name="md5" id="md5" value="<?php  echo $md5_upd;?>" />
    <input type="hidden" name="accion" id="accion" value="<?php  echo $accion?>">
	<div class="ancho_10">
         <div class="ancho_5">
            <div class="datosgenerales">
                <div>
                    <label>Tipo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <?php  
					if (!$edit)
					{
						$oBanners = new cBanners($conexion);
						$oBanners->BusquedaBannerTipoSP($spnombre,$sparam);
						FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","bannertipocod","bannertipocod","bannerTipoDescripcion",$bannertipocod,"Todos",$regactual,$seleccionado,1,"","width: 200px",false,false);
					}else
					{?>
						<b style="font-size:14px; margin-left:10px;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($bannertipodesc,ENT_QUOTES);?></b>
					<?php  }
                    ?>          
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>

                <div>
                    <label>Titulo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="bannerdesc" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="bannerdesc" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($bannerdesc,ENT_QUOTES);?>" />
                    <div id="bannerdescCharCount" class="charCount">
                        Cantidad de caracteres:
                        <span class="counter">0</span>
                    </div>
                </div>

                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>URL:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                        <input type="text" name="bannerurl" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="bannerurl" class="full" maxlength="255" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($bannerurl,ENT_QUOTES);?>" />
                </div>                        

                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Target:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="bannertarget" id="bannertarget">
                            <option value="_blank" <?php  if ($bannertarget=="_blank") echo 'selected="selected"'?> >Abrir en otra ventana</option>
                            <option value="_self" <?php  if ($bannertarget=="_self") echo 'selected="selected"'?>>Abir en la misma ventana</option>
                        </select>
                    </div>                        
          		</div>
				<div class="ancho_5">	
                    <div>          
                        <label>Estado:</label>
                    </div>
                    
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="bannerestado" id="bannerestado" style="width:200px;">
                            <option <?php  if ($bannerestado==PUBLICADO || $bannerestado==""  ) echo 'selected="selected"'?> value="<?php  echo PUBLICADO?>">PUBLICADA</option>
                            <option <?php  if ($bannerestado==NOPUBLICADO) echo 'selected="selected"'?> value="<?php  echo NOPUBLICADO?>">NO PUBLICADA</option>
                        </select>
                    </div>
                </div>         
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Descripci&oacute;n Larga</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <textarea name="bannerdesclarga" id="bannerdesclarga" class="textarea full rich-text" rows="15" cols="40" wrap="hard"><?php  echo $bannerdesclarga;?></textarea>
                    <div class="wordCountclass">
                        <div id="bannerdesclargaWordCount" class="wordCount"></div>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
           </div>
        </div>

   		<div class="ancho_05">&nbsp;</div>
        <div class="ancho_5">
                <div id="btn_subirImgMostrar"></div> 
                <div id="visualizarbanner">
				<?php  if ($edit && $mostrarbanner){ 
                    echo $oBanner->MostrarBanner($datosbanner);
                }?>        
                </div>
                <div style="clear:both">&nbsp;</div>
        </div>												    
        <div style="clear:both">&nbsp;</div>
    </div>
    <div class="clear aire_vertical">&nbsp;</div>
    <div class="msgaccionbanner">&nbsp;</div> 
    <div class="ancho_10">
     <div class="menubarra">
         <ul>
                <li><a class="boton verde" href="javascript:void(0)" onclick="<?php  echo $funcionJs?>"><?php  echo $botontexto?></a></li>
                <li><a class="boton base" href="ban_banners.php">Volver sin guardar</a></li>
                <?php  if ($edit) {?>
                	<li><a class="boton rojo" href="javascript:void(0)" onclick="Eliminar(<?php  echo $bannercod;?>)">Eliminar</a></li>
                <?php  }?>
        </ul>
     </div>
  </div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>