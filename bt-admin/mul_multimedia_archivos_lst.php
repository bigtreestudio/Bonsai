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

header('Content-Type: text/html; charset=iso-8859-1'); 
header("Content-Type: application/force-download");
$oMultimedia = new cMultimedia($conexion,"");

$error = false;

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
$datos["multimediaestadocod"]=ACTIVO;
$datos["multimediatipoarchivo"]="";


if (isset($_POST) && count($_POST)>0){
	$datos=$_SESSION['busquedamultimedia'] = $_POST;
	$titulo=$datos["multimediatitulo"];
	$nombre=$datos["multimedianombre"];
	$multimediaestadocod=$datos["multimediaestadocod"];
	$multimediatipoarchivo=$datos["multimediatipoarchivo"];
	//$multimediafalta=$datos["multimediafalta"];
}

$datos['orderby'] = "multimediacod DESC";
$datos['multimediaconjuntocod'] = FILES;

if(!$oMultimedia->BusquedaAvanzada($datos,$resultado,$numfilas)) {
	$error = true;
}


if (!isset($_GET['pagina']))
	$page = 1;
else
	$page = $_GET['pagina'];

	
$urlSiguiente = "mul_multimedia_archivos.php";
FuncionesPHPLocal::ArmarPaginado($cantidadpaginacion,$numfilas,$page,$primera,$ultima,$numpages,$current,$TotalSiguiente,$TotalVer);

$datos["limit"] = "LIMIT ".$current.",".$cantidadpaginacion;
$datos["orderby"]= "multimediacod DESC";
$datos['multimediaconjuntocod'] = FILES;


$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

if(!$oMultimedia->BusquedaAvanzada($datos,$resultado,$numfilas)) {
	$error = true;
}	
	
?>

<script type="text/javascript" src="modulos/mul_multimedia_modulo/js/mul_multimedia_modulo_archivos.js"></script>

<?php 
if ($numfilas>0)
{
?><div id="Caja_Multimedia_Modulo_Archivos">
    <div class="menubarra" style="padding:10px 0 5px 15px">
        <ul>
            <li style="color:#000"><input type="checkbox" onclick="CheckearTodos()" value="todos" id="todos" /><label for="todos" class="todos">Todos</label></li>
            <li><a class="left boton rojo" href="javascript:void(0)" onclick="return EliminarCheckeados()">Eliminar Seleccionados</a></li>
        </ul>
    </div>
    <div>
    
    <ul class="imagenes">
			<div class="paginator clearfix">
                    <div class="clearfix">
                        <?php 	
                        if($page>1) {?> 
                            <a href="javascript:void(0)" onclick="CargarListado('<?php  echo $page -1?>')" class="prev">Anterior</a>
                         <?php 	} ?>
                        <div class="pages">
                        <?php   for ($i = $primera; $i <= $ultima; $i++) {
                            $class = '';
                            if($i == $page)
                                $class = 'class="active"';
                            ?>
                            <a <?php  echo $class; ?> onclick="CargarListado('<?php  echo $i?>')"><?php  echo $i; ?></a>
                        <?php  	} ?>
    
                        </div>
                        <?php 	if($page<$numpages) {?> 
                            <a href="javascript:void(0)" onclick="CargarListado('<?php  echo $page +1?>')" class="next">Siguiente</a>
                        <?php 	} ?>
                    </div>
                </div>                               	
	<?php   
		$caracteres = 10;
		while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
            {
				$nombreArchivo = $fila['multimedianombre'];
				$total = strlen($fila['multimedianombre']);
				$caracteresFinal = $total - strpos($fila['multimedianombre'],".") ;
				if ($total>($caracteres+$caracteresFinal))
					$nombreArchivo = substr($fila['multimedianombre'],0,10)."[...]".substr($fila['multimedianombre'],($total-$caracteresFinal));
					
               ?><li class="caja_imagen" id="imagen_<?php  echo $fila['multimediacod']?>">
                    <span class="fondocolor">
                        <a class="caja_editar" onclick="EditarMultimediaModulo(<?php  echo $fila['multimediacod']?>,<?php  echo  $fila['multimediaconjuntocod'] ?>); return false;" href="javascript:void(0)" title="Imagen <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES)?>">
                        	&nbsp;
                        </a>
                    </span>
                    <div class="ancho">
                        <img src="<?php  echo  $oMultimedia->DevolverDireccionImg($fila)?>" alt="Imagen de <?php  echo  $fila['multimediatitulo']?> " />
                    </div>
                    <div class="desc" >
                     <?php  if($fila['multimediatitulo']!="") echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediatitulo'],ENT_QUOTES)." - ";?> <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombreArchivo,ENT_QUOTES)?> 
                    </div>
                    <div class="chk">
                        <input type="checkbox" class="multcheck" name="multcheck_<?php  echo $fila['multimediacod']?>" id="multcheck_<?php  echo $fila['multimediacod']?>" value="<?php  echo $fila['multimediacod']?>" onclick="return Seleccionado(<?php  echo $fila['multimediacod']?>);" />
                    </div>  
                    <a class="download_image" title="Descargar <?php  echo  $fila['multimediatitulo']?>" href="<?php  echo  DOMINIO_SERVIDOR_MULTIMEDIA.$fila['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$fila['multimediaubic'] ?>">
                     &nbsp
                    </a>
                    <a class="edit_image" target="_blank" onclick="EditarMultimediaModulo(<?php  echo $fila['multimediacod']?>,<?php  echo  $fila['multimediaconjuntocod'] ?>); return false;" href="javascript:void(0)">
                         &nbsp;
                    </a>
                    <a class="del_image" onclick="EliminarMultimediaModuloCompleto(<?php  echo $fila['multimediacod']?>); return false;" href="javascript:void(0)">
                         &nbsp;
                    </a>

                 </li><?php 
            }
           ?>
            </ul>    

			<div class="paginator clearfix">
                    <div class="clearfix">
                        <?php 	
                        if($page>1) {?> 
                            <a href="javascript:void(0)" onclick="CargarListado('<?php  echo $page -1?>')" class="prev">Anterior</a>
                         <?php 	} ?>
                        <div class="pages">
                        <?php   for ($i = $primera; $i <= $ultima; $i++) {
                            $class = '';
                            if($i == $page)
                                $class = 'class="active"';
                            ?>
                            <a <?php  echo $class; ?> onclick="CargarListado('<?php  echo $i?>')"><?php  echo $i; ?></a>
                        <?php  	} ?>
    
                        </div>
                        <?php 	if($page<$numpages) {?> 
                            <a href="javascript:void(0)" onclick="CargarListado('<?php  echo $page +1?>')" class="next">Siguiente</a>
                        <?php 	} ?>
                    </div>
                </div>

				<div style="padding:5px 0 10px 0"></div>
    <div>
  </div>
<?php 
}else{ ?>
    <div class="sinresultados">
        <div class="totales">Sin Resultados</div>
        <div class="txt">
            No se encontraron resultados que coincidan con los t&eacute;rminos de b&uacute;squeda ingresados.
        </div>
        <hr />
    </div>
<?php  } 
