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

$oLinks = new cLinks($conexion,"");

if (isset($_POST['catcod']) && $_POST['catcod']!="")
{
	$esmodif = true;
	if (!$oLinks->BuscarCategoriaxCatcod($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente - Categoria-Link.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datoscategorias = $conexion->ObtenerSiguienteRegistro($resultado);	

}

$botonejecuta = "BtAlta";
$boton = "Alta";
$catcod="";
$catnom = "";
$catdesc ="";
$catsuperior='';
$catorden = "";
$catestado="";
$onclick = "return InsertarCategorias();";

if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$catcod=$datoscategorias['catcod'];
	$catnom=$datoscategorias['catnom'];
	$catdesc=$datoscategorias['catdesc'];
	$catsuperior=$datoscategorias['catsuperior'];
	$catorden=$datoscategorias['catorden'];
	$catestado=$datoscategorias['catestado'];
	$onclick = "return ModificarCategorias();";
}
?>
<link rel="stylesheet" type="text/css" href="modulos/multimedia/css/estilos.css" />

    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="lin_categorias.php" method="post" name="formulariocategorias_am" id="formulariocategorias_am" >
                <div class="datosgenerales">
                    <div>
                        <label>Nombre</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <input type="text" name="catnom"  id="catnom" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catnom,ENT_QUOTES);?>" size="90" maxlength="255">
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                     <div>
                        <label>Descripci&oacute;n</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <textarea name="catdesc" class="textarea full" id="catdesc" rows="5" cols="40"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catdesc,ENT_QUOTES);?></textarea>
                    </div>
                   <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="catorden" id="catorden" value="<?php  echo $catorden?>" />
                <input type="hidden" name="catestado" id="catestado" value="<?php  echo $catestado?>" />
                <input type="hidden" name="catcod" id="catcod" value="<?php  echo $catcod?>" />
            </form>
        </div>
    </div>
