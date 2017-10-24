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

$oEncuestas= new cEncuestas($conexion);

if (isset($_POST['encuestacod']) && $_POST['encuestacod']!="")
{
	if (!FuncionesPHPLocal::ValidarContenido($conexion,$_POST['encuestacod'],"NumericoEntero"))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar la encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	$esmodif = true;
	if (!$oEncuestas->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Encuesta inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosencuestas = $conexion->ObtenerSiguienteRegistro($resultado);	
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$encuestacod="";
//SACAR CATCOD Y ENCUESTATIPOCOD!
$catcod=1;
$encuestatipocod=1;
$encuestapregunta = "";
$encuestaestado = "";
$onclick = "return InsertarEncuestas();";

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$encuestacod=$datosencuestas['encuestacod'];
	$encuestapregunta=$datosencuestas['encuestapregunta'];
	$encuestatipocod=$datosencuestas['encuestatipocod'];
	$catcod=$datosencuestas['catcod'];
	$encuestaestado=$datosencuestas['encuestaestado'];
	$onclick = "return ModificarEncuestas();";
}
?>
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />

    <div style="text-align:left">
        <div class="form">
            <form action="enc_encuestas.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Pregunta:</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <input type="text" name="encuestapregunta"  id="encuestapregunta" class="full" value="<?php  echo $encuestapregunta?>" size="90" maxlength="255">
                    </div>
                   
                   <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="encuestaestado" id="encuestaestado" value="<?php  echo $encuestaestado?>" />
                <input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod?>" />
                <input type="hidden" name="encuestatipocod" id="encuestatipocod" value="<?php  echo $encuestatipocod?>" />
                <input type="hidden" name="encuestacod" id="encuestacod" value="<?php  echo $encuestacod?>" />
                
            </form>
        </div>
    </div>
