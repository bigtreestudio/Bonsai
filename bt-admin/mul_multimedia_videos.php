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

$oMultimediaTipos = new cMultimediaTipos($conexion);

$_SESSION['volveratras'] = "mul_multimedia_videos.php";

$_SESSION["busquedamultimedia"]="";
$datos=array();
$cantidadpaginacion = PAGINAR;
$datosbusqueda=array();
$multimediacod="";
$multimediaestadocod=ACTIVO;
$titulo="";
$multimediatitulo="";
$nombre="";
$multimediafalta="";
$multimediatipoarchivo="";
$datos["multimediafalta"]="";
$datos["multimedianombre"]="";
$datos["multimediatitulo"]="";
$datos["multimediaestadocod"]="";
$datos["multimediatipoarchivo"]="";

if (isset($_POST) && count($_POST)>0){
	$datos=$_SESSION['busquedamultimedia'] = $_POST;
	$titulo=$datos["multimediatitulo"];
	$nombre=$datos["multimedianombre"];
	$multimediaestadocod=$datos["multimediaestadocod"];
	$multimediatipoarchivo=$datos["multimediatipoarchivo"];
	//$multimediafalta=$datos["multimediafalta"];
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
?>


<link rel="stylesheet" type="text/css" href="modulos/mul_multimedia/css/estilos.css" />
<script type="text/javascript">var sizeLimitFile = <?php  echo TAMANIOARCHIVOS;?>;</script>
<script type="text/javascript" src="modulos/mul_multimedia_modulo/js/mul_multimedia_modulo.js"></script>

<script type="text/javascript" >
var archivoreload = "mul_multimedia_videos_lst.php";
jQuery(document).ready(function(){
	  CargarListado(1);
});
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Videos</h2>
</div>
<div class="form">
    <form action="mul_multimedia_videos.php" method="post" name="busquedamultimedia" id="busquedamultimedia">
		
        <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_2">
    	            <label>Nombre:</label>
                </div>
                <div class="ancho_8">
	                <input type="text" name="multimedianombre" id="multimedianombre" value="<?php  echo $nombre?>" class="full" maxlength="100"  />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_4">
                <div class="ancho_2">
    	            <label>Tipo:</label>
                </div>
                <div class="ancho_8">
	                <?php  
					$datos["multimediaconjuntocod"]=VIDEOS;
					$oMultimediaTipos->SpMultimediaTiposxTipo($datos,$spnombre,$sparam);
					FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"busquedamultimedia","multimediatipoarchivo","multimediatipoarchivo","multimediatipodesc","","Sin filtro...",$regnousar,$selecnousar,1,"","width: 100%"); 
					?>
                </div>
            </div>
            <div class="clear fixalto">&nbsp;</div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_2">
    	            <label>T&iacute;tulo:</label>
                </div>
                <div class="ancho_8">
	                <input type="text" name="multimediatitulo" id="multimediatitulo" value="<?php  echo $titulo?>" class="full" maxlength="100"  />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_4">
                <div class="ancho_2">
                    <label>Estado:</label>
                </div>
                <div class="ancho_8">
                    <select name="multimediaestadocod" id="multimediaestadocod" >
                        <option value="<?php  echo MULTACTIVO?>,<?php  echo MULTNOACTIVO?>">Sin filtro...</option>
                        <option  <?php  if ($multimediaestadocod==MULTACTIVO) echo "selected=selected"; ?> value="<?php  echo MULTACTIVO?>">Activos</option>
                        <option <?php  if ($multimediaestadocod==MULTNOACTIVO) echo "selected=selected";?> value="<?php  echo MULTNOACTIVO?>">No Activos</option>
                        <option <?php  if ($multimediaestadocod==MULELIMINADO) echo "selected=selected";?> value="<?php  echo MULELIMINADO?>">Eliminados</option>
                    </select>
                </div>
           </div>

            <div class="clear fixalto">&nbsp;</div>
            <div class="ancho_05">&nbsp;</div>
           <div class="ancho_3">
                <div class="ancho_2">
    	            <label>C&oacute;digo Externo:</label>
                </div>
                <div class="ancho_8">
	                <input type="text" name="multimediaidexterno" id="multimediaidexterno" class="full" onkeypress="doSearchMultimedia(arguments[0]||event)" maxlength="100" value="" />
                </div>
            </div>

       </div>

        <div class="clear fixalto" style="padding-top:20px">&nbsp;</div>

        <div class="BtcontactoFondo">
            <div class="ancho_10">
                <div class="ancho_4"> 
           		 <input type="button" name="Limpiar"  class="boton azul"  value="Buscar" onclick=" return Buscar();" />
           		</div>
                <div class="ancho_6">
            		<input type="button" name="Limpiar"  class="boton base"  value="Limpiar Filtros" onclick=" return LimpiarFiltrosImagenes();" />
       			</div>
            </div>
        </div> 
        <div class="clear fixalto">&nbsp;</div>
        <div id="PopupMultimedia"></div>
        <div id="PopupMultimediaAlta"></div>
	</form>
</div>
<div class="clear fixalto">&nbsp;</div>

<div class="multimedia_alta menubarra">
    <ul>
        <li><a class="left boton verde" href="javascript:void(0)" onclick="return AbrirPopupNuevoVideoModuloVideos()">Nuevo Video</a></li>
    </ul>
</div>   
   
<div class="clear aire_vertical">&nbsp;</div>
<div id="ListadoMultimedia">

</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>