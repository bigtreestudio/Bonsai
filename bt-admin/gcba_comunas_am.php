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
$ooObjeto = new cGcbaComunaBarrios($conexion);

$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$comunacod = "";
$comunanumero = "";
$comunabarrios = "";
$comunaperimetro = "";
$comunaarea = "";
$comunapoligono = "";
$comunaestado = "";

//cGcbComunaBarrios

$comunabarriocod = "";
$comunacod1 = "";
$barriocod = "";
$ultmodfecha = "";
$ultmodusuario = "";

if (isset($_GET['comunacod']) && $_GET['comunacod']!="")
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
	$comunacod = $datosregistro["comunacod"];
	$comunanumero = $datosregistro["comunanumero"];
	$comunabarrios = $datosregistro["comunabarrios"];
	$comunaperimetro = $datosregistro["comunaperimetro"];
	$comunaarea = $datosregistro["comunaarea"];
	$comunapoligono = $datosregistro["comunapoligono"];
	$comunaestado = $datosregistro["comunaestado"];
}
if(isset($_GET['comunabarriocod']) && $_GET['comunabarriocod']!= '')
{
    $datos1 = $_GET;
    if(!$ooObjeto->BuscarxCodigo($datos1, $resultado1, $numfilas1)){
        return false;
        }
    if($numfilas1!=1){
        FuncionesPHPLocal::MostrarMensaje($conexion, MSG_ERRGRAVE, 'Codigo inexistente.', array("archivo"=>__FILE__, "funcion"=> __FUNCTION__, "linea"=> __LINE__), array("formato"=> _FMT_TEXTO));
        return false;    
        }
        $datosregistro1 = $conexion->ObtenerSiguienteRegistro($resultado1);
	$onclick = "return Modificar();";
	$$comunabarriocod = $datosregistro1["comunabarriocod"];
	$comunacod1 = $datosregistro1["comunacod"];
	$barriocod = $datosregistro1["barriocod"];
        $ultmodfecha = FuncionesPHPLocal::ConvertirFecha($datosregistro1["ultmodfecha"],'aaaa-mm-dd','dd/mm/aaaa');
	$ultmodusuario = $datosregistro1["ultmodusuario"];

}
?>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="modulos/gcba_comunas/js/gcba_comunas_am.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Comunas</h2>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
	<div class="form">
   		
        <div class="col-md-7 col-xs-12 col-sm-6">
			<form action="gcba_comunas.php" method="post" name="formalta" id="formalta" >
				
			<div class="form-group clearfix"><label for="comunanumero">N&uacute;mero</label>
			<input type="text" class="form-control input-md" maxlength="11" name="comunanumero" id="comunanumero" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($comunanumero,ENT_QUOTES)?>" />
			
			

                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            			<div class="form-group clearfix"><label for="comunabarrios">Barrios</label>
			<input type="text" class="form-control input-md" maxlength="255" name="comunabarrios" id="comunabarrios" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($comunabarrios,ENT_QUOTES)?>" />
			
			

                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            			<div class="form-group clearfix"><label for="comunaperimetro">Per&iacute;metro</label>
			<input type="text" class="form-control input-md" maxlength="255" name="comunaperimetro" id="comunaperimetro" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($comunaperimetro,ENT_QUOTES)?>" />
			
			

                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            			<div class="form-group clearfix"><label for="comunaarea">&Aacute;rea</label>
			<input type="text" class="form-control input-md" maxlength="255" name="comunaarea" id="comunaarea" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($comunaarea,ENT_QUOTES)?>" />
			
			

                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            			<div class="form-group clearfix"><label for="comunapoligono">Pol&iacute;gono</label>
                                        <textarea class="form-control input-md rich-text" rows="6" cols="20" name="comunapoligono" id="comunapoligono"><?php   echo $comunapoligono?></textarea>
			

                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                                
					
                    <input type="hidden" name="comunacod" id="comunacod" value="<?php   echo $comunacod?>" />
                        
                	<div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="<?php   echo $onclick ?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="gcba_comunas.php">Volver</a></div></li>
                        	                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                    <div class="msgaccionupd">&nbsp;</div>
                    <div class="menubarra pull-right">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton azul" href="gcba_comunas_am.php">Crear nuevo </a></div></li>
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
    
    
    
    <!-- col-md-12 -->
    <div class="col-md-12 col-xs-12 col-sm-12">
		     <?php if($esmodif){
				
				
				 $oGcbaComunasBarrios = new cGcbaComunaBarrios($conexion);

				if(!$oGcbaComunasBarrios->gcba_barriosSPResult($result_gcba_comunasbarrios,$numfilas_gcba_comunasbarrios))
					return false;
				 
				 
				 ?>
				<!--  Comunas -->
                                <script type="text/javascript" src="modulos/gcba_comunas_barrios/js/gcba_comunas_barrios.js"></script>
            <!-- comuna -->
                <div class="panel-style space">
                <div class="inner-page-title" style="padding-bottom:2px;">
                    <h2>Barrios</h2>
                </div>
                <div class="clear fixalto">&nbsp;</div>
                <form action="gcba_comunas_am.php" method="post" name="formbusqueda_gcba_comunasbarrios" class="general_form" id="formbusqueda_gcba_comunasbarrios">
                       <input type="hidden" name="comunacod" id="comunacod"  value="<?php echo $comunacod;?>" />
                </form> 
                    
                <form action="gcba_comunas_am.php" method="post" name="formalta_gcba_comunasbarrios" class="general_form" id="formalta_gcba_comunasbarrios">
                        <input type="hidden" name="comunacod" id="comunacod"  value="<?php echo $comunacod;?>" />
                        
                         <div class="clearboth">&nbsp;</div>
                         <div class="col-md-5 col-xs-12 col-sm-12">
                        <select class="form-control input-md chzn-select" name="barriocod" id="barriocod">
                                    <option value="">Selecione un Barrio...</option>
                                    
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_gcba_comunasbarrios)){?>
                                    <option value="<?php echo $filaCombo['barriocod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['barrionombre'],ENT_QUOTES);?></option>
                            <?php }?>
                        </select>
                        </div>
                        <div class="col-md-5 col-xs-12 col-sm-12">
                            <div class="ancho_boton aire" style="margin-top: -8px;"><a class="boton verde" href="javascript:void(0)" onclick="AgregarBarrio()">Agregar Barrio</a></div>
                        </div>
                         <div class="clearboth">&nbsp;</div>
                     </form> 
                <div class="clear" style="height:1px;">&nbsp;</div>
                <div id="LstDatos_gcba_comunasbarrios" style="width:100%;">
                       <table id="listarDatos_gcba_comunasbarrios"></table>
                    <div id="pager_gcba_comunasbarrios"></div>
                </div>
                </div>
            <?php }?>
    </div>

    
</div>


<?php 
$oEncabezados->PieMenuEmergente();

?>