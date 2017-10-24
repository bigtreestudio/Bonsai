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

$oObjeto = new cPlanProyectos($conexion);

$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$planproyectocod = "";
$planproyectonombre = "";
$planproyectodescripcion = "";
$planproyectoobjetivo = "";
$planobjetivocod = "";
$planjurisdiccioncod = "";
$planproyectofdesde = "";
$planproyectofhasta = "";
$planproyectoestadocod = "";
$planproyectoestado = "";
$planproyectofalta = "";
if (isset($_GET['planproyectocod']) && $_GET['planproyectocod']!="")
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
	$planproyectocod = $datosregistro["planproyectocod"];
	$planproyectonombre = $datosregistro["planproyectonombre"];
	$planproyectodescripcion = $datosregistro["planproyectodescripcion"];
	$planproyectoobjetivo = $datosregistro["planproyectoobjetivo"];
	$planobjetivocod = $datosregistro["planobjetivocod"];
	$planjurisdiccioncod = $datosregistro["planjurisdiccioncod"];
	$planproyectofdesde = FuncionesPHPLocal::ConvertirFecha($datosregistro["planproyectofdesde"],'aaaa-mm-dd','dd/mm/aaaa');
	$planproyectofhasta = FuncionesPHPLocal::ConvertirFecha($datosregistro["planproyectofhasta"],'aaaa-mm-dd','dd/mm/aaaa');
	$planproyectoestadocod = $datosregistro["planproyectoestadocod"];
	$planproyectoestado = $datosregistro["planproyectoestado"];
	$planproyectofalta = FuncionesPHPLocal::ConvertirFecha($datosregistro["planproyectofalta"],'aaaa-mm-dd','dd/mm/aaaa');
}
if(!$oObjeto->plan_objetivosSPResult($result_plan_objetivos,$numfilas_plan_objetivos))
	return false;
if(!$oObjeto->plan_jurisdiccionesSPResult($result_plan_jurisdicciones,$numfilas_plan_jurisdicciones))
	return false;
if(!$oObjeto->plan_proyectos_estadosSPResult($result_plan_proyectos_estados,$numfilas_plan_proyectos_estados))
	return false;
?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js"></script>
<script type="text/javascript" src="modulos/plan_proyectos/js/plan_proyectos_am.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Proyectos</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
	<div class="form">
   		
        <div class="col-md-7 col-xs-12 col-sm-6">
        	<form action="plan_proyectos.php" method="post" name="formalta" id="formalta" >
        
                <div class="form-group clearfix">
                	<label for="planproyectonombre">Nombre</label>
                	<input type="text" class="form-control input-md" maxlength="255" name="planproyectonombre" id="planproyectonombre" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planproyectonombre,ENT_QUOTES)?>" />
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="form-group clearfix">
                	<label for="planproyectodescripcion">Descripci&oacute;n</label>
                	<textarea class="form-control input-md rich-text" rows="6" cols="20" name="planproyectodescripcion" id="planproyectodescripcion"><?php   echo $planproyectodescripcion?></textarea>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="form-group clearfix">
                	<label for="planproyectoobjetivo">Objetivo</label>
                	<input type="text" class="form-control input-md" maxlength="255" name="planproyectoobjetivo" id="planproyectoobjetivo" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planproyectoobjetivo,ENT_QUOTES)?>" />
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="form-group clearfix">
                	<label for="planobjetivocod">Objetivo</label>
                	<select class="form-control input-md" name="planobjetivocod" id="planobjetivocod">
                		<option value="">Seleccione un Objetivo</option>
                
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_objetivos)){?>
                            <option <?php if ($filaCombo['planobjetivocod']==$planobjetivocod) echo 'selected="selected"'?> value="<?php echo $filaCombo['planobjetivocod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planobjetivonombre'],ENT_QUOTES);?></option>
                            <?php }?>
                	</select>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="form-group clearfix">
                	<label for="planjurisdiccioncod">Jurisdicci&oacute;n</label>
                	<select class="form-control input-md" name="planjurisdiccioncod" id="planjurisdiccioncod">
                		<option value="">Seleccione un Jurisdicci&oacute;n</option>
                
						<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_jurisdicciones)){?>
                        <option <?php if ($filaCombo['planjurisdiccioncod']==$planjurisdiccioncod) echo 'selected="selected"'?> value="<?php echo $filaCombo['planjurisdiccioncod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planjurisdiccionnombre'],ENT_QUOTES);?></option>
                        <?php }?>
                	</select>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="form-group clearfix">
                	<label for="planproyectofdesde">Fecha Inicio</label>
                	<input type="text" class="form-control input-md fechacampo" style="width:20%;" maxlength="10" name="planproyectofdesde"  id="planproyectofdesde" value="<?php   echo $planproyectofdesde?>" />
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="form-group clearfix">
                	<label for="planproyectofhasta">Fecha Fin</label>
                	<input type="text" class="form-control input-md fechacampo" style="width:20%;" maxlength="10" name="planproyectofhasta" id="planproyectofhasta" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planproyectofhasta,ENT_QUOTES)?>" />
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="form-group clearfix">
                	<label for="planproyectoestadocod">Estado</label>
                	<select class="form-control input-md" name="planproyectoestadocod" id="planproyectoestadocod">
                		<option value="">Seleccione un Estado</option>
                
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_proyectos_estados)){?>
                            <option <?php if ($filaCombo['planproyectoestadocod']==$planproyectoestadocod) echo 'selected="selected"'?> value="<?php echo $filaCombo['planproyectoestadocod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planproyectoestadonombre'],ENT_QUOTES);?></option>
                            <?php }?>
                	</select>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <input type="hidden" name="planproyectocod" id="planproyectocod" value="<?php   echo $planproyectocod?>" />
                
                <div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="<?php   echo $onclick ?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="plan_proyectos.php">Volver</a></div></li>
                        </ul>
                    <div class="clearboth">&nbsp;</div>
                    </div>
                	<div class="msgaccionupd">&nbsp;</div>
                	<div class="menubarra pull-right">
                		<ul>
                			<li><div class="ancho_boton aire"><a class="boton azul" href="plan_proyectos_am.php">Crear nuevo </a></div></li>
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
        <div class="col-md-12 col-xs-12 col-sm-12">
		     <?php if($esmodif){
				
				
				 $oPlanProyectosComunas = new cPlanProyectosComunas($conexion);

				if(!$oPlanProyectosComunas->gcba_comunasSPResult($result_gcba_comunas,$numfilas_gcba_comunas))
					return false;
				 
				 
				 ?>
				<!--  Comunas -->
				<script type="text/javascript" src="modulos/plan_proyectos_comunas/js/plan_proyectos_comunas.js"></script>
				
                <div class="panel-style space">
                <div class="inner-page-title" style="padding-bottom:2px;">
                    <h2>Comunas</h2>
                </div>
                <div class="clear fixalto">&nbsp;</div>
                    <form action="plan_proyectos_am.php" method="post" name="formbusqueda_plan_proyectos_comunas" class="general_form" id="formbusqueda_plan_proyectos_comunas">
                        <input type="hidden" name="planproyectocod" id="planproyectocod"  value="<?php echo $planproyectocod;?>" />
                    </form> 
                    
                     <form action="plan_proyectos_am.php" method="post" name="formalta_plan_proyectos_comunas" class="general_form" id="formalta_plan_proyectos_comunas">
                        <input type="hidden" name="planproyectocod" id="planproyectocod"  value="<?php echo $planproyectocod;?>" />
                        <div class="col-md-5 col-xs-12 col-sm-12">
                        <select class="form-control input-md" name="comunacod" id="comunacod">
                                    <option value="">Selecione una Comuna...</option>
                                    
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_gcba_comunas)){?>
                                <option value="<?php echo $filaCombo['comunacod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['comunanumero']." - ".$filaCombo['comunabarrios'],ENT_QUOTES);?></option>
                            <?php }?>
                        </select>
                        </div>
                        <div class="col-md-5 col-xs-12 col-sm-12">
                            <div class="ancho_boton aire" style="margin-top: -8px;"><a class="boton verde" href="javascript:void(0)" onclick="AgregarComuna()">Agregar Comuna</a></div>
                        </div>
                         <div class="clearboth">&nbsp;</div>
                     </form> 
                <div class="clear" style="height:1px;">&nbsp;</div>
                <div id="LstDatos_plan_proyectos_comunas" style="width:100%;">
                       <table id="listarDatos_plan_proyectos_comunas"></table>
                    <div id="pager_plan_proyectos_comunas"></div>
                </div>
                </div>
                
                <!--  Ejes -->
                <? 
					$oPlanEjes = new cPlanEjes($conexion);
					$datos_ejes['planejeestado'] = ACTIVO;
					if(!$oPlanEjes->BusquedaAvanzada($datos_ejes,$result_ejes,$numfilas_ejes))
						return false;
				 
				
				?>
                <script type="text/javascript" src="modulos/plan_proyectos_ejes/js/plan_proyectos_ejes.js"></script>
                <div class="panel-style space">
                <div class="inner-page-title" style="padding-bottom:2px;">
                    <h2>Ejes</h2>
                </div>
                <div class="clear fixalto">&nbsp;</div>
                    <form action="plan_proyectos_am.php" method="post" name="formbusqueda_plan_proyectos_ejes" class="general_form" id="formbusqueda_plan_proyectos_ejes">
                        <input type="hidden" name="planproyectocod" id="planproyectocod"  value="<?php echo $planproyectocod;?>" />
                    </form> 
                    
                     <form action="plan_proyectos_am.php" method="post" name="formalta_plan_proyectos_ejes" class="general_form" id="formalta_plan_proyectos_ejes">
                        <input type="hidden" name="planproyectocod" id="planproyectocod"  value="<?php echo $planproyectocod;?>" />
                        <div class="col-md-5 col-xs-12 col-sm-12">
                        <select class="form-control input-md" name="planejecod" id="planejecod">
                                    <option value="">Selecione un eje...</option>
                                    
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_ejes)){?>
                                <option value="<?php echo $filaCombo['planejecod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planejenombre'],ENT_QUOTES);?></option>
                            <?php }?>
                        </select>
                        </div>
                        <div class="col-md-5 col-xs-12 col-sm-12">
                            <div class="ancho_boton aire" style="margin-top: -8px;"><a class="boton verde" href="javascript:void(0)" onclick="AgregarEjes()">Agregar Eje</a></div>
                        </div>
                         <div class="clearboth">&nbsp;</div>
                     </form> 
                <div class="clear" style="height:1px;">&nbsp;</div>
                <div id="LstDatos_plan_proyectos_ejes" style="width:100%;">
                       <table id="listarDatos_plan_proyectos_ejes"></table>
                    <div id="pager_plan_proyectos_ejes"></div>
                </div>
                </div>
                
                
                <!--  Tags -->
                <? 
					$oPlanTags = new cPlanTags($conexion);
					$result_tags['plantagestado'] = ACTIVO;
					if(!$oPlanTags->BusquedaAvanzada($result_tags,$result_tags,$numfilas_tags))
						return false;
				 
				
				?>
                <script type="text/javascript" src="modulos/plan_proyectos_tags/js/plan_proyectos_tags.js"></script>
                <div class="panel-style space">
                <div class="inner-page-title" style="padding-bottom:2px;">
                    <h2>Tags</h2>
                </div>
                <div class="clear fixalto">&nbsp;</div>
                    <form action="plan_proyectos_am.php" method="post" name="formbusqueda_plan_proyectos_tags" class="general_form" id="formbusqueda_plan_proyectos_tags">
                        <input type="hidden" name="planproyectocod" id="planproyectocod"  value="<?php echo $planproyectocod;?>" />
                    </form> 
                    
                     <form action="plan_proyectos_am.php" method="post" name="formalta_plan_proyectos_tags" class="general_form" id="formalta_plan_proyectos_tags">
                        <input type="hidden" name="planproyectocod" id="planproyectocod"  value="<?php echo $planproyectocod;?>" />
                        <div class="col-md-5 col-xs-12 col-sm-12">
                        <select class="form-control input-md" name="plantagcod" id="plantagcod">
                                    <option value="">Selecione un tags...</option>
                                    
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_tags)){?>
                                <option value="<?php echo $filaCombo['plantagcod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['plantagnombre'],ENT_QUOTES);?></option>
                            <?php }?>
                        </select>
                        </div>
                        <div class="col-md-5 col-xs-12 col-sm-12">
                            <div class="ancho_boton aire" style="margin-top: -8px;"><a class="boton verde" href="javascript:void(0)" onclick="AgregarTags()">Agregar Tags</a></div>
                        </div>
                         <div class="clearboth">&nbsp;</div>
                     </form> 
                <div class="clear" style="height:1px;">&nbsp;</div>
                <div id="LstDatos_plan_proyectos_tags" style="width:100%;">
                       <table id="listarDatos_plan_proyectos_tags"></table>
                    <div id="pager_plan_proyectos_tags"></div>
                </div>
                </div>
                
                <?php }?>
        </div>
        <div class="clearboth">&nbsp;</div>
    </div>
    <div class="clearboth">&nbsp;</div>
</div>


<?php 
$oEncabezados->PieMenuEmergente();

?>