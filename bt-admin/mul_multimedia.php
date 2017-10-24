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

$_SESSION['volveratras'] = "mul_multimedia.php";


$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';
	
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
<script type="text/javascript">var sizeLimitFileAudio = <?php  echo TAMANIOARCHIVOSAUDIO;?>;</script>
<script type="text/javascript" src="modulos/mul_multimedia/js/mul_multimedia.js"></script>
<script type="text/javascript" src="modulos/mul_multimedia/js/mul_multimedia_am.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Multimedia</h2>
</div>
<div class="txt_izq">
    <form action="noticias_lst.php" method="post" name="formbusqueda" id="formbusqueda">
		<div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_2">
    	            <label>Nombre:</label>
                </div>
                <div class="ancho_8">
	                <input type="text" name="multimedianombre" id="multimedianombre" class="full" onkeypress="doSearchMultimedia(arguments[0]||event)" maxlength="100" value="" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_4">
                <div class="ancho_2">
    	            <label>Tipo:</label>
                </div>
                <div class="ancho_8">
	                <?php  
					$oMultimediaTipos->SpMultimediaTipos($spnombre,$sparam);
					FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formbusqueda","multimediatipoarchivo","multimediatipoarchivo","multimediatipodesc","","Sin filtro...",$regnousar,$selecnousar,1,"doSearchMultimedia(arguments[0]||event)","width: 100%"); 
					?>
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
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_4">
				<?php 
                    $oMultimediaCategorias=new cMultimediaCategorias($conexion);
                    $catsuperior="";
                    if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
                        $mostrar=false;
                    
                    $catcod = "";
                ?>               
                <div class="ancho_2">
    	            <label>Categoria:</label>
                </div>
                <div class="ancho_8">
                    <select name="catcod" id="catcod" style=" width:100%;" onchange="doSearchMultimedia(arguments[0]||event)">
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
            </div>
            <div class="clear fixalto">&nbsp;</div>
            <div class="ancho_05">&nbsp;</div>
           <div class="ancho_3">
                <div class="ancho_2">
    	            <label>Estado:</label>
                </div>
                <div class="ancho_8">
                	<select name="multimediaestadocod" id="multimediaestadocod" onchange="doSearchMultimedia(arguments[0]||event)" style="width:100%;">
                    	<option value="<?php  echo MULTACTIVO?>,<?php  echo MULTNOACTIVO?>">Sin filtro...</option>
                    	<option value="<?php  echo MULTACTIVO?>">Activos</option>
                    	<option value="<?php  echo MULTNOACTIVO?>">No Activos</option>
                    	<option value="<?php  echo MULELIMINADO?>">Eliminados</option>
					</select>
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="clear fixalto">&nbsp;</div>
       </div>
        <div class="clear fixalto">&nbsp;</div>
        
        <div id="PopupMultimedia"></div>
	</form>
</div>
<div class="clear fixalto">&nbsp;</div>
<div class="menubarra">
    <ul>
       <li><a class="left" href="javascript:void(0)" onclick="Limpiar()">Limpiar filtros</a></li>
    </ul>
</div>
<div class="clear fixalto">&nbsp;</div>
<div class="multimedia_alta menubarra">
    <ul>
        <li><a class="left" href="javascript:void(0)" onclick="return AbrirPopupNuevoArchivo()">Nuevo Archivo</a></li>
        <li><a class="left" href="javascript:void(0)" onclick="return AbrirPopupNuevaFoto()">Nueva Foto</a></li>
        <li><a class="left" href="javascript:void(0)" onclick="return AbrirPopupNuevoAudio()">Nuevo Audio</a></li>
        <li><a class="left" href="javascript:void(0)" onclick="return AbrirPopupNuevoVideo()">Nuevo Video</a></li>
    </ul>
    
</div>      
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstMultimedia" style="width:100%;">
    <table id="ListadoMultimedia"></table>
    <div id="pager2"></div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>