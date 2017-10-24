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

$oProvincias = new cProvincias($conexion,"");


$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['provinciacod']) && $_POST['provinciacod'])
{
	$esmodif = true;
	if (!$oProvincias->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosprovincia = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$titulo = "Alta de Provincia";
$provinciacod = "";
$provinciadesc = "";
$onclick = "return InsertarProvincia();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$titulo = "Modificación de Provincia";
	$provinciacod = $datosprovincia['provinciacod'];
	$provinciadesc = $datosprovincia['provinciadesc'];
	$onclick = "return ModificarProvincia();";
}
?>
<script type="text/javascript" language="javascript">
</script>

    <div style="text-align:left">
        <div class="form">
            <form action="provincias.php" method="post" name="formulario" id="formulario" >
                <div class="datosgenerales">
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($provinciadesc,ENT_QUOTES)?>" name="provinciadesc" size="80" id="provinciadesc" class="full" />
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>    
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" value="<?php  echo $provinciacod?>" name="provinciacod" id="provinciacod" />
        
            </form>
        </div>
    </div>
