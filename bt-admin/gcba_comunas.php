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
$oObjeto = new cGcbaComunas($conexion);
?>
<script type="text/javascript" src="modulos/gcba_comunas/js/gcba_comunas.js"></script>
	
<div class="form">
    <form action="gcba_comunas.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h2>Comunas</h2>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            							<div class="form-group clearfix">
													                            
								<div class="col-md-4">
									<label>C&oacute;digo:</label>
									<input name="comunacod" id="comunacod" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['comunacod'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['comunacod'],ENT_QUOTES) : '';?>" />
								</div>
																				                            
								<div class="col-md-4">
									<label>N&uacute;mero:</label>
									<input name="comunanumero" id="comunanumero" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['comunanumero'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['comunanumero'],ENT_QUOTES) : '';?>" />
								</div>
																				                            
								<div class="col-md-4">
									<label>Barrios:</label>
									<input name="comunabarrios" id="comunabarrios" class="form-control input-md" type="text"  maxlength="255" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['comunabarrios'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['comunabarrios'],ENT_QUOTES) : '';?>" />
								</div>
													                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
							        </div>
        			<input type="hidden" name="comunaestado" id="comunaestado" value="<?php echo ACTIVO.",".NOACTIVO ?>" /> 	
		    </form>   
</div> 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra pull-left">
    <ul>
    	<li><div class="ancho_boton aire"><a class="boton verde" href="gcba_comunas_am.php">Crear nuevo Comunas</a></div></li>
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