<?php  
abstract class cUsuariosRolesdb
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

	protected function AltaUsuarioRol($datos)
	{

		$spnombre="ins_usuarios_roles";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'prolcod'=> $datos['rolcod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudieron insertar el usuario al rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;

	}


	protected function BajaUsuarioRol($datos)
	{
		
		$spnombre="del_usuarios_roles_sitios_xusuariocod_rolcod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'prolcod'=> $datos['rolcod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudieron dar de baja el usuario al rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;

	}


}


?>