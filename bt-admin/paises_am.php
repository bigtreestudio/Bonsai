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

$oPaises = new cPaises($conexion);

header('Content-Type: text/html; charset=iso-8859-1');

$esmodif = false; 
if (isset($_POST['paiscod']) && $_POST['paiscod'])
{
	$esmodif = true;
	if (!$oPaises->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datospais = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$titulo = "Alta de Pais";
$paiscod = "";
$paisdesc = "";
$paisestado = "";
$onclick = "return InsertarPais();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$titulo = "Modificación de Pais";
	$paiscod = $datospais['paiscod'];
	$paisdesc = $datospais['paisdesc'];
	$paisestado = $datospais['paisestado'];
	$onclick = "return ModificarPais();";
}
?>


    <div style="text-align:left">
        <div class="form ">
            <form action="feriados.php" method="post" name="formferiados" id="formferiados" >
                <div class="datosgenerales">
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" size="80" value="<?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($paisdesc,ENT_QUOTES)?>" name="paisdesc" id="paisdesc" class="full" />
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>    
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" name="<?php echo $botonejecuta?>" value="<?php echo $boton?>" href="javascript:void(0)"  onclick="<?php echo $onclick?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $paiscod?>" name="paiscod" id="paiscod" />
        
            </form>
        </div>
    </div>
