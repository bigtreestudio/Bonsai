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

if(!$oObjeto->plan_tags_categoriasSPResult($result_plan_tags_categorias,$numfilas_plan_tags_categorias))
	return false;
$datos_plan_ejes['planejeestado'] = ACTIVO;	
if(!$oPlanEjes->BusquedaAvanzada($datos_plan_ejes,$result_plan_ejes,$numfilas_plan_plan_ejes))
	return false;	
?>
<script type="text/javascript" src="modulos/plan_tags/js/plan_tags.js"></script>
	
<div class="form">
    <form action="plan_tags.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h2>Tags</h2>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            		<div class="form-group clearfix">
													                            
								<div class="col-md-4">
									<label>C&oacute;digo:</label>
									<input name="plantagcod" id="plantagcod" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['plantagcod'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['plantagcod'],ENT_QUOTES) : '';?>" />
								</div>
																				                            
								<div class="col-md-4">
									<label>Nombre:</label>
									<input name="plantagnombre" id="plantagnombre" class="form-control input-md" type="text"  maxlength="255" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['plantagnombre'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['plantagnombre'],ENT_QUOTES) : '';?>" />
								</div>
								<div class="col-md-4">
                                    <label>Categor&iacute;a:</label>
									<select class="form-control input-md" name="plantagcatcod" id="plantagcatcod">
                        				<option value="">Todos...</option>
											<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_tags_categorias)){?>
                                                <option <?php if (isset($_SESSION['BusquedaAvanzada']['plantagcatcod']) && $filaCombo['plantagcatcod']==$_SESSION['BusquedaAvanzada']['plantagcatcod']) echo 'selected="selected"'?> value="<?php echo $filaCombo['plantagcatcod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['plantagcatnombre'],ENT_QUOTES);?></option>
                                            <?php }?>
									</select>
								</div>
                       </div>
                       <div class="clearboth brisa_vertical">&nbsp;</div>
					   <div class="form-group clearfix">
								<div class="col-md-4">
                                    <label>Eje:</label>
									<select class="form-control input-md" name="planejecod" id="planejecod">
                        				<option value="">Todos...</option>
                                        
										<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_ejes)){?>
                                            <option <?php if (isset($_SESSION['BusquedaAvanzada']['planejecod']) && $filaCombo['planejecod']==$_SESSION['BusquedaAvanzada']['planejecod']) echo 'selected="selected"'?> value="<?php echo $filaCombo['planejecod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planejenombre'],ENT_QUOTES);?></option>
                                        <?php }?>
									</select>
								</div>
                       </div>
      </div>                 
        			<input type="hidden" name="plantagestado" id="plantagestado" value="<?php echo ACTIVO.",".NOACTIVO ?>" /> 	
		    </form>   
</div> 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra pull-left">
    <ul>
    	<li><div class="ancho_boton aire"><a class="boton verde" href="plan_tags_am.php">Crear nuevo Tags</a></div></li>
        <li><div class="ancho_boton aire"><a class="boton azul" href="javascript:void(0)" onclick="gridReload()">Buscar</a></div></li>
    	<li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar</a></div></li>
    </ul>
</div>


<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstDatos" style="width:100%;">
       <table id="listarDatos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
	
<?php 
$oEncabezados->PieMenuEmergente();

?>