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

$oRoles_Modulos=new cRolesModulos ($conexion);
?>
<div class="onecolumn">
    <div class="header">
        <span>Roles - M&oacute;dulos</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />

<?php 
//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,&$datosvalidados)
{
	$datosvalidados=array();
	
	if($_POST['rolcod']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la selección del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}

	if(!$conexion->BuscarRegistroxClave("sel_roles_orden",array("porderby"=>"rolcod"),array("rolcod"=>$_POST['rolcod']),$resultado,$filaret,$numfilasmatcheo,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	if($numfilasmatcheo!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosvalidados["rolcod"]=$_POST['rolcod'];

	if(isset($_POST['listamodulosder']))
		$arrayfinal=$_POST['listamodulosder'];
	else
		$arrayfinal=array();

	$arrayinicial=array();
	if(!$conexion->ejecutarStoredProcedure("sel_modulos_xrolcod_orden",array('prolcod'=>$datosvalidados["rolcod"],'porderby'=>"modulocod"),$resultado,$numfilas,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al seleccionar los módulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	while($filamodulos=$conexion->ObtenerSiguienteRegistro($resultado))
		$arrayinicial[]=$filamodulos['modulocod'];

	$arraysacar=array_diff($arrayinicial,$arrayfinal);
	$arrayponer=array_diff($arrayfinal,$arrayinicial);

	$datosvalidados["modulossacar"]=$arraysacar;
	$datosvalidados["modulosponer"]=$arrayponer;
	
	return true;
}

//----------------------------------------------------------------------------------------- 	

	$result=true;
	if(isset($_POST['botonalta']) && !Validarphp($conexion,$datosvalidados))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","",$_SESSION['usuariocod']);	
		$result=false;
	}

	if($result && isset($_POST['botonalta']))
	{
		$conexion->ManejoTransacciones("B");
		
		foreach($datosvalidados["modulosponer"] as $modulocod)
		{
			$ArregloDatos=array('rolcod'=>$datosvalidados['rolcod'],'modulocod'=>$modulocod);
			if (!$oRoles_Modulos->Insertar ($ArregloDatos))
				$result=false;
			else
				FuncionesPHPLocal::RegistrarAcceso($conexion,'033601',$datosvalidados['rolcod']."-".$modulocod,$_SESSION['usuariocod']);
		}

		if($result)
		{
			foreach($datosvalidados["modulossacar"] as $modulocod)
			{  
				$ArregloDatos=array('rolcod'=>$datosvalidados['rolcod'],'modulocod'=>$modulocod);
				if (!$oRoles_Modulos->Eliminar ($ArregloDatos))
					$result=false;
				else
					FuncionesPHPLocal::RegistrarAcceso($conexion,'033602',$datosvalidados['rolcod']."-".$modulocod,$_SESSION['usuariocod']);
			}
		}
		
		if($result)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se actualizó el Rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			$conexion->ManejoTransacciones("C");
		}
		else
			$conexion->ManejoTransacciones("R");
	}
	

?>		
		<br /><br /><br /><div align="center"><a href="roles_modulos.php" class="linkfondoblanco">Volver</a></div>
   </div>
</div>
<?php 


$oEncabezados->PieMenuEmergente();
?>
