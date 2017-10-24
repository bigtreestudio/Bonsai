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

?>
<div class="onecolumn">
    <div class="header">
        <span>Stored Procedures Front</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?
$oStoredFront=new cStoredFront($conexion);

if (get_magic_quotes_gpc())
	$_POST=FuncionesPHPLocal::removeMagicQuotes ($_POST);


//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,$oStoredFront)
{
	if (isset($_POST['botonbuscar']))
	{
		if ($_POST['spnombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el texto del Nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	
	}
	if (isset($_POST['botonalta']))
	{
		if ($_POST['spnombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el texto del Nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['sptabla']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el texto de la Tabla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['spoperacion']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el texto de la Operación. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['spsqlstring']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el texto del SQL. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		if ($_POST['spcodviejo']!="" && !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['spcodviejo'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el código de Stored Procedures. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	} // $_POST['botonalta']
	
	if (isset($_POST['botonbaja'])) 	
	{
		if ($_POST['spcodviejo']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['spcodviejo'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error en el código del Stored Procedure. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if($_POST['spcodviejo']!="") // validar que exista el modulocod
		{
			$spcod=$_POST['spcodviejo'];
			$ArregloDatos['spcod']=$spcod;	
			if (!$oStoredFront->Buscar ($ArregloDatos,$numfilas,$resultado))
				return false;
			
			if ($numfilas!=1)
			{
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Stored Procedure inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
			}
		}	
	} // $_POST['botonbaja']

	return true;
}
$result=true;
	if (!Validarphp($conexion,$oStoredFront))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","Stored - spcodviejo=".$_POST['spcodviejo'],$_SESSION['usuariocod']);	
		$result=false;
	}



	if (isset ($_POST['botonbuscar']))
	{
		$carpetas=explode ("||",DIR_BUSQUEDA);
		$spnombre=$_POST['spnombre'];
		if (!$oStoredFront->VerificarExistenciaStore ($spnombre,$carpetas,$encontrado,$texto))
			$result=false;
		
		if ($encontrado)
			echo $texto;
		else
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_INF,"No se encuentra en ninguna carpeta",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	}
//----------------------------------------------------------------------------------------- 		
	if ($result &&  ((isset($_POST['botonalta']) || isset($_POST['botonbaja']))))
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
					$carpetas=explode ("||",DIR_BUSQUEDA);
					$spnombre=$_POST['spnombre'];
					if (!$oStoredFront->VerificarExistenciaStore ($spnombre,$carpetas,$encontrado,$texto))
					{	
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					if ($encontrado)
					{
						$result=false;
						echo $texto;
					}
					if ($result)
					{
						$spcod=$_POST['spcodviejo'];
						$ArregloDatos['spcod']=$spcod;	
						if (!$oStoredFront->Buscar ($ArregloDatos,$numfilas,$resultado))
							$result=false;

					}
					if ($result)
					{
						if ($numfilas!=1)
						{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Stored Procedure inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
						}
					}
					if ($result)
					{
						if (!$oStoredFront->Eliminar ($ArregloDatos))
							$result=false;

						$datos=$conexion->ObtenerSiguienteRegistro($resultado);
						if($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"034802","spcod=".$spcod,$_SESSION['usuariocod']);	
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado el Stored Procedure '".$spcod."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
					$conexion->ManejoTransacciones("C");
				}
			
		} // botonbaja

		// Alta o Actualización
		if($result && isset($_POST['botonalta'])) 
		{
			
			if($_POST['spcodviejo']=="") 
			{
				$ArregloDatos['spnombre']=$_POST['spnombre'];	
				if (!$oStoredFront->Buscar ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				if ($result)
				{
					if ($numfilas==1)
					{
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"El Nombre del Stored ya se encuentra en la Tabla de Stored.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					if ($result)
					{
						
						$ArregloDatos['spoperacion']=$_POST['spoperacion'];	
						$ArregloDatos['sptabla']=$_POST['sptabla'];	
						$ArregloDatos['spsqlstring']=$_POST['spsqlstring'];	
						$ArregloDatos['spobserv']=$_POST['spobserv'];	
						if (!$oStoredFront->Insertar ($ArregloDatos,$codigosp))
							$result=false;
						
						if ($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"034801","spcod=".$codigosp,$_SESSION['usuariocod']);
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha insertado el Stored Procedures '".$_POST['spnombre']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			}else
			{	
				$spcod=$_POST['spcodviejo'];
				$ArregloDatos['spcod']=$spcod;	
				if (!$oStoredFront->Buscar ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				if ($result)
				{
					if ($numfilas!=1)
					{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Stored Procedure inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
					}
					if ($result)
					{
						$Arreglonombre['spnombre']=$_POST['spnombre'];
						if (!$oStoredFront->Buscar ($Arreglonombre,$numfilas,$resultadonombre))
							$result=false;
						
						if ($result)
						{
							
							if ($numfilas==1)
							{
								$datos=$conexion->ObtenerSiguienteRegistro ($resultadonombre);
								if ($datos['spcod']!=$spcod)
								{
									FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"El Nombre del Stored ya se encuentra en la Tabla de Stored.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
									$result=false;
								}	
							}
						}
						if ($result)
						{
							$ArregloDatos['spnombre']=$_POST['spnombre'];
							$ArregloDatos['spoperacion']=$_POST['spoperacion'];	
							$ArregloDatos['sptabla']=$_POST['sptabla'];	
							$ArregloDatos['spsqlstring']=$_POST['spsqlstring'];	
							$ArregloDatos['spobserv']=$_POST['spobserv'];	
							if (!$oStoredFront->Modificar ($ArregloDatos))
								$result=false;
						}
						if ($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"034803","spcod=".$spcod,$_SESSION['usuariocod']);
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha actualizado el Stored '".$_POST['spnombre']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
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
			<br /><br /><br /><div align="center"><a href="stored_front.php" class="linkfondoblanco">Volver</a></div>
    </div>
</div>        
<div style="height:20px; clear:both">&nbsp;</div>
<?
$oEncabezados->PieMenuEmergente();
?>
