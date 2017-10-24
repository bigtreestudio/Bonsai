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

header('Content-Type: text/html; charset=iso-8859-1');

$oProvincias = new cProvincias($conexion);

$esmodif = false;

	if(isset($_POST['provinciacod']) && $_POST['provinciacod']!="")
	{
	if (!$oProvincias->BuscarxCodigo($_POST,$resultadoprov,$numfilasprov))
		return false;
	if ($numfilasprov!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
}
$datosprovincia = $conexion->ObtenerSiguienteRegistro($resultadoprov);
$provinciacod = $datosprovincia['provinciacod'];

$oDepartamentos = new cDepartamentos($conexion,$provinciacod,"");

if (isset($_POST['departamentocod']) && $_POST['departamentocod']!="")
{
	$esmodif = true;
	if (!$oDepartamentos->BuscarDepartamentoxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - departamento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosdepartamento = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$departamentocod = "";
$departamentodesc = "";
$onclick = "return InsertarDepartamento();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$departamentocod = $datosdepartamento['departamentocod'];
	$departamentodesc = $datosdepartamento['departamentodesc'];
	$onclick = "return ModificarDepartamento();";
}
?>
<script type="text/javascript" language="javascript">
</script>

    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="ciudades.php?provinciacod=<?php  echo $provinciacod?>" method="post" name="formciudades" id="formciudades" >
                <div class="datosgenerales">
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" size="80" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($departamentodesc,ENT_QUOTES)?>" name="departamentodesc" id="departamentodesc" class="full" />
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>    
                    <div class="menubarra">
                        <ul>
                            <li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" value="<?php  echo $provinciacod?>" name="provinciacod" id="provinciacod" />
                <input type="hidden" value="<?php  echo $departamentocod?>" name="departamentocod" id="departamentocod" />
        
            </form>
        </div>
    </div>
