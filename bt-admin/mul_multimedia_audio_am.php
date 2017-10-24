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

$oMultimedia = new cMultimedia($conexion,"");


$esmodif=false;
$numfilas="";
$catcod  ="";
if(isset($_POST['modif']) && $_POST['modif'])
	$esmodif=true;
	
if($esmodif){
	if(!$oMultimedia->BuscarMultimediaxCodigo($_POST,$resultado,$numfilas))
		die();
	if ($numfilas!=1)
		die();	
	$datos = $conexion->ObtenerSiguienteRegistro($resultado);
	$catcod = $datos['catcod'];			
}

$oMultimediaCategorias=new cMultimediaCategorias($conexion);
$catsuperior="";
if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
	$mostrar=false;


function CargarCategorias($catcod,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"';?>  value="<?php  echo $fila['catcod']?>"><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php  
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($catcod,$fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}
?>
<link href="modulos/mul_multimedia/css/popup.css?v=1.1" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia_modulo/js/mul_multimedia_modulo.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia_modulo/js/mul_multimedia_modulo_audios.js"></script>

<div>
     <?php  if (!$esmodif){?>
	 <script type="text/javascript" src="modulos/mul_multimedia/js/mul_multimedia_audios.js"></script>

    <div id="mul_multimedia_audios">
        <ul>
            <li><a href="#mul_multimedia_pestanis_audios_subir">Subir Audio Nuevo</a></li>
        </ul>
        <div id="mul_multimedia_pestanis_audios_subir">
        	<div class="form">
                <form action="noticias_lst.php" method="post" name="multimediaformulario" id="multimediaformulario">
             
                    <div class="ancho_10">
                        <div class="ancho_2"> 
                            <input type="radio" name="tipovideosubir" style="width:20px; float:left"  value="1" checked="checked" id="tipovideosubir1" onclick="MostrarVentanasAudios(1)">
                            <label for="tipovideosubir1">Audio</label>
                        </div>
                        <div class="ancho_2"> 
                            <input type="radio" name="tipovideosubir" style="width:20px; float:left"   value="2" id="tipovideosubir2" onclick="MostrarVentanasAudios(2)">
                            <label for="tipovideosubir2">Audio Externo</label>
                        </div>
                        <div class="clear aire_vertical">&nbsp;</div>            
                    </div>
                    <div id="VentanaAudio1" class="audiosVentanas">
                        <div class="ancho_10">
                            <div id="mul_multimedia_bt_subir_audio"></div> 
                            <input type="hidden" name="mul_multimedia_size" id="mul_multimedia_size"  value="" />
                            <input type="hidden" name="mul_multimedia_name" id="mul_multimedia_name"  value="" />
                            <input type="hidden" name="mul_multimedia_file" id="mul_multimedia_file"  value="" />
                            <div class="clear fixalto">&nbsp;</div>
                        </div>
                        <div class="clear fixalto">&nbsp;</div>
                    </div>
                    <div id="VentanaAudio2" class="audiosVentanas" style="display:none;">
                        <div class="ancho_10">
                            <div class="ancho_2"> 
                                <input type="radio" style="width:20px; float:left" name="cajasonidoexterno" checked="checked" value="GOEA" id="cajasonidoexterno">
                                <label for="cajasonidoexterno">Goear</label>
                            </div>
                            <div class="clear aire_vertical">&nbsp;</div>            
        
                            <div class="ancho_2">
                                <label>C&oacute;digo GOEAR</label>
                            </div>
                            <div class="ancho_3">
                                <input type="text"  name="multcodgoearaudio" id="multcodgoearaudio" class="full" value="" />
                            </div>
                            <div class="clear fixalto">&nbsp;</div>
                            <div class="ancho_2">
                                &nbsp;
                            </div>
                            <div class="ancho_8" style="font-size:10px;">
                                Ej: http://www.goear.com/listen/<b>e8b59ac</b>/gm-en-lu6-radio-atlantica-la-manana
                            </div>
                            <div class="clear aire_vertical">&nbsp;</div>
                            <div class="ancho_1">&nbsp;</div>
                            <div class="ancho_3">
                                <div class="menubarra">
                                    <ul>
                                        <li><a class="left boton azul" href="javascript:void(0)" onclick="BuscarAudioExterno()">Buscar Audio</a></li>
                                    </ul>
                                </div>        
                            </div>
                        </div>
                        <div class="clear fixalto">&nbsp;</div>
                    </div>

                    <div class="clear fixalto">&nbsp;</div>
                    <div class="ancho_4">
                    	<div id="mul_multimedia_previsualizar"></div>
                    </div>
                    <div class="ancho_6" id="mul_multimedia_descripcion" style="display:none">
						<?php 
                            $oMultimediaCategorias=new cMultimediaCategorias($conexion);
                            $catsuperior="";
                            if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
                                $mostrar=false;
                            
                            $catcod = "";
                        ?>               
                       <div class="ancho_3" style="padding-top:5px;">
                            <label>Categoria: </label>
                       </div>
                       <div class="ancho_7">
                        <select name="catcod" id="catcod" style=" width:100%;">
                            <option value="">Seleccione una Categor&iacute;a...</option>
                        <?php 
                            foreach($arbol as $fila)
                            {
                                ?>
                                <option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php  echo $fila['catcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                                <?php  
                                if (isset($fila['subarbol']))
                                {
                                    $nivel = "---";
                                    CargarCategorias($catcod,$fila['subarbol'],$nivel);
                                }
                            }
                            ?>
                         </select>                   			
                        </div>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                       <div class="ancho_1" style="padding-top:10px;">
                            <label>T&iacute;tulo: </label>
                       </div>                        
                       <input type="text" name="multimedia_titulo" style="width:100%" id="multimedia_titulo" maxlength="255" class="full"></textarea>
                       <div class="ancho_1" style="padding-top:5px;">
                            <label>Descripci&oacute;n </label>
                       </div>
                    	<textarea name="multimedia_desc" style="width:100%" id="multimedia_desc" cols="20" rows="4"></textarea>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                        <div class="menubarra">
                            <ul>
                                <li><a class="left boton verde" href="javascript:void(0)" onclick="GuardarAudio()">Guardar Audio</a></li>
                                <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogCloseAlta()">Cerrar Ventana</a></li>
                            </ul>
                        </div>        
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                </form>        
            </div>
        </div>
        <?php  }else{ ?>
        
        		<div class="form">
               	<form action="noticias_lst.php" method="post" name="form_mul_multimedia" id="form_mul_multimedia">
                    <div class="ancho_3">
                    	<div style="font-weight:bold">Tema:</div> 
                        <div class="descripcion" style=" margin:5px 10px 20px 30px; " >
							<?php 
							 echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimedianombre'],ENT_QUOTES);?>
						</div> 

                        <img src="<?php  echo $oMultimedia->DevolverDireccionThumbImgAudio();?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimedianombre'],ENT_QUOTES);?>" />
                    </div>
                    <div class="ancho_7" id="mul_multimedia_descripcion">
						<?php 
                            $oMultimediaCategorias=new cMultimediaCategorias($conexion);
                            $catsuperior="";
                            if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
                                $mostrar=false;
                        ?>               
                       <div class="ancho_3" style="padding-top:5px;">
                            <label>Categoria: </label>
                       </div>
                       <div class="ancho_1">&nbsp;</div>
                       <div class="ancho_6">
                        <select name="catcod" id="catcod" style=" width:100%;">
                            <option value="">Seleccione una Categor&iacute;a...</option>
                        <?php 
                            foreach($arbol as $fila)
                            {
                                ?>
                                <option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php  echo $fila['catcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                                <?php  
                                if (isset($fila['subarbol']))
                                {
                                    $nivel = "---";
                                    CargarCategorias($catcod,$fila['subarbol'],$nivel);
                                }
                            }
                            ?>
                         </select>                   			
                        </div>
                       <div class="ancho_1" style="padding-top:10px;">
                            <label>T&iacute;tulo: </label>
                       </div>                        
	                    <div class="clear brisa_vertical">&nbsp;</div>
                    	<input type="text" name="multimedia_titulo" style="width:100%" id="multimedia_titulo" maxlength="255" class="full"  value="<?php  echo   FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimediatitulo'],ENT_QUOTES);?>" />
                       <div class="ancho_1" style="padding-top:5px;">
                            <label>Descripci&oacute;n </label>
                       </div>
                    	<textarea name="multimedia_desc" style="width:100%" id="multimedia_desc" cols="20" rows="4"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimediadesc'],ENT_QUOTES);?></textarea>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                        <div class="menubarra">
                            <ul>
                                <li><a class="left boton verde" href="javascript:void(0)" onclick="ModificarDescripcionMultimediaUnicoCompleto(<?php  echo $datos['multimediacod']?>)">Guardar cambios</a></li>
                                <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Volver sin guardar</a></li>
                            </ul>
                        </div>        
                    </div>
                    
                </form>        

                 </div>

				<?php 
			}
			?>
                
   </div>     
</div>