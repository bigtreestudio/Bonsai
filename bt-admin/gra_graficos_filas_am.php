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

$oGraficos = new cGraficos($conexion,"");
$oGraficosFilas = new cGraficosFilas($conexion,"");


if (!$oGraficos->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el grafico por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datosgrafico = $conexion->ObtenerSiguienteRegistro($resultado);	
$graficocod = $datosgrafico['graficocod'];


if (isset($_POST['filacod']) && $_POST['filacod']!="")
{
	$esmodif = true;
	if (!$oGraficosFilas->BuscarxCodigoxGrafico($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Fila.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datos = $conexion->ObtenerSiguienteRegistro($resultado);
}


$botonejecuta = "BtAlta";
$boton = "Alta";
$filacod="";
$filatitulo="";
$filacolor="";
$onclick = "return InsertarFila();";
if ($esmodif)
{
	
	$filacod=$datos['filacod'];
	$filatitulo=$datos['filatitulo'];
	$filacolor=$datos['filacolor'];
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$onclick = "return ModificarFila();";
}
?>
<link rel="stylesheet" type="text/css" href="modulos/gra_graficos/css/gra_graficos.css" />
<script type="text/javascript">
	$(document).ready( function() {
		$("#filacolor").miniColors();
	});
</script>
    <div style="text-align:left">
        <div class="form ">
            <form action="gra_graficos.php" method="post" name="formulariofilas" id="formulariofilas" >
                <div class="datosgenerales">
                    <div>
                        <label>Titulo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="filatitulo"  id="filatitulo" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filatitulo,ENT_QUOTES)?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Color (Hexadecimal):</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div class="ancho_10">
                    	<div class="ancho_2">
	                        <div>
    	                        <input type="text" name="filacolor" style="" id="filacolor"  value="<?php  echo $filacolor?>" size="40" maxlength="10">
        	                </div>
            	            <div>
                	            <span style="font-size:11px; float:left; color:#666">Ej: #CCCCCC</span>
                    	    </div>
                        </div>    
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div class="menubarra" >
                        <ul>
                            <li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="graficocod" id="graficocod" value="<?php  echo $graficocod?>" />
                <input type="hidden" name="filacod" id="filacod" value="<?php  echo $filacod?>" />
        
            </form>
        </div>
    </div>
