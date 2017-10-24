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
$oObjeto = new cGcbaComunaBarrios($conexion);
if(!$oObjeto->gcba_comunasSPResult($result_gcba_comunas,$numfilas_gcba_comunas))
	return false;
if(!$oObjeto->gcba_barriosSPResult($result_gcba_barrios,$numfilas_gcba_barrios))
	return false;
?>
<script type="text/javascript" src="modulos/gcba_comunas_barrios/js/gcba_comunas_barrios.js"></script>
	
<div class="form">
    <form action="gcba_comunas_barrios.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h2></h2>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            							<div class="form-group clearfix">
													                            
								<div class="col-md-4">
									<label>Codigo:</label>
									<input name="comunabarriocod" id="comunabarriocod" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['comunabarriocod'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['comunabarriocod'],ENT_QUOTES) : '';?>" />
								</div>
																				                            <div class="col-md-4">
                                    <label>Comuna:</label>
									<select class="form-control input-md" name="comunacod" id="comunacod">
                        				<option value="">Todos...</option>
                                        
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_gcba_comunas)){?>
								<option <?php if (isset($_SESSION['BusquedaAvanzada']['comunacod']) && $filaCombo['comunacod']==$_SESSION['BusquedaAvanzada']['comunacod']) echo 'selected="selected"'?> value="<?php echo $filaCombo['comunacod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['comunanumero'],ENT_QUOTES);?></option>
							<?php }?>
									</select>

								</div>
                            													                            <div class="col-md-4">
                                    <label>Barrio:</label>
									<select class="form-control input-md" name="barriocod" id="barriocod">
                        				<option value="">Todos...</option>
                                        
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_gcba_barrios)){?>
								<option <?php if (isset($_SESSION['BusquedaAvanzada']['barriocod']) && $filaCombo['barriocod']==$_SESSION['BusquedaAvanzada']['barriocod']) echo 'selected="selected"'?> value="<?php echo $filaCombo['barriocod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['barrionombre'],ENT_QUOTES);?></option>
							<?php }?>
									</select>

								</div>
                            						                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
							        </div>
            </form>   
</div> 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra pull-left">
    <ul>
    	<li><div class="ancho_boton aire"><a class="boton verde" href="gcba_comunas_barrios_am.php">Crear nuevo </a></div></li>
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