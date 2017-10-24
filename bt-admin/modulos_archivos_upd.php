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


$oModulosArchivos = new cModulosArchivos($conexion);
$oModulos= new cModulos ($conexion);
//----------------------------------------------------------------------------------------- 

?>
<div class="onecolumn">
    <div class="header">
        <span>M&oacute;dulos - Archivos</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?php 

function Validarphp($conexion,&$datosvalidados,$oModulosArchivo)
{
	$datosvalidados=array();
	
	if($_POST['modulocod']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la selección del módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}

	if(!$conexion->BuscarRegistroxClave("sel_modulos_orden",array("porderby"=>"modulocod"),array("modulocod"=>$_POST['modulocod']),$resultado,$filaret,$numfilasmatcheo,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	if($numfilasmatcheo!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosvalidados["modulocod"]=$_POST['modulocod'];

	if(isset($_POST['listaarchivosder']))
		$arrayfinal=$_POST['listaarchivosder'];
	else
		$arrayfinal=array();

	$arrayinicial=array();
	if(!$conexion->ejecutarStoredProcedure("sel_archivos_xmodulocod_orden",array('pmodulocod'=>$datosvalidados["modulocod"],'porderby'=>"archivonom"),$resultado,$numfilas,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al seleccionar los archivos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	while($filaarchivos=$conexion->ObtenerSiguienteRegistro($resultado))
		$arrayinicial[]=$filaarchivos['archivocod'];

	$arraysacar=array_diff($arrayinicial,$arrayfinal);
	$arrayponer=array_diff($arrayfinal,$arrayinicial);

	$datosvalidados["archivossacar"]=$arraysacar;
	$datosvalidados["archivosponer"]=$arrayponer;
	
	return true;
}

//----------------------------------------------------------------------------------------- 	


	$result=true;
	if(isset($_POST['botonalta']) && !Validarphp($conexion,$datosvalidados,$oModulosArchivos))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","",$_SESSION['usuariocod']);	
		$result=false;
	}

	$ArregloDatos['modulocod']=$datosvalidados["modulocod"];
	if (!$oModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
		$result=false;

	if ($result)
		$datosmodulo=$conexion->ObtenerSiguienteRegistro($resultado);
		
	if($result && isset($_POST['botonalta']))
	{
		$conexion->ManejoTransacciones("B");
		
		foreach($datosvalidados["archivosponer"] as $archivocod)
		{
			$ArregloDatos=array('modulocod'=>$datosvalidados['modulocod'],'archivocod'=>$archivocod);
			if (!$oModulosArchivos->Insertar ($ArregloDatos))
			{	
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				$result=false;
				break;
			}
			FuncionesPHPLocal::RegistrarAcceso($conexion,'032601',$datosvalidados['modulocod']."-".$archivocod,$_SESSION['usuariocod']);
		}

		if($result)
		{
			foreach($datosvalidados["archivossacar"] as $archivocod)
			{
				$ArregloDatos=array('modulocod'=>$datosvalidados['modulocod'],'archivocod'=>$archivocod);
				if (!$oModulosArchivos->Eliminar ($ArregloDatos))
				{	
					FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
					$result=false;
					break;
				}
				FuncionesPHPLocal::RegistrarAcceso($conexion,'032602',$datosvalidados['modulocod']."-".$archivocod,$_SESSION['usuariocod']);
			}
		}
		
		if($result)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se actualizó el Módulo '".$datosmodulo['modulotextomenu']."'.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			$conexion->ManejoTransacciones("C");
		}
		else
			$conexion->ManejoTransacciones("R");
	}
	
?>
			<br /><br /><br /><div align="center"><a href="modulos_archivos.php" class="linkfondoblanco">Volver</a></div>
   </div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
