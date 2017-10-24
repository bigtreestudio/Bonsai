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
$oGraficosColumnas = new cGraficosColumnas($conexion,"");


if (!$oGraficos->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;

if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el grafico por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datosgrafico = $conexion->ObtenerSiguienteRegistro($resultado);	
$graficocod = $datosgrafico['graficocod'];


if (isset($_POST['columnacod']) && $_POST['columnacod']!="")
{
	$esmodif = true;
	if (!$oGraficosColumnas->BuscarxCodigoxGrafico($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Columna.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datos = $conexion->ObtenerSiguienteRegistro($resultado);
}


$botonejecuta = "BtAlta";
$boton = "Alta";
$columnacod="";
$columnatitulo="";
$onclick = "return InsertarColumna();";
if ($esmodif)
{
	
	$columnacod=$datos['columnacod'];
	$columnatitulo=$datos['columnatitulo'];
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$onclick = "return ModificarColumna();";
}
?>
<link rel="stylesheet" type="text/css" href="modulos/gra_graficos/css/gra_graficos.css" />

    <div style="text-align:left">
        <div class="form ">
            <form action="gra_graficos.php" method="post" name="formulariocolumnas" id="formulariocolumnas" >
                <div class="datosgenerales">
                    <div>
                        <label>Titulo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="columnatitulo"  id="columnatitulo" class="full" value="<?php  echo $columnatitulo?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="graficocod" id="graficocod" value="<?php  echo $graficocod?>" />
                <input type="hidden" name="columnacod" id="columnacod" value="<?php  echo $columnacod?>" />
        
            </form>
        </div>
    </div>
