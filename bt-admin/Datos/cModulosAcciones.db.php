<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con el acceso a base de datos para el manejo de modulos archivos
abstract class cModulosAccionesdb
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
// Retorna una consulta con todos los usuarios que cumplan con las condiciones

// Parámetros de Entrada:
//		ArregloDatos: array asociativo con los filtros. Claves: usuarionombre, usuarioapellido, usuariocuit, usuarioemail

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarModulosAccionesxUsuarioxRolcodActualiza($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_modulos_acciones_xusuario_xrolcodactualiza";
		$sparam=array(
			'prolcodactualiza'=> $datos['rolcodactualiza'],
			'pusuariocod'=> $datos['usuariocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos para actualizar.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



	protected function BuscarAccionesxUsuarioxRolcodActualiza($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_modulos_acciones_acciones_xusuario_xrolcodactualiza";
		$sparam=array(
			'prolcodactualiza'=> $datos['rolcodactualiza'],
			'pusuariocod'=> $datos['usuariocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos para actualizar.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


	protected function BuscarAccionesxModulos($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_modulos_acciones_xmodulocod_usuariocod";
		$sparam=array(
			'pmodulocod'=> $datos['modulocod'],
			'pxusuariocod'=> $datos['xusuariocod'],
			'pusuariocod'=> $datos['usuariocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos para actualizar.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}




}

?>