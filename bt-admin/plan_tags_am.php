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

$oObjeto = new cPlanTags($conexion);
$oPlanEjes = new cPlanEjes($conexion);

$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$plantagcod = "";
$plantagnombre = "";
$plantagcatcod = "";
$plantagestado = "";
$plantagcolor = "#000000";
$plantagclass = "";
$planejecod = "";
$plantagorden = "";
if (isset($_GET['plantagcod']) && $_GET['plantagcod']!="")
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
	$plantagcod = $datosregistro["plantagcod"];
	$plantagnombre = $datosregistro["plantagnombre"];
	$plantagcatcod = $datosregistro["plantagcatcod"];
	$plantagestado = $datosregistro["plantagestado"];
	$plantagcolor = $datosregistro["plantagcolor"];
	$plantagclass = $datosregistro["plantagclass"];
	$planejecod = $datosregistro["planejecod"];
	$plantagorden = $datosregistro["plantagorden"];
}
if(!$oObjeto->plan_tags_categoriasSPResult($result_plan_tags_categorias,$numfilas_plan_tags_categorias))
	return false;
	
	
$datos_plan_ejes['planejeestado'] = ACTIVO;	
if(!$oPlanEjes->BusquedaAvanzada($datos_plan_ejes,$result_plan_ejes,$numfilas_plan_plan_ejes))
	return false;	
?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js"></script>
<script type="text/javascript" src="modulos/plan_tags/js/plan_tags_am.js"></script>
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	$("#plantagcolor").miniColors();
	
});
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Tags</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
	<div class="form">
   		
        <div class="col-md-7 col-xs-12 col-sm-6">
			<form action="plan_tags.php" method="post" name="formalta" id="formalta" >
				
							<div class="form-group clearfix">
                            	<label for="plantagnombre">Nombre</label>
								<input type="text" class="form-control input-md" maxlength="255" name="plantagnombre" id="plantagnombre" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($plantagnombre,ENT_QUOTES)?>" />
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div class="form-group clearfix">
                                    <label for="plantagcatcod">Categor&iacute;a</label>
                                    <select class="form-control input-md" name="plantagcatcod" id="plantagcatcod">
                                        <option value="">Seleccione un Categor&iacute;a</option>
                                
										<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_tags_categorias)){?>
                                            <option <?php if ($filaCombo['plantagcatcod']==$plantagcatcod) echo 'selected="selected"'?> value="<?php echo $filaCombo['plantagcatcod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['plantagcatnombre'],ENT_QUOTES);?></option>
                                        <?php }?>
                                    </select>
                             </div>       
                             <div class="clearboth brisa_vertical">&nbsp;</div>
                             <div class="clearboth brisa_vertical">&nbsp;</div>
                            <div class="form-group clearfix">
                                    <label for="plantagcatcod">Eje</label>
                                    <select class="form-control input-md" name="planejecod" id="planejecod">
                        				<option value="">Selecione un eje...</option>
                                        
										<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_ejes)){?>
                                            <option <?php if ($filaCombo['planejecod']==$planejecod) echo 'selected="selected"'?>  value="<?php echo $filaCombo['planejecod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planejenombre'],ENT_QUOTES);?></option>
                                        <?php }?>
									</select>
                             </div>       
                             <div class="clearboth brisa_vertical">&nbsp;</div>
                             
                             
                             
                             <div class="form-group clearfix">
                                    <label for="plantagcolor">Color</label><br />
                                    <input type="text" class="form-control input-sm" maxlength="7" style="width:20%;float:left" name="plantagcolor" id="plantagcolor" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($plantagcolor,ENT_QUOTES)?>" />
                             </div>
                             <div class="clearboth brisa_vertical">&nbsp;</div>
                                
                                
                                
                                
                             <div class="form-group clearfix">
                                	<label for="plantagclass">Class</label>
									<input type="text" class="form-control input-md" maxlength="255" name="plantagclass" id="plantagclass" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($plantagclass,ENT_QUOTES)?>" />
                             </div>
                             <div class="clearboth brisa_vertical">&nbsp;</div>
                                
					
                    <input type="hidden" name="plantagcod" id="plantagcod" value="<?php   echo $plantagcod?>" />
                        
                	<div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="<?php   echo $onclick ?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="plan_tags.php">Volver</a></div></li>
                        	                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                    <div class="msgaccionupd">&nbsp;</div>
                    <div class="menubarra pull-right">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton azul" href="plan_tags_am.php">Crear nuevo </a></div></li>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>    
                    
                </div>
                <div class="clearboth">&nbsp;</div>
        	</form>
        </div>
        
        <div class="col-md-5 col-xs-12 col-sm-6">
		                <div class="txt">Recuerde <strong>guardar</strong> para que se realicen los cambios</div>
                                
                
            </div>
        <div class="clearboth">&nbsp;</div>
    </div>
    <div class="clearboth">&nbsp;</div>
</div>


<?php 
$oEncabezados->PieMenuEmergente();

?>