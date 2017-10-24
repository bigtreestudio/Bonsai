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

$usuarios = new cUsuarios($conexion);

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = $_SESSION['msgactualizacion'];
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
	$ArregloDatos['usuariocod']=$_SESSION['usuariocod'];
	if (!$usuarios->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
	{	
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	
	$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
	
	
	$filausuario['usuariofnacimiento'] = FuncionesPHPLocal::ConvertirFecha($filausuario['usuariofnacimiento'],'aaaa-mm-dd','dd/mm/aaaa');
?>

<script type="text/javascript">
	var mailusuario	= "<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarioemail'],ENT_QUOTES);?>";
	var usuariocod = "<?php echo $_SESSION['usuariocod'];?>";
	jQuery(document).ready(function(){CargarDatosPersonales()});
</script>
<script type="text/javascript" src="js/archivos/usuarios.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Mi cuenta</h2>
</div>
<div class="form">
	<div class="col-md-12">
		<form action="usuarios_modificar.php" method="post" class="general_form" name="formulario" id="formulario">
    		<div id="DatosPersonales"></div>   
			<input type="hidden" id="accion" name="accion" value="1" />
		</form>
	</div>
	<div class="row">
		<span class="obligatorio">(*)</span> Campos Obligatorios.
	</div>
    <div class="clearboth brisa_vertical">&nbsp;</div>
	<div id="MsgAccionDatos"></div>
	<div class="menubarraInferior">
		<div class="menubarra">
			<ul>
				<li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onClick="ModificarDatosPersonales()">Guardar Modificaciones</a></div></li>
			</ul>
		</div>    
		 
	</div>
   
</div>        
<div style="height:50px; clear:both">&nbsp;</div>
<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>