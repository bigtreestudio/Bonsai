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

$oGruposModulos= new cGruposModulos($conexion);

?>
<div class="onecolumn">
    <div class="header">
        <span>Grupos M&oacute;dulos</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?php 
//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,$oGruposModulos)
{
	if(isset($_POST['botonbaja']) && $_POST['grupomodcod']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el código de grupo de módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	if($_POST['grupomodcod']!="" && !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['grupomodcod'],"NumericoEntero"))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el código de grupo de módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	if($_POST['grupomodcod']!="") // validar que exista el grupomodcod
	{
		
		$ArregloDatos['grupomodcod']=$_POST['grupomodcod'];
		if (!$oGruposModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error grupo de módulo inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	}
	
	if(isset($_POST['botonalta']))
	{	
		if($_POST['grupomodtextomenu']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el texto del menu del grupo de módulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if($_POST['grupomodsec']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['grupomodsec'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la secuencia del grupo de módulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	}	
	
	return true;
}

//----------------------------------------------------------------------------------------- 		

	if (isset($_POST['botonalta']) || isset($_POST['botonbaja']))
	{
		$result=true;
		$errorinformable=true;
		
		$conexion->ManejoTransacciones("B");
	
		// Validación de los datos 
		if (!Validarphp($conexion,$oGruposModulos))
		{
			FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","Grupos modulos - grupomodtextomenu=".$_POST['grupomodtextomenu'],$_SESSION['usuariocod']);	
			$result=false;
		}
		
		// Baja del Archivo
		if($result && isset($_POST['botonbaja'])) 
		{
			
			$ArregloDatos['grupomodcod']=$_POST['grupomodcod'];
		
			if (!$oGruposModulos->Eliminar ($ArregloDatos))
				$result=false;

			if($result)
			{
				FuncionesPHPLocal::RegistrarAcceso($conexion,"031002","grupomodcod=".$_POST['grupomodcod'],$_SESSION['usuariocod']);	
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado el grupo de módulos '".$_POST['grupomodtextomenu']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			}
		} // botonbaja

		// Alta o Actualización
		if($result && isset($_POST['botonalta'])) 
		{
			if($_POST['grupomodcod']=="") 
			{
				$ArregloDatos['grupomodtextomenu']=$_POST['grupomodtextomenu'];
				$ArregloDatos['grupomodsec']=$_POST['grupomodsec'];
			
				if (!$oGruposModulos->Insertar ($ArregloDatos,$codigogrupomodulo))
					$result=false;

				if ($result)
				{
					FuncionesPHPLocal::RegistrarAcceso($conexion,"031001","grupomodtextomenu=".$_POST['grupomodtextomenu'],$_SESSION['usuariocod']);
					FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha insertado el grupo de módulos '".$_POST['grupomodtextomenu']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				}
			}else
			{
				$ArregloDatos['grupomodtextomenu']=$_POST['grupomodtextomenu'];
				$ArregloDatos['grupomodsec']=$_POST['grupomodsec'];
				$ArregloDatos['grupomodcod']=$_POST['grupomodcod'];
				if (!$oGruposModulos->Modificar ($ArregloDatos))
					$result=false;

				if ($result)
				{
					FuncionesPHPLocal::RegistrarAcceso($conexion,"031003","grupomodtextomenu=".$_POST['grupomodtextomenu'],$_SESSION['usuariocod']);
					FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha actualizado el grupo de módulos '".$_POST['grupomodtextomenu']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				}
			
			} 
			
			
		} // botonalta	

		if($result)
			$conexion->ManejoTransacciones("C");
		else
			$conexion->ManejoTransacciones("R");
		
	} // $_POST['botonalta'] || $_POST['botonbaja']
?>		
			<br /><br /><br /><div align="center"><a href="gruposmod.php" class="linkfondoblanco">Volver</a></div>
   </div>
</div>
<?php 

$oEncabezados->PieMenuEmergente();
?>
