<?php  
abstract class cNoticiasPermisosdb
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
// Retorna las acciones a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerEstadosxUsuario($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estados_xusuario";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados al cual el rol accede.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna las acciones a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function PuedeEditarNoticiaxEstado($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estados_usuarios_xusuariocod_noticiaestadocod";
		$sparam=array(
			'pusuariocod'=> $_SESSION['usuariocod'],
			'pnoticiaestadocod'=> $datos['noticiaestadocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados de la noticia por usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}	

}


?>