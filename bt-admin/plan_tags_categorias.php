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

$oObjeto = new cPlanTagsCategorias($conexion);

?>
<script type="text/javascript" src="modulos/plan_tags_categorias/js/plan_tags_categorias.js"></script>
	
<div class="form">
    <form action="plan_tags_categorias.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h2>Categor&iacute;as de Tags</h2>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            							<div class="form-group clearfix">
													                            
								<div class="col-md-4">
									<label>C&oacute;digo:</label>
									<input name="plantagcatcod" id="plantagcatcod" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['plantagcatcod'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['plantagcatcod'],ENT_QUOTES) : '';?>" />
								</div>
																				                            
								<div class="col-md-4">
									<label>Nombre:</label>
									<input name="plantagcatnombre" id="plantagcatnombre" class="form-control input-md" type="text"  maxlength="255" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['plantagcatnombre'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['plantagcatnombre'],ENT_QUOTES) : '';?>" />
								</div>
													        </div>
     </div>                                                       
        			<input type="hidden" name="plantagcatestado" id="plantagcatestado" value="<?php echo ACTIVO.",".NOACTIVO ?>" /> 	
		    </form>   
</div> 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra pull-left">
    <ul>
    	<li><div class="ancho_boton aire"><a class="boton verde" href="plan_tags_categorias_am.php">Crear nueva Categor�a de Tags</a></div></li>
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