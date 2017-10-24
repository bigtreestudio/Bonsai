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


$oModulos=new cModulos($conexion); 

?>
<div class="onecolumn">
    <div class="header">
        <span>M&oacute;dulos</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?php 
//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,$oModulos)
{
	if (isset($_POST['botonalta']))
	{
		if ($_POST['modulocodnuevo']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['modulocodnuevo'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el código de módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['modulotextomenu']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el texto del menú del módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['archivocod']=="" || $_POST['archivocod']=="0" 
			|| !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['archivocod'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el link del módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['modulosec']=="" || $_POST['modulosec']=="0" 
			|| !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['modulosec'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la secuencia del módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['modulomostrar']=='') 
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en si se muestra en el menú. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	} // $_POST['botonalta']
	
	if (isset($_POST['botonbaja'])) 	
	{
		if ($_POST['modulocodviejo']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['modulocodviejo'],"NumericoEntero") )
		{
			MostrarMensaje($idconexion,MSG_ERRSOSP,"Error en el código de módulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),FMT_TEXTO);
			return false;
		}
		if($_POST['modulocodviejo']!="") // validar que exista el modulocod
		{
			$modulocod=$_POST['modulocodviejo'];
			$ArregloDatos['modulocod']=$modulocod;	
			if (!$oModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
				return false;
			
			if ($numfilas!=1)
			{
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Módulo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
			}
		}	
	} // $_POST['botonbaja']

	return true;
}
$result=true;
	if (!Validarphp($conexion,$oModulos))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","modulos - modulocod=".$_POST['modulocodviejo'],$_SESSION['usuariocod']);	
		$result=false;
	}

//----------------------------------------------------------------------------------------- 		
	if (($result) && ((isset($_POST['botonalta']) || isset($_POST['botonbaja']))))
	{
		$commit=true;
		$errorinformable=true;
		
		$conexion->ManejoTransacciones("B");
	
		// Validación de los datos 
		
		// Baja del Archivo
		if($result && isset($_POST['botonbaja'])) 
		{
				if ($result)
				{
					$modulocod=$_POST['modulocodviejo'];
					$ArregloDatos['modulocod']=$modulocod;	
					if (!$oModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
						$result=false;
					if ($result)
					{
						if ($numfilas!=1)
						{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Módulo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
						}
					}
					if ($result)
					{
						if (!$oModulos->Eliminar ($ArregloDatos))
							$result=false;
						$datos=$conexion->ObtenerSiguienteRegistro($resultado);
						if($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"032502","modulocod=".$modulocod,$_SESSION['usuariocod']);	
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado el módulo '".$modulocod."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			
		} // botonbaja

		// Alta o Actualización
		if($result && isset($_POST['botonalta'])) 
		{
			if($_POST['modulocodviejo']=="") 
			{
				$modulocod=$_POST['modulocodnuevo'];
				$ArregloDatos['modulocod']=$modulocod;	
				if (!$oModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				if ($result)
				{
					if ($numfilas==1)
					{
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"El Módulo ya se encuentra en la Tabla de Modulos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					if ($result)
					{
						if(!isset($_POST['modulodash']))
							$_POST['modulodash']="0";
						
						$ArregloDatos['modulocod']=$_POST['modulocodnuevo'];	
						$ArregloDatos['modulodesc']=$_POST['modulodesc'];	
						$ArregloDatos['modulotextomenu']=$_POST['modulotextomenu'];	
						$ArregloDatos['archivocod']=$_POST['archivocod'];	
						$ArregloDatos['modulosec']=$_POST['modulosec'];	
						$ArregloDatos['modulomostrar']=$_POST['modulomostrar'];	
						$ArregloDatos['moduloimg']=$_POST['moduloimg'];	
						$ArregloDatos['modulodash']=$_POST['modulodash'];	
						$ArregloDatos['moduloacciones']=$_POST['moduloacciones'];	
						if (!$oModulos->Insertar ($ArregloDatos,$codigomodulo))
							$result=false;
						
						if ($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"032501","modulocod=".$_POST['modulocodnuevo'],$_SESSION['usuariocod']);
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha insertado el modulo '".$_POST['modulocodnuevo']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			}else
			{	
				$modulocod=$_POST['modulocodnuevo'];
				$ArregloDatos['modulocod']=$modulocod;	
				if (!$oModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				if ($result)
				{
					if ($numfilas!=1)
					{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Módulo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
					}
					if ($result)
					{
						if(!isset($_POST['modulodash']))
							$_POST['modulodash']="0";
						
						$ArregloDatos['modulocodnuevo']=$_POST['modulocodnuevo'];	
						$ArregloDatos['modulocod']=$_POST['modulocodviejo'];	
						$ArregloDatos['modulodesc']=$_POST['modulodesc'];	
						$ArregloDatos['modulotextomenu']=$_POST['modulotextomenu'];	
						$ArregloDatos['archivocod']=$_POST['archivocod'];	
						$ArregloDatos['modulosec']=$_POST['modulosec'];	
						$ArregloDatos['modulomostrar']=$_POST['modulomostrar'];	
						$ArregloDatos['moduloimg']=$_POST['moduloimg'];	
						$ArregloDatos['modulodash']=$_POST['modulodash'];						
						$ArregloDatos['moduloacciones']=$_POST['moduloacciones'];	
						if (!$oModulos->Modificar ($ArregloDatos))
							$result=false;
						
						if ($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"032503","modulocod=".$_POST['modulocodviejo'],$_SESSION['usuariocod']);
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha actualizado el modulo '".$_POST['modulocodviejo']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			} // botonalta	
		}
		if($result)
			$conexion->ManejoTransacciones("C");
		else
			$conexion->ManejoTransacciones("R");
		
	} // $_POST['botonalta'] || $_POST['botonbaja']

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

?>
			<br /><br /><br /><div align="center"><a href="modulos.php" class="linkfondoblanco">Volver</a></div>
   </div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
