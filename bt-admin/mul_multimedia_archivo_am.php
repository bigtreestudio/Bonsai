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
	
	switch($datos['multimediatipoarchivo'])
	{
		case "DOC":
			$icono = "doc-128x128.png";
			break;	

		case "XLS":
			$icono = "xls-128x128.png";
			break;	
			
		case "XLSX":
			$icono = "xlsx-128x128.png";
			break;		

		case "DOCX":
			$icono = "docx-128x128.png";
			break;	

		case "PDF":
			$icono = "pdf-128x128.png";
			break;	

		case "TXT":
			$icono = "txt-128x128.png";
			break;	
			
		case "PPT":
			$icono = "ppt-128x128.png";
			break;
			
		case "PPTX":
			$icono = "pptx-128x128.png";
			break;			
	
	
	}
}

function CargarCategorias($catcod,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"';?>  value="<?php  echo $fila['catcod']?>" ><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php  
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($catcod,$fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}	

$oMultimediaCategorias=new cMultimediaCategorias($conexion);
$catsuperior="";
if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
	$mostrar=false;

?>
<link href="modulos/mul_multimedia/css/popup.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia_modulo/js/mul_multimedia_modulo.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia_modulo/js/mul_multimedia_modulo_archivos.js"></script>
<div>
     <?php  if (!$esmodif){?>
     <script type="text/javascript" src="modulos/mul_multimedia/js/mul_multimedia_archivos.js"></script>

    <div id="mul_multimedia_fotos">
      
       <ul>
            <li><a href="#mul_multimedia_subir">Subir Archivo Nuevo</a></li>
        </ul>
        <div id="mul_multimedia_subir">
        	<div class="form">
                <form action="noticias_lst.php" method="post" name="form_mul_multimedia_file" id="form_mul_multimedia_file">
                   <div class="ancho_10">
                        <div id="mul_multimedia_bt_subir_file"></div> 
                        <input type="hidden" name="mul_multimedia_size" id="mul_multimedia_size"  value="" />
                        <input type="hidden" name="mul_multimedia_name" id="mul_multimedia_name"  value="" />
                        <input type="hidden" name="mul_multimedia_file" id="mul_multimedia_file"  value="" />
                        <div class="clear fixalto">&nbsp;</div>
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                    <div class="ancho_3">
                    	<div id="mul_multimedia_previsualizar"></div>
                    </div>
                    <div class="ancho_7" id="mul_multimedia_descripcion" style="display:none">
						<?php 
                            $oMultimediaCategorias=new cMultimediaCategorias($conexion);
                            $catsuperior="";
                            if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
                                $mostrar=false;
                            
                            $catcod = "";
                        ?>               
                       <div class="ancho_1" style="padding-top:5px;">
                            <label>Categoria: </label>
                       </div>
                       <div class="ancho_1">&nbsp;</div>
                       <div class="ancho_8">
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
                    	<input type="text" name="multimedia_titulo" style="width:100%" id="multimedia_titulo" maxlength="255" class="full" />
                       <div class="ancho_1" style="padding-top:5px;">
                            <label>Descripci&oacute;n </label>
                       </div>
                    	<textarea name="multimedia_desc" style="width:100%" id="multimedia_desc" cols="20" rows="4"></textarea>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                        <div class="menubarra" style="padding-top:5px">
                            <ul>
                                <li><a class="lef boton verde" href="javascript:void(0)" onclick="GuardarArchivo()">Guardar Archivo</a></li>
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
                        <img src="<?php  echo 'images/fileformat/'.$icono ?>" title="archivo <?php  echo $datos['multimediatipoarchivo']?>" />
                    </div>
                    <div class="ancho_7">
						<?php 
                            $oMultimediaCategorias=new cMultimediaCategorias($conexion);
                            $catsuperior="";
                            if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
                                $mostrar=false;
                        ?>               
                       <div class="ancho_1" style="padding-top:5px;">
                            <label>Categoria: </label>
                       </div>
                       <div class="ancho_1">&nbsp;</div>
                       <div class="ancho_8">
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
                    	<input type="text" name="multimedia_titulo" style="width:100%" id="multimedia_titulo" maxlength="255" class="full"  value="<?php  echo  utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimediatitulo'],ENT_QUOTES));?>" />
                       <div class="ancho_1" style="padding-top:5px;">
                            <label>Descripci&oacute;n </label>
                       </div>
                       <textarea name="multimedia_desc" style="width:100%" id="multimedia_desc" cols="20" rows="4"><?php  echo utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimediadesc'],ENT_QUOTES));?></textarea>
                       <div class="menubarra">
                            <ul>
                                <li><a class="left boton verde" href="javascript:void(0)" onclick="ModificarDescripcionMultimediaUnicoCompleto(<?php  echo $datos['multimediacod']?>)">Guardar cambios</a></li>
                                <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Volver sin guardar</a></li>
                            </ul>
                        </div>        
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                </form>        

                 </div>
				<?php 
			}
			?>
        
   </div>     
</div>