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

$oFormulariosTipos = new cFormulariosTipos($conexion);

header('Content-Type: text/html; charset=iso-8859-1');

$esmodif = false; 
$esmodif = false;
if (isset($_POST['formulariotipocod']) && $_POST['formulariotipocod'])
{
	$esmodif = true;
	if (!$oFormulariosTipos->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datostiposformularios = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$formulariotipocod = "";
$formulariotipodesc = "";
$onclick = "return Insertar();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$formulariotipocod = $datostiposformularios['formulariotipocod'];
	$formulariotipodesc = $datostiposformularios['formulariotipodesc'];
	$onclick = "return Modificar();";
}
?>
<script type="text/javascript" language="javascript">
</script>

    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="con_formularios_tipos_am.php" method="post" name="formtiposformularios" id="formtiposformularios" >
                <div class="datosgenerales">
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" size="80" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariotipodesc,ENT_QUOTES)?>" name="formulariotipodesc" id="formulariotipodesc"  maxlength="255" class="full" />
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>    
                    <div class="menubarra">
                        <ul>
                            <li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" value="<?php  echo $formulariotipocod?>" name="formulariotipocod" id="formulariotipocod" />
        
            </form>
        </div>
    </div>
