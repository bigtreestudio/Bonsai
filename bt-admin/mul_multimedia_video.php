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
<link href="modulos/mul_multimedia/css/popup.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia/js/mul_multimedia_videos.js"></script>
<div>
    <div id="mul_multimedia_videos">
        <ul>
            <li><a href="#mul_multimedia_pestania_subir">Subir Video Externo</a></li>
            <li><a href="#mul_multimedia_pestania_subir_propietario">Subir Video Propietario</a></li>
            <li><a href="#mul_multimedia_pestania_busqueda">Repositorio de Videos</a></li>
        </ul>
        <div id="mul_multimedia_pestania_subir">
        	<div class="form">
                <form action="mul_multimedia_video.php" method="post" name="multimediaformulario" id="multimediaformulario">
                    <div class="ancho_2"> 
                        <span class="icon-youtube"></span>
                    	<input type="radio" style="width:20px; float:left" name="cajavideosexterno" checked="checked" value="YOU" id="cajavideosexternoyou" onclick="MostrarCajaYouTube()">
                        <label for="cajavideosexternoyou">YouTube</label>
                    </div>
                    <div class="ancho_8">
                        <span class="icon-vimeo"></span>
                    	<input type="radio" style="width:20px; float:left" name="cajavideosexterno" value="VIM" id="cajavideosexternovim" onclick="MostrarCajaVimeo()">
  					    <label for="cajavideosexternovim">Vimeo</label>
                    </div>
					<div class="clear aire_vertical">&nbsp;</div>            
                    <div class="ancho_10">
                    	<div class="ancho_6">
                        	<div id="cajayoutube"  style="display:block; margin-top:10px" >
                        	<div class="ancho_10">
                            	<div class="ancho_3">
                               		<label>C&oacute;digo youtube</label>
                            	</div>
                            	<div class="ancho_7">
                                	<input type="text" value="" class="full" id="mulcodepagey" name="mulcodepagey" />
                            	</div>
                        	</div>
                            <div class="clear fixalto">&nbsp;</div>
                            <div class="ancho_3">
                                &nbsp;
                            </div>
                            <div class="ancho_5" style="font-size:10px;">
                                Ej: http://youtu.be/<b>W3MFwIrfnZc</b>
                            </div>
                            <div class="ancho_5">&nbsp;</div>
                            <div class="clear fixalto">&nbsp;</div>
                            <div class="ancho_3">
                                &nbsp;
                            </div>
                            <div class="ancho_7" style="font-size:10px;">
                                Ej: http://www.youtube.com/watch?v=<b>W3MFwIrfnZc</b>&feature=context-vrec
                            </div>
                            <div class="clear fixalto">&nbsp;</div>                            
                            <div class="clear fixalto">&nbsp;</div>
	                    </div>
                        <div id="cajavimeo" style="display:none;margin-top:10px">
                            <div class="ancho_3">
                                <label>C&oacute;digo vimeo</label>
                            </div>
                            <div class="ancho_7">
                                <input type="text" value="" class="full" id="mulcodepage" name="mulcodepage" />
                            </div>
                           <div class="ancho_3">
                                &nbsp;
                            </div>
                            <div class="ancho_6" style="font-size:10px;">
                                Ej: http://player.vimeo.com/video/<b>5367093</b>
                            </div>
                            <div class="ancho_5">&nbsp;</div>
                            <div class="clear fixalto">&nbsp;</div> 
                         </div>
                        </div>

                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_3">
                            <div class="menubarra">
                                <ul>
                                    <li><a class="left boton azul" href="javascript:void(0)" onclick="BuscarVideoExterno()">Buscar Video</a></li>
                                </ul>
                            </div>        
                        </div>
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
                       <div class="ancho_2" style="padding-top:5px;">
                            <label>Categoria: </label>
                       </div>
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
	                    <div class="clear brisa_vertical">&nbsp;</div>
                        <input type="text" name="multimedia_titulo" style="width:100%" id="multimedia_titulo" maxlength="255" class="full"></textarea>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                        <textarea name="multimedia_desc" style="width:100%" id="multimedia_desc" cols="20" rows="4"></textarea>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                        <div class="menubarra">
                            <ul>
                                <li><a class="left boton verde" href="javascript:void(0)" onclick="GuardarVideo()">Guardar Video</a></li>
                                <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                            </ul>
                        </div>        
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                    <input type="hidden" name="tipovideosubir" value="1" id="tipovideosubir_1" />
                </form>        
            </div>
        </div>
        
        <div id="mul_multimedia_pestania_subir_propietario">
            <form action="noticias_lst.php" method="post"name="multimediaformvidpropietario" id="multimediaformvidpropietario">
                <div>
                    <div class="ancho_10">
                        <div id="mul_multimedia_bt_subir_video"></div> 
                        <input type="hidden" name="mul_multimedia_size" id="mul_multimedia_size"  value="" />
                        <input type="hidden" name="mul_multimedia_name" id="mul_multimedia_name"  value="" />
                        <input type="hidden" name="mul_multimedia_file" id="mul_multimedia_file"  value="" />
                        <div class="clear fixalto">&nbsp;</div>
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                </div>
                <div class="ancho_4">
                    <div id="mul_multimedia_previsualizar_video"></div>
                </div>
                <div class="ancho_6" id="mul_multimedia_descripcion_video" style="display:none">
                   <div class="ancho_1" style="padding-top:5px;">
                        <label>Categoria: </label>
                   </div>
                   <div class="ancho_8">
                    <select name="catcodvideoprop" id="catcodvideoprop" style=" width:100%;">
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
                    <div class="ancho_1">
                        <label>Titulo</label>
                    </div>
                    <div class="ancho_1">&nbsp;</div>
                    <div class="ancho_8">
                        <input type="text" name="multimedia_titulo_prop" style="width:100%" id="multimedia_titulo_prop" maxlength="255" class="full"></textarea>
                    </div>
                    <div class="clear brisa_vertical">&nbsp;</div>
                    <div class="ancho_1">
                        <label>Desc</label>
                    </div>
                    <div class="ancho_1">&nbsp;</div>
                    <div class="ancho_8">
                        <textarea name="multimedia_desc_prop" style="width:100%" id="multimedia_desc_prop" cols="20" rows="4"></textarea>
                    </div>
                    <div class="clear brisa_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left boton verde" href="javascript:void(0)" onclick="GuardarVideoPropietario()">Guardar Video</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>        
                </div>
                <div class="clear fixalto">&nbsp;</div>
                <input type="hidden" name="tipovideosubir" value="2" id="tipovideosubir_2" />
            </form>        
        </div>
        
        <div id="mul_multimedia_pestania_busqueda">
        	<div id="TableMultimediawidth" style="width:700px;">
            	<div class="form">
                <form action="javascript:void(0)" method="post" name="formbusquedamultimedia" id="formbusquedamultimedia">
                	<div class="ancho_10">
                        <div class="ancho_2" style="margin-top:10px">
                            <label>Buscar</label>
                        </div>
                        <div class="ancho_5" style="margin-top:10px">
                            <input type="text" name="criteriobusqueda" class="full" value="" />
                        </div>
                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_2">
                            <div class="menubarra">
                                <ul>
                                    <li><a class="left boton azul" href="javascript:void(0)" onclick="gridReloadMultimedia()">Buscar</a></li>
                                </ul>
                            </div>        
                        </div>
                        <div class="clear aire_vertical">&nbsp;</div>
                    </div>
                    <div class="clear aire_vertical">&nbsp;</div>
                </form>
                </div>
            	<table id="TableMultimedia"></table>
                <div id="pagermultimedia"></div>
            </div>
        </div>
   </div>     
</div>