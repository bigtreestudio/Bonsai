<?php  

require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oTiposDocumentos = new cTiposDocumentos($conexion);

header('Content-Type: text/html; charset=iso-8859-1');

$esmodif = false; 
$esmodif = false;
if (isset($_POST['tipodocumentocod']) && $_POST['tipodocumentocod'])
{
	$esmodif = true;
	if (!$oTiposDocumentos->BuscarTiposDocumentoxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datostiposdocumentos = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$tipodocumentocod = "";
$tipodocumentonombre = "";
$tipodocumentoestadocod = "";
$onclick = "return Insertar();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$tipodocumentocod = $datostiposdocumentos['tipodocumentocod'];
	$tipodocumentonombre = $datostiposdocumentos['tipodocumentonombre'];
	$tipodocumentoestadocod = $datostiposdocumentos['tipodocumentoestadocod'];
	$onclick = "return Modificar();";
}
?>
<script type="text/javascript" language="javascript">
</script>

    <div style="text-align:left">
        <div class="form ">
            <form action="tipos_documentos_am.php" method="post" name="formtiposdocumentos" id="formtiposdocumentos" >
                <div class="datosgenerales">
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" size="80" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($tipodocumentonombre,ENT_QUOTES)?>" name="tipodocumentonombre" id="tipodocumentonombre"  maxlength="10" class="full" />
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>    
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" value="<?php  echo $tipodocumentocod?>" name="tipodocumentocod" id="tipodocumentocod" />
        
            </form>
        </div>
    </div>
