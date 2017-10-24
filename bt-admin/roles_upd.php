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

$roles=new cRoles($conexion);
?>
<div class="onecolumn">
    <div class="header">
        <span>Roles</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />

<?php 
if(isset($_POST['botonalta']) && $_POST["rolcodviejo"]=="")
{
	$conexion->ManejoTransacciones("B");

	if($roles->Insertar($_POST,$rolcod))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"033501","rolcod=".$rolcod,$_SESSION['usuariocod']);
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha agregado el rol con código '".$rolcod."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));

		$conexion->ManejoTransacciones("C");
	}
	else
		$conexion->ManejoTransacciones("R");
}
elseif(isset($_POST['botonalta']) && $_POST["rolcodviejo"]!="")
{
	$conexion->ManejoTransacciones("B");

	if($roles->Modificar($_POST,$_POST["rolcodviejo"]))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"033503","rolcod=".$_POST["rolcodviejo"],$_SESSION['usuariocod']);
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha actualizado el rol '".$_POST["rolcodviejo"]."'",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));

		$conexion->ManejoTransacciones("C");
	}
	else
		$conexion->ManejoTransacciones("R");
}
elseif(isset($_POST['botonbaja']))
{
	$conexion->ManejoTransacciones("B");

	if($roles->Eliminar($_POST["rolcodviejo"]))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"033502","rolcod=".$_POST["rolcodviejo"],$_SESSION['usuariocod']);
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha borrado el rol '".$_POST["rolcodviejo"]."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));

		$conexion->ManejoTransacciones("C");
	}
	else
		$conexion->ManejoTransacciones("R");
}

?>		
		<br /><br /><br /><div align="center"><a href="roles.php" class="linkfondoblanco">Volver</a></div>
    </div>
</div>        
<div style="height:20px; clear:both">&nbsp;</div>
<?php 

$oEncabezados->PieMenuEmergente();
?>
