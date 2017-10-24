<?php  
abstract class cNoticiasAccionesdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 


//----------------------------------------------------------------------------------------- 
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionesPermitidasxRol($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_acciones_xusuario";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAcciones($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_acciones";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un usuario dado

// Parámetros de Entrada:
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionesxUsuariocod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_acciones_usuarios_xusuariocod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones de un usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 



	protected function AltaUsuarioAccion($datos)
	{
		
		$spnombre="ins_not_noticias_acciones_usuarios";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'pnoticiaaccioncod'=> $datos['noticiaaccioncod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudieron insertar el usuario a la accion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;

	}


	protected function BajaUsuarioAccion($datos)
	{
		
		$spnombre="del_not_noticias_acciones_usuarios_xusuariocod_noticiaaccioncod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'pnoticiaaccioncod'=> $datos['noticiaaccioncod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudieron dar de baja el usuario a la accion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;

	}
	

}


?>