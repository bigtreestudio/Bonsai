<?php  
abstract class cUsuariosModulosAccionesdb
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


	protected function BuscarAccionxUsuario($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_usuarios_modulos_acciones_xusuariocod_accioncodigo";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'paccioncodigo'=> $datos['accioncodigo']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la accion del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}
	

	protected function BuscarAccionesxUsuario($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_usuarios_modulos_acciones_xusuariocod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la accion del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}
	

	protected function Insertar($datos)
	{

		$spnombre="ins_usuarios_modulos_acciones";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'paccioncodigo'=> $datos['accioncodigo'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar una accion a un usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	
	}
	
	protected function Eliminar($datos)
	{

		$spnombre="del_usuarios_modulos_acciones_xusuariocod_accioncodigo";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'paccioncodigo'=> $datos['accioncodigo']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar una accion a un usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	
	}
	

}


?>