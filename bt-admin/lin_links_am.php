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

$oLinks = new cLinks($conexion,"");
$volver=$_SESSION['volver'];
?>

<script type="text/javascript" src="modulos/lin_links/js/lin_links.js"></script>
<?php 

if (!isset($_GET['catcod']) && !isset($_GET['linkcod']))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}


if(isset($_GET['catcod']) && $_GET['catcod']!="")
{ 
	$catcod=$_GET['catcod'];
	$linkcod="";
	$linktitulo="";
	$linkdesclarga="";
	$linklink="";
	$linktarget="";
	$linkarchubic="";
	$linkarchnombre="";
	$linkarchsize="";
	$boton = "Agregar";
	$funcionJs="return InsertarLink()";
	
}

if ( isset($_GET['linkcod']) && $_GET['linkcod']!="" )
{
	$esmodif = true;
	if (!$oLinks->BuscarxLinkCod($_GET,$resultado,$numfilas))
		return false;
		
	if ($numfilas==0)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Link inexistente .",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datoslink = $conexion->ObtenerSiguienteRegistro($resultado);

	$linkcod=$_GET['linkcod'];
	$catcod=$datoslink['catcod'];
	$linktitulo=$datoslink['linktitulo'];
	$linkdesclarga=$datoslink['linkdesclarga'];
	$linklink=$datoslink['linklink'];
	$linktarget=$datoslink['linktarget'];
	$linkarchubic=$datoslink['linkarchubic'];
	$linkarchnombre=$datoslink['linkarchnombre'];
	$linkarchsize=$datoslink['linkarchsize'];
	$linkestado=$datoslink['linkestado'];
	$linkorden=$datoslink['linkorden'];	
	$funcionJs="return Modificarlink()";
	$boton = "Modificar";
	
	$volver=$_SESSION['volver']="lin_links.php?catcod=".$catcod;
}

$datos['catcod']= $catcod;
if(!$oLinks->BuscarCategoriaxCatcod($datos,$resultado,$numfilas))
	return false;
if($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
$datoscategoria = $conexion->ObtenerSiguienteRegistro($resultado);


?>
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Links de la categoria <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscategoria['catnom'],ENT_QUOTES)?></h2>
</div>
    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="lin_links_am.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Titulo</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="linktitulo"  id="linktitulo" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($linktitulo,ENT_QUOTES);?>" size="40" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Descripci&oacute;n</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <textarea  rows="3" cols="34" name="linkdesclarga"  id="linkdesclarga" maxlength="255"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($linkdesclarga,ENT_QUOTES)?></textarea>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Link</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="linklink"  id="linklink" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($linklink,ENT_QUOTES)?>" size="40" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Target</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="linktarget" id="linktarget">
                            <option value="_blank" <?php  if ($linktarget=="_blank") echo 'selected="selected"'?> >Abrir en otra ventana</option>
                            <option value="_self" <?php  if ($linktarget=="_self") echo 'selected="selected"'?>>Abir en la misma ventana</option>
                        </select>
                    </div>   
                   
                    <div class="clearboth aire_menor">&nbsp;</div>
                 
                 <!--   <div>
                        <label>Nombre de la foto</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="linkarchnombre"  id="linkarchnombre" size="40" value="<?php  echo $linkarchnombre?>"  maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                   
                 <div>
                        <label>Seleccione archivo</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                   <div>
                       <input type="file" name="foto" id="foto" class="textoinput" maxlength="10" size="40" />
					</div>
                    <div class="clear aire_vertical">&nbsp;</div>-->
                    <div class="menubarra">
                      	<ul>
                            <li><a class="left" href="javascript:void(0)" onclick="<?php  echo $funcionJs?>"> <?php  echo $boton?></a></li>
                  		    <li><a class="left" href="<?php  echo $volver?>">Volver sin guardar</a></li>
                            <?php  if($equipoimagen!=""){?>
                                &nbsp;&nbsp;&nbsp;
                                <input type="submit" name="botoneliminar" class="input-submit" value="Elininar imagen" /> 
                            <?php  } ?>
							</ul>
                           <div id="btn_subirImgMostrar"></div> 
              				 <div id="visualizarbanner">
                        
                    </div>
                </div>
                <input type="hidden" name="foto" id="foto" value=""/>
                <input type="hidden" name="linkarchnombre" id="linkarchnombre" value="" />
             	<input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod?>" />
                <input type="hidden" name="linkcod" id="linkcod" value="<?php  echo $linkcod?>" />
				</div>
            </form>
        </div>
    </div>
<?php  
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>