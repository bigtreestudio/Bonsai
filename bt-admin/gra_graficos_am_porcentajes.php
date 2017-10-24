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

$oGraficos = new cGraficos($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$act_des = "";	
$graficocod = "";
$graficoestado = "";	
$graficotipocod = "";
$graficoinvertir = "";
$graficotitulo = "";
$graficodesc = "";
$graficotitulocolumnas = "";
$graficotitulocolumnasalign = "";
$graficotitulofilas = "";
$graficotitulofilasalign = "";
$graficofilaflota = "";
$valorx = 0;
$valory = 0;
$graficoleyendamostrar = "";
$graficoleyendaalinear="";
$graficoleyendaalinearvertical="";
$graficotituloalign="";
$graficodescalign="";
$graficoalto="";
$graficozoom="";

$accion = 1;
$edit = false;
$funcionJs="return InsertarBanner()";
$boton = "botonalta";
$botontexto = "Alta de Banner";
$esbaja  = false;


FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("graficocod"=>$_GET['graficocod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("graficocod"=>$_GET['graficocod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$graficocod = $_GET['graficocod'];
if (!$oGraficos->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el grafico por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datosgrafico = $conexion->ObtenerSiguienteRegistro($resultado);	


if ($datosgrafico['conjuntocod']!=GRAFPORCENTAJES)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, el grafico no corresponde a una edición de 1 eje. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}


$funcionJs="return ActualizarGrafico()";
$edit = true;
$boton = "botonmodif";
$accion = 2;
$botontexto = "Actualizar Datos del Gráfico";

$graficoestado = $datosgrafico['graficoestado'];
$graficotipocod = $datosgrafico['graficotipocod'];
$graficoinvertir = $datosgrafico['graficoinvertir'];
$graficotitulo = $datosgrafico['graficotitulo'];
$graficodesc = $datosgrafico['graficodesc'];
$graficotitulocolumnas = $datosgrafico['graficotitulocolumnas'];
$graficotitulocolumnasalign = $datosgrafico['graficotitulocolumnasalign'];
$graficotitulofilas = $datosgrafico['graficotitulofilas'];
$graficotitulofilasalign = $datosgrafico['graficotitulofilasalign'];
$graficofilaflota = $datosgrafico['graficofilaflota'];
$valorx = $datosgrafico['valorx'];
$valory = $datosgrafico['valory'];
$graficoleyendamostrar = $datosgrafico['graficoleyendamostrar'];
$graficoleyendaalinear = $datosgrafico['graficoleyendaalinear'];
$graficoleyendaalinearvertical = $datosgrafico['graficoleyendaalinearvertical'];
$graficotituloalign = $datosgrafico['graficotituloalign'];
$graficodescalign = $datosgrafico['graficodescalign'];
$graficoalto = $datosgrafico['graficoalto'];
$conjuntocod = $datosgrafico['conjuntocod'];
$graficozoom = $datosgrafico['graficozoom'];
$graficomedida = $datosgrafico['graficomedida'];
$graficoestilo = $datosgrafico['graficoestilo'];

FuncionesPHPLocal::ArmarLinkMD5("gra_graficos_upd.php",array("graficocod"=>$graficocod),$get_upd,$md5_upd);
?>
<link href="modulos/gra_graficos/css/graficos_am.css" rel="stylesheet" title="style" media="all" />
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="modulos/gra_graficos/js/gra_graficos_am_porcentajes.js"></script>
<script type="text/javascript" src="modulos/gra_graficos/js/gra_graficos_previsualizar.js"></script>
<script src="js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>
		


<div id="contentedor_modulo">
	<div id="contenedor_interno">
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2 id="titulo">Gr&aacute;ficos</h2>
</div>  

<div class="form">
<form action="gra_graficos_upd.php" method="post" name="formulariografico" id="formulariografico">
    <input type="hidden" name="graficocod" id="graficocod" value="<?php  echo $graficocod;?>" />
    <input type="hidden" name="md5" id="md5" value="<?php  echo $md5_upd;?>" />
    <input type="hidden" name="accion" id="accion" value="<?php  echo $accion?>">
    <input type="hidden" name="conjuntocod" id="conjuntocod" value="<?php  echo $conjuntocod?>" />
	<div class="ancho_10">
         <div class="ancho_4">
            <div class="datosgenerales">
            
            	<div class="ancho_10">
                    <div class="ancho_4">
                        <div>
                            <label>Tipo:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                             <?php  
							 	$datos['conjuntocod'] = $conjuntocod;
                                $oGraficos->BuscarTiposActivos($datos,$spnombre,$sparam);
                                FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formbusqueda","graficotipocod","graficotipocod","graficotipotitulo",$graficotipocod,"Seleccione un tipo...",$regactual,$seleccionado,1,"",false,false);
                            ?>                     
                        </div>
                    </div>
                    <div class="ancho_6">
                        <div>          
                            <label>Estado:</label>
                        </div>
                        
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <select name="graficoestado" id="graficoestado" style="width:200px;">
                                <option <?php  if ($graficoestado==NOPUBLICADO  || $graficoestado==""  ) echo 'selected="selected"'?> value="<?php  echo NOPUBLICADO?>">NO PUBLICADO</option>
                                <option <?php  if ($graficoestado==PUBLICADO) echo 'selected="selected"'?> value="<?php  echo PUBLICADO?>">PUBLICADO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
               
               
            	<div class="ancho_10">
                    <div class="ancho_3">
                        <div>
                            <label>Alto Gr&aacute;fico (pixeles):</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" name="graficoalto" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="graficoalto" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($graficoalto,ENT_QUOTES);?>" />
                        </div>
					</div>
                    <div class="ancho_1">&nbsp;</div>
                </div>    
                <div class="clearboth aire_menor">&nbsp;</div>
				<div class="ancho_10">
	                <div class="ancho_6">
                        <div>
                            <label>Estilo del Grafico:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <select name="graficoestilo"  id="graficoestilo">
                                <option value="default"  <?php  if ($graficoestilo=="" || $graficoestilo=="default" ) echo 'selected="selected"'?>>Default</option>
                                <option value="dark-blue"  <?php  if ($graficoestilo=="dark-blue"  ) echo 'selected="selected"'?>>Azul Oscuro</option>
                                <option value="dark-green"  <?php  if ($graficoestilo=="dark-green" ) echo 'selected="selected"'?>>Verde Oscuro</option>
                                <option value="gray"  <?php  if ($graficoestilo=="gray" ) echo 'selected="selected"'?>>Gris</option>
                                <option value="grid"  <?php  if ($graficoestilo=="grid" ) echo 'selected="selected"'?>>En Grillas</option>
                            </select>
                        </div>
					</div>
				</div>
                <div class="clearboth aire_menor">&nbsp;</div>

				<div class="ancho_10">
	                <div class="ancho_6">
                        <div>
                            <label>Titulo:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" name="graficotitulo" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="graficotitulo" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($graficotitulo,ENT_QUOTES);?>" />
                        </div>
					</div>
                    <div class="ancho_1">&nbsp;</div>
	                <div class="ancho_2">
                        <div>
                            <label>Alineaci&oacute;n:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <select name="graficotituloalign"  id="graficotituloalign">
                                <option value="left"  <?php  if ($graficotituloalign=="left" ) echo 'selected="selected"'?>>Izquierda</option>
                                <option value="center"  <?php  if ($graficotituloalign=="center"  || $graficotituloalign=="" ) echo 'selected="selected"'?>>Centro</option>
                                <option value="right"  <?php  if ($graficotituloalign=="right" ) echo 'selected="selected"'?>>Derecha</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                
				<div class="ancho_10">
	                <div class="ancho_6">
                        <div>
                            <label>Subitulo:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <input type="text" name="graficodesc" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="graficodesc" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($graficodesc,ENT_QUOTES);?>" />
                        </div>
					</div>
                    <div class="ancho_1">&nbsp;</div>
	                <div class="ancho_2">
                        <div>
                            <label>Alineaci&oacute;n:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <select name="graficodescalign"  id="graficodescalign">
                                <option value="left"  <?php  if ($graficodescalign=="left" ) echo 'selected="selected"'?>>Izquierda</option>
                                <option value="center"  <?php  if ($graficodescalign=="center"  || $graficodescalign=="" ) echo 'selected="selected"'?>>Centro</option>
                                <option value="right"  <?php  if ($graficodescalign=="right" ) echo 'selected="selected"'?>>Derecha</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                
                

                <div class="clearboth aire_menor">&nbsp;</div>

                <input type="hidden" name="graficoinvertir" id="graficoinvertir" value=0"" />
                <input type="hidden" name="graficozoom" id="graficozoom" value="0" />
                <input type="hidden" name="graficotitulocolumnas" id="graficotitulocolumnas"  value="" />
                <input type="hidden" name="graficotitulocolumnasalign" id="graficotitulocolumnasalign" value="" />
                <input type="hidden" name="graficotitulofilas" id="graficotitulofilas"  value="" />
                <input type="hidden" name="graficotitulofilasalign" id="graficotitulofilasalign"  value="" />
                <input type="hidden" name="graficomuestravaloreseje" id="graficomuestravaloreseje"  value="" />
                <input type="hidden" name="graficomuestravaloresseries" id="graficomuestravaloresseries"  value="" />
                <div class="clearboth aire_menor">&nbsp;</div>


                <h3>
                    <label style="font-size:12px; text-decoration:underline">Detalle de las Series</label>
                </h3>
                <div class="clearboth aire_menor">&nbsp;</div>
				<div class="ancho_10">
                	<div class="ancho_05">&nbsp;</div>
	                <div class="ancho_2">
                        <div>
                            <label>Mostrar Detalle:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <select name="graficoleyendamostrar"  id="graficoleyendamostrar">
                                <option value="0"  <?php  if ($graficoleyendamostrar==0 || $graficoleyendamostrar==""  ) echo 'selected="selected"'?>>No</option>
                                <option value="1"  <?php  if ($graficoleyendamostrar==1 ) echo 'selected="selected"'?>>Si</option>
                            </select>
                        </div>
                    </div>
                    <div class="ancho_05">&nbsp;</div>
	                
                    <div class="ancho_2">
                        <div>
                            <label>Flota en Gr&aacute;fico:</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div>
                            <select name="graficofilaflota"  id="graficofilaflota" onchange="VerOpcionesFlotar()">
                                <option value="0"  <?php  if ($graficofilaflota==0 || $graficofilaflota==""  ) echo 'selected="selected"'?>>No</option>
                                <option value="1"  <?php  if ($graficofilaflota==1 ) echo 'selected="selected"'?>>Si</option>
                            </select>
                        </div>
                    </div>
	                <div class="ancho_5" id="ValoresFlotar" <?php  if ($graficofilaflota==0 || $graficofilaflota==""  ) echo 'style="display:none"'?> >
                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_3">
                            <div>
                                <label>X:</label>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div>
                                <input type="text" name="valorx" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="valorx" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($valorx,ENT_QUOTES);?>" />
                            </div>
                        </div>
                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_3">
                            <div>
                                <label>Y:</label>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div>
                                <input type="text" name="valory" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="valory" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($valory,ENT_QUOTES);?>" />
                            </div>
                        </div>
					</div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div class="ancho_10">
                        <div class="ancho_05">&nbsp;</div>
                        <div class="ancho_8">
                            <div>
                                <label>Alinear:</label>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div>
                               <select name="graficoleyendaalinear"  id="graficoleyendaalinear">
                                    <option value="left"  <?php  if ($graficoleyendaalinear=="left" || $graficoleyendaalinear==""  ) echo 'selected="selected"'?>>Izquierda</option>
                                    <option value="center"  <?php  if ($graficoleyendaalinear=="center" ) echo 'selected="selected"'?>>Centro</option>
                                    <option value="right"  <?php  if ($graficoleyendaalinear=="right" ) echo 'selected="selected"'?>>Derecha</option>
                                </select>
                                &nbsp;&nbsp;
                               <select name="graficoleyendaalinearvertical"  id="graficoleyendaalinearvertical">
                                    <option value="bottom"  <?php  if ($graficoleyendaalinearvertical=="bottom" || $graficoleyendaalinearvertical==""  ) echo 'selected="selected"'?>>Abajo</option>
                                    <option value="center"  <?php  if ($graficoleyendaalinearvertical=="center" ) echo 'selected="selected"'?>>Centro</option>
                                    <option value="top"  <?php  if ($graficoleyendaalinearvertical=="top" ) echo 'selected="selected"'?>>Arriba</option>
                                </select>
                            </div>
                        </div>
                    </div>
               </div>       
               <div class="clearboth aire_menor">&nbsp;</div>
           </div>
           <div class="clearboth aire_menor">&nbsp;</div>
            <div class="ancho_10">
                <div class="ancho_8">
                    <div>
                        <label>Medida del valor:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="graficomedida" <?php  if ($esbaja) echo 'disabled="disabled"'?> id="graficomedida" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($graficomedida,ENT_QUOTES);?>" />
                    </div>
                </div>
            </div>
            <div class="clearboth aire_menor">&nbsp;</div>
       </div>
		<div class="ancho_6">
            <div class="clear aire_vertical">&nbsp;</div>
            <div style="font-size:14px; font-weight:bold; margin-bottom:5px; text-align:right">
                <a href="javascript:void(0)" class="boton verde" onclick="AgregarEjeY()">Agregar Series</a>
            </div>
            <div id="LstFilas" style="width:100%;">
                <table id="ListarFilas"></table>
            </div>
            <div class="clear aire_vertical">&nbsp;</div>
			<?php  if ($edit){?>
                <div class="clear aire_vertical">&nbsp;</div>
                <div id="ValoresCampos"></div>
                <div class="clear aire_vertical">&nbsp;</div>
             <?php  }?>   
        </div>
   		<div class="ancho_05">&nbsp;</div>
        <div class="clearboth aire_menor">&nbsp;</div>
        <div class="msgaccionbanner">&nbsp;</div> 
        <div class="ancho_10">
        <div class="menubarra">
             <ul>
                <li><a class="boton verde" href="javascript:void(0)" onclick="<?php  echo $funcionJs?>"><?php  echo $botontexto?></a></li>
                <li><a class="left boton base" href="gra_graficos.php">Volver sin guardar</a></li>
            </ul>
        </div>
        <div class="clearboth aire_menor">&nbsp;</div>
		<?php  if ($edit){?>
            <div class="clear aire_vertical">&nbsp;</div>
            <div id="GraficoPrevisualizar"></div>
            <div id="Popup"></div>
         <?php  }?>   
    </div>
  </div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>