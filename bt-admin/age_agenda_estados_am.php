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

$oAgendaEstados = new cAgendaEstados($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

if (isset($_POST['agendaestadocod']) && $_POST['agendaestadocod'])
{
	$esmodif = true;
	if (!$oAgendaEstados->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosestados = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$agendaestadocod = "";
$agendaestadodesc = "";
$agendaestadoestado= "";
$agendaestadocolor= "";
$agendaestadocte= "";
$onclick = "return Insertar();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$agendaestadocod = $datosestados['agendaestadocod'];
	$agendaestadodesc = $datosestados['agendaestadodesc'];
	$agendaestadoestado= $datosestados['agendaestadoestado'];
	$agendaestadocolor= $datosestados['agendaestadocolor'];
	$agendaestadocte= $datosestados['agendaestadocte'];
	$onclick = "return Modificar();";
}

?>
<link type="text/css" rel="stylesheet" href="css/jquery.miniColors.css" />
<script type="text/javascript" src="js/jquery.miniColors.min.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	$("#agendaestadocolor").miniColors();
	
});
</script>
<div style="text-align:left">
	<div class="form ">
		<form action="age_agenda_estados_am.php" method="post" name="formagendaestado" id="formagendaestado" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="agendaestadodesc"  id="agendaestadodesc" maxlength="50" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($agendaestadodesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Constante:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="agendaestadocte" id="agendaestadocte" maxlength="20" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($agendaestadocte,ENT_QUOTES)?>"/>
    			</div>
                <div class="clearboth aire_menor">&nbsp;</div>  
                <div>
                	<label>Color</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
            		<input type="text" value="<?php  echo $agendaestadocolor?>" id="agendaestadocolor" name="agendaestadocolor" maxlength="7" size="10" />(Hexadecimal)
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
    
				<div class="menubarra">
    				<ul>
        				<li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $agendaestadocod?>" name="agendaestadocod" id="agendaestadocod" />
		</form>
	</div>
</div>
