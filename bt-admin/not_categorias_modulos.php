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

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oCategorias = new cCategorias($conexion,"");

if (!isset($_GET['catcod']))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
if (!$oCategorias->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
$datoscategorias = $conexion->ObtenerSiguienteRegistro($resultado);
$catcod = $datoscategorias['catcod'];

?>
<link href="modulos/not_categorias/css/categorias_modulos.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/not_categorias/js/categorias_modulos.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Widgets de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscategorias['catnom'],ENT_QUOTES)?></h2>
</div>
<div class="clearboth">&nbsp;</div>
<div class="ancho_6">
<form action="not_categorias_modulos.php" method="post" name="formcategoria">
	<input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod?>" />
    <h2 style="font-size:14px; font-weight:bold;">Categor&iacute;as de Widgets</h2>
    <?php  
		$datos['modulotipocod'] = 2;
		$oTapasModulosCategorias= new cTapasModulosCategorias($conexion);
		$oTapasModulosCategorias->BuscarSPxTipo($datos, $spnombre,$sparam);
		FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","catcodModulo","catcod","catdesc","","Seleccione una categoria...",$regactual,$seleccionado,1,"CargarModulo($(this).val())",false,false);

    ?>
    <div class="clear aire_vertical">&nbsp;</div>
    <?php  
       ?>
    <div class="ancho_4">
        <h2 style="font-size:14px; font-weight:bold;">Widgets a asignar</h2>
        <div id="Modulos" style="background:#FFC; border:2px #000 dashed; min-height:50px;">
            &nbsp;
        </div>
    </div>   
    <div class="ancho_05">&nbsp;</div>
    <div class="ancho_4">
        <h2 style="font-size:14px; font-weight:bold;">Widgets Asignados</h2>
        <div>
            <div id="sortable" style="list-style:none; border:1px solid #CCC; min-height:20px; background:#E6F7FF; border:2px #000 dashed; min-height:50px;">

            </div>
        </div>
    </div>
    <div class="clear aire_vertical">&nbsp;</div>
    <div class="menubarra">
        <ul class="accionespagina">
            <li class="states botonesaccion"><a class="left" href="not_categorias.php">Volver</a></li>
            <li class="states botonesaccion"><a class="left" href="javascript:void(0)" onclick="Publicar()">Publicar</a></li>
        </ul>
    </div>
    <div class="clear fixalto">&nbsp;</div>
	<div id="PopupModulo"></div>
</form>
</div>
<div class="clearboth">&nbsp;</div>

<?php  
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>