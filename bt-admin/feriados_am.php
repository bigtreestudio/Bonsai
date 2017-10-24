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

$oFeriados = new cFeriados($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['feriadocod']) && $_POST['feriadocod'])
{
	$esmodif = true;
	if (!$oFeriados->BuscarFeriadosxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosferiados = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$titulo = "Alta de Feriado";
$feriadocod = "";
$feriadodia = "";
$feriadodesc = "";
$onclick = "return InsertarFeriados();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$titulo = "Modificación del Feriado";
	$feriadocod = $datosferiados['feriadocod'];
	$feriadodia = FuncionesPHPLocal::ConvertirFecha($datosferiados['feriadodia'],"aaaa-mm-dd","dd/mm/aaaa");
	$feriadodesc= $datosferiados['feriadodesc'];
	$onclick = "return ModificarFeriados();";
}

?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function(){$( "#feriadodia" ).datepicker( {dateFormat:"dd/mm/yy",changeMonth: true,changeYear: true, yearRange: "-120:+0",})});
</script>

    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="feriados.php" method="post" name="formferiados" id="formferiados" >
                <div class="datosgenerales">
                    <div>
                        <label>Descripci&oacute;n:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($feriadodesc,ENT_QUOTES)?>" name="feriadodesc" size="80" id="feriadodesc" class="full" />
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Fecha del Feriado:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                         <div>
                         	 <input type="text" name="feriadodia" id="feriadodia" size="20" maxlength="10" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($feriadodia,ENT_QUOTES)?>"/>
                        </div>
                    <div class="clearboth aire_menor">&nbsp;</div>    
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></</div></li>
                            <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" value="<?php  echo $feriadocod?>" name="feriadocod" id="feriadocod" />
        
            </form>
        </div>
    </div>
