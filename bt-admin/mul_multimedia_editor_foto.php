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
 $fila=array();
 $fila['alinearcod']='';
 $alinearcod='1';
 $formatocod='';
 $checkedi='';
 $checkedd='';
 $checkedc='' ;
 $checkedn=''; 
 $imgrurl='';
 $imgtitrurl='';
 $editorId = $_GET['editorid'];

?>
<link href="modulos/mul_multimedia_editor/css/popup.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia_editor/js/funciones.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia_editor/js/mul_multimedia_fotos.js"></script>
<div style="float:left; width:430px">
    <div id="mul_multimedia_fotos">
        <ul>
            <li style="font-weight:bold; padding:5px 5px 10px 0px">Seleccionar una Im&aacute;gen Existente</li>
        </ul>
        	<div id="TableMultimedia" style="width:430px;">
            	<form action="javascript:void(0)" method="post" name="formbusquedamultimedia" id="formbusquedamultimedia">
                	<div class="ancho_100">
                    	<div class="ancho_2">
                        	<label>Descripci&oacute;n</label>
                        </div>
                        <div class="ancho_7">
                        	<input type="text" name="multimedia_desc_busqueda" onkeypress="KeyPressBusquedaMultimedia(arguments[0]||event)" id="multimedia_desc_busqueda" class="full" value="" />
                        </div>
                    </div>
                    <div class="clear aire_vertical">&nbsp;</div>
                </form>
            	<table id="TableEditorMultimediaFoto"></table>
              
                <div id="pagereditormultimedia"></div>
        </div>
 
   </div>   
     
</div>


<div style="float:left; width:375px">
    <div style="font-weight:bold; margin-top:15px; ">Imagen Seleccionada:</div>
    <div id="ImagenSeleccionada" style="height:100px;  max-width:150px; margin-left:160px;"></div>
    <div style="clear:both">&nbsp;</div>
    <div class="ancho_10">
        <div style="margin:5px 0px 5px 0px">
            <label>Alinear:</label>
        </div>
         <div  style=" width:350px">
                <input type="radio" tabindex="6" name="alinearcod" id="alinearcod_d"  <?php  echo $checkedi ?>  value="I" />
                <b>Izquierda</b>
                <input type="radio" tabindex="6" name="alinearcod" id="alinearcod_d" <?php  echo $checkedd ?> value="D" />
                <b>Derecha</b>
                <input type="radio" tabindex="6" name="alinearcod" id="alinearcod_c" <?php  echo $checkedc ?>  value="C" />
                <b>Centro</b>
                <input type="radio" tabindex="6" name="alinearcod" id="alinearcod_n" <?php  echo $checkedn ?> value="N" />
                <b>Ninguno</b>                    
         </div>        
   <div class="clearboth brisa_vertical"  style="height:25px;" >&nbsp;</div>
  <div style="float:left;width:70px"> 
   <label>Tama&ntilde;o:</label>
  </div>
   <div style="float:left">
     <?php  
        $oMultimediaFormatos = new cMultimediaFormatos($conexion);
        $oMultimediaFormatos->BuscarMultimadiaFormatosSP($spnombre,$sparam);
        FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","formatocod","formatocod","formatodesc",$formatocod,"Ninguno",$regactual,$seleccionado,1,"",false,false);
    ?>                     
    </div>   
    
    <div class="clearboth aire_menor">&nbsp;</div>
    <div>
        <label>URL:</label>
    </div>
    <div class="clearboth brisa_vertical">&nbsp;</div>
    <div>
            <input type="text" name="imgrurl" id="imgrurl" class="full" maxlength="255" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($imgrurl,ENT_QUOTES);?>" />
    </div>    
    <div class="clearboth aire_menor">&nbsp;</div>
    <div>
        <label>Titulo URL:</label>
    </div>
    <div class="clearboth brisa_vertical">&nbsp;</div>
    <div>
           <input type="text" name="imgtitrurl" id="imgtitrurl" class="full" maxlength="255" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($imgtitrurl,ENT_QUOTES);?>" />
    </div> 
    <div class="clearboth aire_menor" style="height:100px">&nbsp;</div>
    <div style="text-align:right">
      <input type="button" class="boton" name="Agregar" value="Agregar" onclick="AgregarImagenTinyMce()" />
    	<input type="hidden" name="editorid" id="editorid" value="<?php  echo $editorId?>" />
	</div>
            
</div> 

