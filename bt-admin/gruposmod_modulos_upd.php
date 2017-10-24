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

$oGrupomod_Modulos= new cGruposmodModulos($conexion);
$oGruposModulos= new cGruposModulos($conexion);

?>
<div class="onecolumn">
    <div class="header">
        <span>Grupos M&oacute;dulos - M&oacute;dulos</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?php 

//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,&$datosvalidados)
{
	$datosvalidados=array();
	
	if($_POST['grupomodcod']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la selección del grupo de módulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}

	if(!$conexion->BuscarRegistroxClave("sel_gruposmod_orden",array("porderby"=>"grupomodcod"),array("grupomodcod"=>$_POST['grupomodcod']),$resultado,$filaret,$numfilasmatcheo,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el grupo de módulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	if($numfilasmatcheo!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el grupo de módulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosvalidados["grupomodcod"]=$_POST['grupomodcod'];

	if(isset($_POST['listamodulosder']))
		$arrayfinal=$_POST['listamodulosder'];
	else
		$arrayfinal=array();

	$arrayinicial=array();
	if(!$conexion->ejecutarStoredProcedure("sel_modulos_xgrupomodcod_orden",array('pgrupomodcod'=>$datosvalidados["grupomodcod"],'porderby'=>"modulocod"),$resultado,$numfilas,$errno))
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

	$ArregloDatos['grupomodcod']=$datosvalidados["grupomodcod"];
	if (!$oGruposModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
		$result=false;

	if ($result)
		$datosgrupomodulo=$conexion->ObtenerSiguienteRegistro($resultado);


	if($result && isset($_POST['botonalta']))
	{
		$conexion->ManejoTransacciones("B");
		
		foreach($datosvalidados["modulosponer"] as $modulocod)
		{
			$ArregloDatos=array('modulocod'=>$modulocod,'grupomodcod'=>$datosvalidados['grupomodcod']);
			if (!$oGrupomod_Modulos->Insertar ($ArregloDatos))
				$result=false;
			else
				FuncionesPHPLocal::RegistrarAcceso($conexion,'031101',$datosvalidados['grupomodcod']."-".$modulocod,$_SESSION['usuariocod']);
		}

		if($result)
		{
			foreach($datosvalidados["modulossacar"] as $modulocod)
			{
				
				$ArregloDatos=array('grupomodcod'=>$datosvalidados['grupomodcod'],'modulocod'=>$modulocod);
				if (!$oGrupomod_Modulos->Eliminar ($ArregloDatos))
					$result=false;
				else	
					FuncionesPHPLocal::RegistrarAcceso($conexion,'031102',$datosvalidados['grupomodcod']."-".$modulocod,$_SESSION['usuariocod']);
			}
		}
		
		if($result)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se actualizó el Grupo de Módulos '".$datosgrupomodulo['grupomodtextomenu']."'.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			$conexion->ManejoTransacciones("C");
		}
		else
			$conexion->ManejoTransacciones("R");
	}
	


?>
			<br /><br /><br /><div align="center"><a href="gruposmod_modulos.php" class="linkfondoblanco">Volver</a></div>
		</div>
   </div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
