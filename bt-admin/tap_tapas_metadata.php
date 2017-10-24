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


$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

$oTapas= new cTapas($conexion);

if (!isset($_POST['tapacod']) || $_POST['tapacod']=="")
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Tapa inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$tapacod= $_POST['tapacod'];
if (!$oTapas->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Tapa inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datostapas = $conexion->ObtenerSiguienteRegistro($resultado);	

if ($datostapas['tapametadata']!="")
	$objDataModel = json_decode($datostapas['tapametadata'],1);
	
$oMetadataCampos = new cTapasTiposMetadataCampos($conexion);
if(!$oMetadataCampos->BuscarCamposActivos($resultado,$numfilas))
	return false;
	
	
?>
    <div style="text-align:left">
        <div class="form ">
            <form action="tap_tapas.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <h2 style="font-weight:bold !important; font-size:14px; margin:5px 0 5px 0 !important"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datostapas['tapanom'],ENT_QUOTES);?></h2>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <?php  while($fila = $conexion->ObtenerSiguienteRegistro($resultado)){?>
                        <div>
                            <label><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tapatipometadatacampo'],ENT_QUOTES);?>:</label>
                        </div>
                        <div>
                            <input type="text" class="full" value="<?php  if (isset($objDataModel[$fila['tapatipometadatacte']])) {echo utf8_decode($objDataModel[$fila['tapatipometadatacte']]);}?>" name="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tapatipometadatacte'],ENT_QUOTES)?>" id="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tapatipometadatacte'],ENT_QUOTES)?>" />
                        </div>
                        <div class="clearboth aire_menor">&nbsp;</div>
                    <?php  }?>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left boton verde" href="javascript:void(0)"  onclick="GuardarMetadata()">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="tapacod" id="tapacod" value="<?php  echo $tapacod?>" />
            </form>
        </div>
    </div>
