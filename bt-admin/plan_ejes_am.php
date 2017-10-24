<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oObjeto = new cPlanEjes($conexion);

$oMultimedia = new cMultimedia($conexion,"noticias/");

$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$planejecod = "";
$planejenombre = "";
$multimediacod = "";
$planejeconstante = "";
$planejedescripcion = "";
$planejecolor = "#000000";
$planejeestado = "";
if (isset($_GET['planejecod']) && $_GET['planejecod']!="")
{
	$esmodif = true;
	$datos = $_GET;
	if(!$oObjeto->BuscarxCodigo($datos,$resultado,$numfilas))
		return false;
	if($numfilas!=1){
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Codigo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosregistro = $conexion->ObtenerSiguienteRegistro($resultado);
	$onclick = "return Modificar();";
	$planejecod = $datosregistro["planejecod"];
	$planejenombre = $datosregistro["planejenombre"];
	$multimediacod = $datosregistro["multimediacod"];
	$planejeconstante = $datosregistro["planejeconstante"];
	$planejedescripcion = $datosregistro["planejedescripcion"];
	$planejecolor = $datosregistro["planejecolor"];
	$planejeestado = $datosregistro["planejeestado"];
}
?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js"></script>
<script type="text/javascript" src="modulos/plan_ejes/js/plan_ejes_am.js"></script>
<script type="text/javascript" src="js/multimediaSelectorFotos.js"></script>	
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	$("#planejecolor").miniColors();
	
});
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Eje</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
	<div class="form">
   		
        <div class="col-md-7 col-xs-12 col-sm-6">
			<form action="plan_ejes.php" method="post" name="formalta" id="formalta" >
				
							<div class="form-group clearfix">
                            	<label for="planejenombre">Nombre</label>
								<input type="text" class="form-control input-md" maxlength="255" name="planejenombre" id="planejenombre" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planejenombre,ENT_QUOTES)?>" />
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div class="form-group clearfix">
                            	<label for="planejeconstante">Constante</label>
								<input type="text" class="form-control input-md" maxlength="255" name="planejeconstante" id="planejeconstante" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planejeconstante,ENT_QUOTES)?>" />
                            </div>
                             <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div class="form-group clearfix">
                            	<label for="temacolor">Color</label><br />
								<input type="text" class="form-control input-sm" maxlength="7" style="width:20%;float:left" name="planejecolor" id="planejecolor" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planejecolor,ENT_QUOTES)?>" />
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div class="form-group clearfix">
                            	<label for="planejedescripcion">Descripci&oacute;n</label>
								<textarea class="form-control input-md rich-text" rows="6" cols="20" name="planejedescripcion" id="planejedescripcion"><?php   echo $planejedescripcion?></textarea>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            			    
					
                    <input type="hidden" name="planejecod" id="planejecod" value="<?php   echo $planejecod?>" />
                        
                	<div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="<?php   echo $onclick ?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="plan_ejes.php">Volver</a></div></li>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                    <div class="msgaccionupd">&nbsp;</div>
                    <div class="menubarra pull-right">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton azul" href="plan_ejes_am.php">Crear nuevo </a></div></li>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>    
                    
                </div>
                <div class="clearboth">&nbsp;</div>
        	</form>
        </div>
        
        <div class="col-md-5 col-xs-12 col-sm-6">
		

            <form action="plan_ejes.php" method="post" name="formaltamultimediasimple" id="formaltamultimediasimple" >
						<div style="margin-bottom:10px;">
                        	<label for="multimediacod">Imagen</label>
                        </div>
            		  	<div class="menubarra">
                            <ul>
                                <li><div class="ancho_boton aire"><a class="boton azul" href="javascript:void(0)" onclick="return SeleccionarMultimediaRepositorioFotos('multimediacod')">Seleccione una Im&aacute;gen</a></div></li>
                            </ul>
                            <div class="clearboth">&nbsp;</div>
                        </div>
                          <div><input type="hidden" name="multimediacod" id="multimediacod" value="<?php   echo $multimediacod?>" /></div>
                          <div class="clearboth brisa_vertical">&nbsp;</div>
                          <div id="multimediapreview_multimediacod">
							<?php   $titulomultimedia_multimediacod = "" ?>                            
							<?php   if ($multimediacod!=""){                            
									$datosBusqueda["multimediacod"] = $multimediacod;
								if(!$oMultimedia->BuscarMultimediaxCodigo($datosBusqueda,$resultado,$numfilas))
										return false;
									$datosMultimedia = $conexion->ObtenerSiguienteRegistro($resultado);
									$html = $oMultimedia->VisualizarArchivoSimpleMultimedia($datosMultimedia);
									echo $html;
									$titulomultimedia_multimediacod = $datosMultimedia["multimedianombre"];
									if($datosMultimedia["multimediatitulo"]!="")
									$titulomultimedia_multimediacod = $datosMultimedia["multimediatitulo"];
									?>
									<?php   }?><?php   $oculto_multimediacod='style="display:none"';
									if ($esmodif && $multimediacod!=""){$oculto_multimediacod='';} ?>
									<a id="multimediaeliminar_multimediacod" <?php   echo $oculto_multimediacod; ?>  href="javascript:void(0)" onclick="return EliminarMultimediaRepositorioFotos('multimediacod','planejecod')"><img src="images/cross.gif"  alt="Eliminar"></a>
									<div><?php   echo utf8_encode($titulomultimedia_multimediacod); ?></div>
									<div class="clearboth aire_vertical">&nbsp;</div>
							</div>
						</form>
                                <div class="txt">Recuerde <strong>guardar</strong> para que se realicen los cambios</div>
                                
                
            </div>
        <div class="clearboth">&nbsp;</div>
    </div>
    <div class="clearboth">&nbsp;</div>
</div>


<?php 
$oEncabezados->PieMenuEmergente();

?>