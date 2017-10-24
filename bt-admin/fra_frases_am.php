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

$oFrases= new cFrases($conexion);

if (isset($_POST['frasecod']) && $_POST['frasecod']!="")
{
	$frasecod=$_POST['frasecod'];
	$esmodif = true;
	if (!$oFrases->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Frase inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosfrases = $conexion->ObtenerSiguienteRegistro($resultado);	

}

$botonejecuta = "BtAlta";
$boton = "Alta";
$fraseorden="";
$frasecod="";
$fraseautor = "";
$frasedesclarga ="";
$fraseestado = "";
$fraseurl="";
$onclick = "return InsertarFrases();";

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$frasecod=$datosfrases['frasecod'];
	$fraseautor=$datosfrases['fraseautor'];
	$frasedesclarga=$datosfrases['frasedesclarga'];
	$fraseestado=$datosfrases['fraseestado'];
	$onclick = "return ModificarFrases();";
}
?>
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />

    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="tap_tapas.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Autor:</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <input type="text" name="fraseautor"  id="fraseautor" class="full" value="<?php  echo $fraseautor?>" size="90" maxlength="255">
                    </div>
                   
                   <div class="clear aire_vertical">&nbsp;</div>
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <input type="text" name="frasedesclarga"  id="frasedesclarga" class="full" value="<?php  echo $frasedesclarga?>" size="90" maxlength="255">
                    </div>                    
                    <div class="menubarra">
                        <ul>
                            <li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="frasecod" id="frasecod" value="<?php  echo $frasecod?>" />

                <input type="hidden" name="fraseestado" id="fraseestado" value="<?php  echo $fraseestado?>" />
                <input type="hidden" name="fraseurl" id="fraseurl" value="<?php  echo $fraseurl?>" />
                <input type="hidden" name="fraseorden" id="fraseorden" value="<?php  echo $fraseorden?>" />

            </form>
        </div>
    </div>
