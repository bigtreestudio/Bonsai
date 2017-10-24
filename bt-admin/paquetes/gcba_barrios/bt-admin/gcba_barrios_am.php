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
$oObjeto = new cGcbaBarrios($conexion);
$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$barriocod = "";
$barrionombre = "";
$barriodesc = "";
$barrioestado = "";
if (isset($_GET['barriocod']) && $_GET['barriocod']!="")
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
	$barriocod = $datosregistro["barriocod"];
	$barrionombre = $datosregistro["barrionombre"];
	$barriodesc = $datosregistro["barriodesc"];
	$barrioestado = $datosregistro["barrioestado"];
}
?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js"></script>
<script type="text/javascript" src="modulos/gcba_barrios/js/gcba_barrios_am.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Barrios</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
	<div class="form">
   		
        <div class="col-md-7 col-xs-12 col-sm-6">
			<form action="gcba_barrios.php" method="post" name="formalta" id="formalta" >
				
			<div class="form-group clearfix"><label for="barrionombre">Nombre</label>
			<input type="text" class="form-control input-md" maxlength="255" name="barrionombre" id="barrionombre" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($barrionombre,ENT_QUOTES)?>" />
			
			
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            			<div class="form-group clearfix"><label for="barriodesc">Descripci&oacute;n</label>
			<textarea class="form-control input-md" rows="6" cols="20" name="barriodesc" id="barriodesc"><?php   echo $barriodesc?></textarea>
			
			
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                                
					
                    <input type="hidden" name="barriocod" id="barriocod" value="<?php   echo $barriocod?>" />
                        
                	<div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="<?php   echo $onclick ?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="gcba_barrios.php">Volver</a></div></li>
                        	                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                    <div class="msgaccionupd">&nbsp;</div>
                    <div class="menubarra pull-right">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton azul" href="gcba_barrios_am.php">Crear nuevo </a></div></li>
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