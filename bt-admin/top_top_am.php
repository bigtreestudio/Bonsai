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

$oTop = new cTop($conexion);
$oTopTipo = new cTopTipos($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$act_des = "";	
$topcod = "";
$topestado = "";	
$topdesc = "";
$topdesclarga = "";
$toptipocod="";
$md5_upd = "";
$topurl = "";
$toporden = "";
$toptarget = "";
$volver = "";
$toptipodesc="";
$accion = 1;
$edit = false;
$funcionJs="return Insertartop()";
$boton = "botonalta";
$botontexto = "Alta de top";
$esbaja  = false;

if (isset($_GET['topcod']) && $_GET['topcod']!="")
{
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("topcod"=>$_GET['topcod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	
	$topcod = $_GET['topcod'];
	if (!$oTop->BuscarTopxCodigo($_GET,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el top por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datostop = $conexion->ObtenerSiguienteRegistro($resultado);	


	$funcionJs="return ActualizarTop()";
	$edit = true;
	$boton = "botonmodif";
	$accion = 2;
	$botontexto = "Actualizar Top";

	$toptipocod = $datostop['toptipocod']; 
	$topestado = $datostop['topestado'];
	$topdesc = $datostop['topdesc'];
	$topdesclarga = $datostop['topdesclarga'];
	$topurl = $datostop['topurl'];
	$toptipodesc = $datostop['toptipodesc'];	
	$toparchubic = $datostop['toparchubic'];	
	$toparchnombre = $datostop['toparchnombre'];	
	$toparchsize = $datostop['toparchsize'];	
	$alto = $datostop['topalto'];	
	$ancho = $datostop['topancho'];	
	$toptarget = $datostop['toptarget'];	
	
	$mostrartop = false;
	if ($toparchubic!="")
		$mostrartop = true;
}

?>
<link href="modulos/top_top/css/top_am.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/top_top/js/top_top_am.js"></script>

<div id="contentedor_modulo">
	<div id="contenedor_interno">
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2 id="titulo">Top</h2>
</div>  
<div class="form">
<form action="top_top_upd.php" method="post" name="formulario" id="formulario">
    <input type="hidden" name="topcod" id="topcod" value="<?php  echo $topcod;?>" />
    <input type="hidden" name="md5" id="md5" value="<?php  echo $md5_upd;?>" />
    <input type="hidden" name="accion" id="accion" value="<?php  echo $accion?>">
	<div class="ancho_10">
         <div class="ancho_4">
            <div class="datosgenerales">
                <div>
                    <label>Tipo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <?php  
					if (!$edit)
					{
						$oTop = new cTop($conexion);
						$oTop->BusquedaTopTipoSP($spnombre,$sparam);
						FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","toptipocod","toptipocod","toptipodesc",$toptipocod,"Todos",$regactual,$seleccionado,1,"","width: 200px",false,false);
					}else
					{?>
						<b style="font-size:14px; margin-left:10px;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($toptipodesc,ENT_QUOTES);?></b>
					<?php  }
                    ?>          
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>

                <div>
                    <label>Descripci&oacute;n:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="topdesc" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="topdesc" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($topdesc,ENT_QUOTES);?>" />
                    <div id="topdescCharCount" class="charCount">
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
                        <input type="text" name="topurl" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="topurl" class="full" maxlength="255" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($topurl,ENT_QUOTES);?>" />
                </div>                        

                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Target:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="toptarget" id="toptarget">
                            <option value="_blank" <?php  if ($toptarget=="_blank") echo 'selected="selected"'?> >Abrir en otra ventana</option>
                            <option value="_self" <?php  if ($toptarget=="_self") echo 'selected="selected"'?>>Abir en la misma ventana</option>
                        </select>
                    </div>                        
          		</div>
				<div class="ancho_5">	
                    <div>          
                        <label>Estado:</label>
                    </div>
                    
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="topestado" id="topestado" style="width:200px;">
                            <option <?php  if ($topestado==PUBLICADO || $topestado==""  ) echo 'selected="selected"'?> value="<?php  echo PUBLICADO?>">PUBLICADA</option>
                            <option <?php  if ($topestado==NOPUBLICADO) echo 'selected="selected"'?> value="<?php  echo NOPUBLICADO?>">NO PUBLICADA</option>
                        </select>
                    </div>
                </div>         
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Descripci&oacute;n Larga</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <textarea name="topdesclarga" id="topdesclarga" class="textarea full rich-text" rows="15" cols="40" wrap="hard"><?php  echo $topdesclarga;?></textarea>
                    <div class="wordCountclass">
                        <div id="topdesclargaWordCount" class="wordCount"></div>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
           </div>
        </div>

   		<div class="ancho_05">&nbsp;</div>
        <div class="ancho_5">
                <div id="btn_subirImgMostrar"></div> 
                <div id="visualizartop">
				<?php  if ($edit && $mostrartop){ 
                    echo $oTop->MostrarTop($datostop);
                }?>        
                </div>
                <div style="clear:both">&nbsp;</div>
        </div>												    
        <div style="clear:both">&nbsp;</div>
    </div>
    <div class="clear aire_vertical">&nbsp;</div>
    <div class="msgacciontop">&nbsp;</div> 
    <div class="ancho_10">
     <div class="menubarra">
         <ul>
                <li><a class="boton verde" href="javascript:void(0)" onclick="<?php  echo $funcionJs?>"><?php  echo $botontexto?></a></li>
                <li><a class="left boton base" href="top_top.php">Volver sin guardar</a></li>
                <?php  if ($edit) {?>
                	<li><a class="left boton rojo" href="javascript:void(0)" onclick="Eliminar(<?php  echo $topcod;?>)">Eliminar</a></li>
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