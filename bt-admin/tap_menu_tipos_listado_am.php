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

$oTapasMenuTipos = new cTapasMenuTipos($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['menutipocod']) && $_POST['menutipocod'])
{
	$esmodif = true;
	if (!$oTapasMenuTipos->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosmenutipos = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$menutipocod = "";
$menutipodesc = "";
$menutipocte= "";
$menuanchoautomatico= "";
$menutipoarchivo= "";
$menuclass= "";
$onclick = "return Insertar();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$menutipocod = $datosmenutipos['menutipocod'];
	$menutipodesc = $datosmenutipos['menutipodesc'];
	$menutipocte= $datosmenutipos['menutipocte'];
	$menuanchoautomatico= $datosmenutipos['menuanchoautomatico'];
	$menutipoarchivo= $datosmenutipos['menutipoarchivo'];
	$menuclass= $datosmenutipos['menuclass'];
	$onclick = "return Modificar();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="aire_vertical ">
		<form action="mul_multimedia_formatos.php" method="post" name="formmenutipo" id="formmenutipo" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="menutipodesc"  id="menutipodesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menutipodesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Constante:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="menutipocte" id="menutipocte" maxlength="80" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menutipocte,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div> 
                <div>
    				<label>Ancho Automatico:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="menuanchoautomatico" id="menuanchoautomatico" maxlength="80" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menuanchoautomatico,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>
                <div>
    				<label>Archivo:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="menutipoarchivo" id="menutipoarchivo" maxlength="80" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menutipoarchivo,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>
                <div>
    				<label>Class:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="menuclass" id="menuclass"  maxlength="10" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menuclass,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $menutipocod?>" name="menutipocod" id="menutipocod" />
		</form>
	</div>
</div>
