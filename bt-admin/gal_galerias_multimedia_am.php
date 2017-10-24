<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);
$oGaleriasMultimedia = new cGaleriasMultimedia($conexion,"");

$oGalerias = new cGalerias($conexion);


if(!$oGalerias->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Galeria inexistente",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}

$datosgaleria = $conexion->ObtenerSiguienteRegistro($resultado);
$galeriacod = $datosgaleria['galeriacod'];
$galeriatitulo =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria['galeriatitulo'],ENT_QUOTES);
$galeriadesc =  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria['galeriadesc'],ENT_QUOTES);
$estado = $datosgaleria['galeriaestadocod'];
$multimediaconjuntocod = $datosgaleria['multimediaconjuntocod'];
$dominiogaleria = FuncionesPHPLocal::EscapearCaracteres($datosgaleria['galeriatitulo']);
$dominiogaleria=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominiogaleria));
$dominiogaleria="/galeria/".str_replace(' ', '-', trim($dominiogaleria))."_g".$galeriacod;


?>
<link href="modulos/gal_galerias/css/gal_galerias_multimedia.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript">var sizeLimitFile = <?php  echo TAMANIOARCHIVOS;?>;</script>
<script type="text/javascript">var sizeLimitFileAudio = <?php  echo TAMANIOARCHIVOSAUDIO;?>;</script>
<script type="text/javascript" src="modulos/gal_galerias/js/gal_galeria_multimedia.js"></script>
<script type="text/javascript" src="modulos/gal_galerias/js/gal_galeria_multimedia_am.js"></script>
<script type="text/javascript" src="js/touch-punch.min.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <div class="ancho_7">
    <h2>Galeria de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosgaleria['multimediaconjuntodesc'],ENT_QUOTES)?>: <?php  echo  $galeriatitulo ?>
    <span style="font-size:13px;">Url de la Galer&iacute;a: (<?php  echo  $dominiogaleria ?>)</span></h2>
    </div>
    <div class="ancho_3">
    	<div class="menubarra">
            <ul>
                <li><a class="boton verde" href="gal_galerias_salto.php?galeriacod=<?php  echo $galeriacod?>" target="_blank">Previsualizar</a></li>
                <li><a class="left boton base" href="gal_galerias.php">Volver al listado</a></li>
            </ul>
        </div>
    </div>
    <div class="clear fixalto">&nbsp;</div>
</div>
<div class="clear fixalto">&nbsp;</div>


<div class="datosnoticia">
    <div class="cajamultimediacolizq">
        <form action="javascript:void()" method="post" name="formulariogaleria" id="formulariogaleria">
            <input type="hidden" name="galeriacod" id="galeriacod" value="<?php  echo $galeriacod?>" />
            <input type="hidden" name="multimediaconjuntocod" id="multimediaconjuntocod" value="<?php  echo $multimediaconjuntocod?>" />
            <div class="clear fixalto">&nbsp;</div>
        <?php 
            switch($multimediaconjuntocod)
            {
                case FOTOS:
                    ?>
                        <div style="float:right;">
                            <div id="mul_multimedia_bt_subir_img">
                            
                            </div>
                        </div>
                        <div class="clear fixalto">&nbsp;</div>
                    <?php 	
                    break;
                                    
                
                case VIDEOS:
                    ?>
                           
                            <div class="ancho_10">
                                
                                <div class="ancho_3"> 
                                	<span class="icon-youtube"></span>
                                    <input type="radio" name="cajavideosexterno" checked="checked" value="YOU" id="cajavideosexternoyou" onclick="MostrarCaja('cajayoutube')">
                                    <label for="cajavideosexternoyou">YouTube</label>
                                </div>
                                <div class="ancho_3">
                                	<span class="icon-vimeo"></span>
                                    <input type="radio" name="cajavideosexterno" value="VIM" id="cajavideosexternovim" onclick="MostrarCaja('cajavimeo')">
                                    <label for="cajavideosexternovim">Vimeo</label>
                                </div>
                                <div class="ancho_3">
                                	<span class="icon-videopropietario"></span>
                                    <input type="radio" name="cajavideosexterno" value="PROP" id="cajavideopropietario" onclick="MostrarCaja('cajapropietario')">
                                    <label for="cajavideopropietario">Propietario</label>
                                </div>
                                <div class="clearboth"></div>
                                <div class="boxsubida clearfix">
                                
                                    <div id="cajayoutube" class="boxsubidaData" style="display:block; margin-top:10px" > 
                                        <div class="ancho_10">
                                            <label>C&oacute;digo youtube</label>
                                        </div>
                                        <div class="ancho_4">
                                            <input type="text" value="" class="full" id="mulcodepagey" name="mulcodepagey" />
                                        </div>
                                        <div class="ancho_1">&nbsp;</div>
                                        <div class="ancho_3">
                                            <div class="menubarra">
                                                <ul>
                                                     <li><a class="left boton azul" href="javascript:void(0)" onclick="BuscarVideoExterno()">Buscar Video</a></li>
                                               </ul>
                                            </div>        
                                        </div>
                                        <div class="clear fixalto">&nbsp;</div>
                
                                        <div class="ancho_10" style="font-size:10px;">
                                            Ej: http://youtu.be/<b>Q3k_xdvHv3k</b>
                                        </div>
                
                                        <div class="clear fixalto">&nbsp;</div>
            
                                        <div class="ancho_10" style="font-size:10px;">
                                            Ej: http://www.youtube.com/watch?v=<b>Q3k_xdvHv3k</b>
                                        </div>
                                        <div class="clear fixalto">&nbsp;</div>
                                    </div>
                                    
        
        
                                    <div id="cajavimeo" class="boxsubidaData" style="display:none;">
                                        <div class="ancho_10">
                                            <label>C&oacute;digo Vimeo</label>
                                        </div>
                                        <div class="ancho_4">
                                            <input type="text" value="" class="full" id="mulcodepage" name="mulcodepage" />
                                        </div>
                                        <div class="ancho_1">&nbsp;</div>
                                        <div class="ancho_3">
                                            <div class="menubarra">
                                                <ul>
                                                    <li><a class="boton azul" href="javascript:void(0)" onclick="BuscarVideoExterno()">Buscar Video</a></li>
                                                </ul>
                                            </div>        
                                        </div>
                                        <div class="clear fixalto">&nbsp;</div>
            
                                        <div class="ancho_10" style="font-size:10px;">
                                            Ej: http://player.vimeo.com/video/<b>5367093</b>
                                        </div>
                                        <div class="clear fixalto">&nbsp;</div>
                                    </div>


                                    <div class="clear fixalto">&nbsp;</div>
                                    <div class="boxsubidaData" id="cajapropietario" style="display:none;">
                                        <div>
                                            <div class="ancho_10">
                                                <div id="mul_multimedia_bt_subir_video_propietario"></div> 
                                                <input type="hidden" name="mul_multimedia_size" id="mul_multimedia_size"  value="" />
                                                <input type="hidden" name="mul_multimedia_name" id="mul_multimedia_name"  value="" />
                                                <input type="hidden" name="mul_multimedia_file" id="mul_multimedia_file"  value="" />
                                                <div class="clear fixalto">&nbsp;</div>
                                            </div>
                                            
                                            <div class="clear fixalto">&nbsp;</div>
                                            <div class="ancho_10" id="mul_multimedia_subir_prop" style="display:none; padding-top:10px">
                                                <div id="PreviewVideoPropietario"></div>
                                                <div class="clear fixalto">&nbsp;</div>
                                                <div class="menubarra">
                                                    <ul>
                                                        <li><a class="boton verde" href="javascript:void(0)" onclick="GuardarVideoPropietario()">Guardar Video</a></li>
                                                        <li><a class="left boton base" href="javascript:void(0)"  onclick="CancelarVideoPropietario()">Cancelar</a></li>
                                                    </ul>
                                                </div>  
                                            </div>     
                                            
                                        </div>
                                    </div>

                                        
                                </div>
                                <div class="clear fixalto">&nbsp;</div>
                                <div class="ancho_10">
                                    <div id="mul_multimedia_previsualizar_am"></div>
                                </div>
                                <div class="clear fixalto">&nbsp;</div>
                            </div>
                            <div class="clear fixalto">&nbsp;</div>
                            <div class="ancho_8" id="mul_multimedia_subir_am" style="display:none; padding-top:10px">
                                <div class="menubarra">
                                    <ul>
                                        <li><a class="boton verde" href="javascript:void(0)" onclick="GuardarVideo()">Guardar Video</a></li>
                                        <li><a class="left boton base" href="javascript:void(0)" onclick="CancelarVideo()">Cancelar</a></li>
                                    </ul>
                                </div>        
                            </div>
                            <div class="clear fixalto">&nbsp;</div>
                       
                    <?php 	
                    break;
                                    
                
                case AUDIOS:
                    ?>
                        <div style=" float:right; margin-right:5px">
                            <div id="mul_multimedia_bt_subir_audio">
                            
                            </div>
                        </div>
                <?php 	
                    break;
                                    
                
            }
        
        ?>
        &nbsp;
	</form>
	</div>
    <div class="cajamultimediacolder">
        <div class="multimedia">
        	<div class="menuizquierda">
                <div class="menubarra">
                    <ul>
                        <li><input type="checkbox" onclick="CheckearTodos()" value="todos" id="todos" style="margin-right:3px" /><label for="todos" class="todos" style="margin-right:15px">Todos</label></li>
                        <li><a class="boton rojo" href="javascript:void(0)" onclick="return EliminarCheckeados()">Eliminar Seleccionados</a></li>
                    </ul>
                </div>
            </div>
            <div class="menuderecha">
                 <div class="menubarra">
                    <ul>
                        <?php  
							switch($multimediaconjuntocod)
							{
							   case FOTOS:
								 ?>
									<li><a class="left boton azul" href="javascript:void(0)" onclick="return AbrirPopupNuevaFoto()">Buscar Imagen existente</a></li>
								 <?php  
								 break;
							   case VIDEOS:
								 ?>
									<li><a class="left boton azul" href="javascript:void(0)" onclick="return AbrirPopupNuevoVideo()">Buscar Video existente</a></li>
								 <?php  
								 break;
							   case AUDIOS:
								 ?>
									<li> <a class="left boton azul" href="javascript:void(0)" onclick="return AbrirPopupNuevoAudio()">Buscar Audio existente</a></li>
								 <?php  
								 break;
							}
						?>
                    </ul>
                </div>
           </div>
            <div class="clear fixalto">&nbsp;</div>
            <div id="ListadoMultimedia">
            
            </div>
     </div>       
</div>
<div id="PopupMultimedia"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div style="height:50px;">&nbsp;</div>
<?php  
 $oEncabezados->PieMenuEmergente();
?>