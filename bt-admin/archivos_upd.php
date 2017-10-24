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

$oArchivos= new cArchivos($conexion);

//----------------------------------------------------------------------------------------- 


?>
<div class="onecolumn">
    <div class="header">
        <span>Archivos</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?php 
function BuscarBoton ($cadenapost,$cadenainicio,&$alta)
{
	$alta=false;
	$codigo=strstr ($cadenapost, $cadenainicio);  // string empezando por Borrar
	if ($codigo!="")
		$alta=true;
}


function DesglosarBt ($cadenapost,$cadenainicio,&$codigo,$alta)
{
		$codigo=strstr ($cadenapost, $cadenainicio );  // string empezando por Borrar
 		$pos1=0;
		$pos2=0;
		$pos3=0;		
		$pos1 = strpos($codigo, '_'); 
		if (!$pos1===false)
			if ($alta)
				$possig1 = strpos(substr($codigo,$pos1+1), '_php');
			else
				$possig1 = substr($codigo,$pos1+1);

		if (!$possig1===false) 
		{
			$pos2= $pos1+$possig1+1;
		}
		
		if ($pos1>0 && $pos2>0) 
		{
			$codigolength=$pos2-$pos1-1;
			$codigo=substr($codigo,$pos1+1,$codigolength);
		}
		
		return true;
}



function Validarphp($conexion,$oArchivos,$codigoarchborrar)
{
	if (isset($_POST['botonbaja']) && $_POST['archivocod']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Archivo no seleccionado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	if (isset($_POST['botonalta']) && $_POST['archivonomnuevo']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en al nombre del archivo a insertar. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	if(($_POST['archivocod']!="") || ($codigoarchborrar!=""))
	{
		if (isset ($_POST['archivocod']) && ($_POST['archivocod']!=""))
			$codigoarchborrar=$_POST['archivocod'];
			
		$ArregloDatos['archivocod']=$codigoarchborrar;
		
		if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$query) || ($numfilas!=1))
		{	
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código de Archivo Inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
	}
	return true;
}
//----------------------------------------------------------------------------------------- 	
	$result=true;
	$codigoarchborrar="";
	$nombrearchivo="";
	
	
	if (!isset($_POST['botonalta']) && !isset($_POST['botonbaja']) && !isset($_POST['BotonAltaSelecionados']) && !isset($_POST['BotonBorrarSelecionados']))
	{
		$cadenapost=implode ( '|', array_keys($_POST));
		
		$cadenainicio="Alta_";
		BuscarBoton ($cadenapost,$cadenainicio,$alta);
		if (!$alta)
			$cadenainicio="Baja_";
			
		DesglosarBt($cadenapost,$cadenainicio,$nombrearchivo,$alta);
		if ($alta)
		{
			$nombrearchivo.=".php";
		}
		else
		{	
			$codigoarchborrar=$nombrearchivo;
			$nombrearchivo="";	
		}	
	}

	if (!Validarphp($conexion,$oArchivos,$codigoarchborrar))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","Archivos - archivocod=".$_POST['archivocod'],$_SESSION['usuariocod']);	
		$result=false;
	}
	
	if ($result)
	{
			$conexion->ManejoTransacciones("B");
		
			// Validación de los datos 
			// Baja del Archivo
			if($result &&  ( isset($_POST['botonbaja']) || ($codigoarchborrar!="") )  ) 
			{
				if ( isset($_POST['botonbaja']))
					$codigoarchborrar=$_POST['archivocod'];
				
				$ArregloDatos['archivocod']=$codigoarchborrar;	
				if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$resultado))
				{	
					FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
					$result=false;
				}
				
				if ($result)
				{
					if ($numfilas!=1)
					{
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Archivo inexistente",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					if ($result)
					{
						if (!$oArchivos->Eliminar ($ArregloDatos))
							$result=false;
						$datos=$conexion->ObtenerSiguienteRegistro($resultado);
						if($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"030102","archivocod=".$codigoarchborrar,$_SESSION['usuariocod']);	
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado el archivo '".$datos['archivonom']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			} // botonbaja		
			
			// Alta o Actualización
			if($result &&  (isset($_POST['botonalta']) || ($nombrearchivo!="") )  ) 
			{
				if (($_POST['archivocod']=="") || ($nombrearchivo!=""))
				{
					if ($nombrearchivo=="")
						$nombrearchivo=$_POST['archivonomnuevo'];
					
					
					$ArregloDatos['archivonom']=$nombrearchivo;
					if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$resultado))
					{	
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					if ($result)
					{
						if ($numfilas==1)
						{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"El Archivo ya se encuentra en la Tabla de Archivos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
						}
					
						if ($result)
						{
							if (!$oArchivos->Insertar ($ArregloDatos,$codigoarchivo))
								$result=false;
							
							if ($result)
							{
									FuncionesPHPLocal::RegistrarAcceso($conexion,"030101","archivonom=".$nombrearchivo,$_SESSION['usuariocod']);
									FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha insertado el archivo '".$nombrearchivo."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							}
						}
					}	
				} 
				else 
				{	
					$ArregloDatos['archivocod']=$_POST['archivocod'];
					if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$resultado))
					{	
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					
					if ($result)
					{
						if ($numfilas!=1)
						{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Archivo inexistente",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
						}
						if ($result)
						{
							$ArregloDatos['archivonom']=$_POST['archivonomnuevo'];
							if (!$oArchivos->Modificar ($ArregloDatos))
								$result=false;
							$datos=$conexion->ObtenerSiguienteRegistro($resultado);
							if($result)
							{
								FuncionesPHPLocal::RegistrarAcceso($conexion,"030103","archivocod=".$_POST['archivocod'],$_SESSION['usuariocod']);
								FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha actualizado el archivo '".$datos['archivonom']."' a '".$_POST['archivonomnuevo'].". ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							}
						}
					}
				}
			}	
			
			
			if(isset($_POST['BotonAltaSelecionados']) && $_POST['BotonAltaSelecionados']!="")
			{
				//print_r($_POST);
				if(count($_POST['checkins'])>0)
				{
					foreach($_POST['checkins'] as $key=>$nombrearchivo)
					{
						if ($nombrearchivo!="")
						{
							
							$ArregloDatos['archivonom']=$nombrearchivo;
							if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$resultado))
							{	
								FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
								$result=false;
							}
							if ($result)
							{
								if ($numfilas==1)
								{
									FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"El Archivo ya se encuentra en la Tabla de Archivos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
									$result=false;
								}
							
								if ($result)
								{
									if (!$oArchivos->Insertar ($ArregloDatos,$codigoarchivo))
										$result=false;
									
									if ($result)
									{
											FuncionesPHPLocal::RegistrarAcceso($conexion,"030101","archivonom=".$nombrearchivo,$_SESSION['usuariocod']);
											FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha insertado el archivo '".$nombrearchivo."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
									}
								}
							}	
						} 
					}
				}
				
			}
			
			if(isset($_POST['BotonBorrarSelecionados']) && $_POST['BotonBorrarSelecionados']!="")
			{
				
				if(count($_POST['checkdel'])>0)
				{
					foreach($_POST['checkdel'] as $key=>$codigoarchborrar)
					{
						if ($codigoarchborrar!="")
						{
							$ArregloDatos['archivocod']=$codigoarchborrar;	
							if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$resultado))
							{	
								FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
								echo "aca";die;
								$result=false;
							}
							
							if ($result)
							{
								if ($numfilas!=1)
								{
									FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Código de Archivo inexistente",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
									$result=false;
								}
								if ($result)
								{
									if (!$oArchivos->Eliminar ($ArregloDatos))
										$result=false;
									$datos=$conexion->ObtenerSiguienteRegistro($resultado);
									if($result)
									{
										FuncionesPHPLocal::RegistrarAcceso($conexion,"030102","archivocod=".$codigoarchborrar,$_SESSION['usuariocod']);	
										FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado el archivo '".$datos['archivonom']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
									}
								}
							}	
				
						} 
					}
				}
			}
			
			if($result)
				$conexion->ManejoTransacciones("C");
			else
				$conexion->ManejoTransacciones("R");
			
}

?>
		<br /><br /><br /><div align="center"><a href="archivos.php" class="linkfondoblanco">Volver</a></div>
   </div>
</div>
<?php 


$oEncabezados->PieMenuEmergente();
?>
