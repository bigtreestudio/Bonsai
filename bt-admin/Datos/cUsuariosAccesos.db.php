<?php  
abstract class cUsuariosAccesosdb
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


	protected function BuscarUltimoAcceso($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_usuarios_accesos_xanteultimoacceso_xusuariocod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar usuario el ultimo acceso. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}
	

	protected function Insertar($datos)
	{
		$spnombre="ins_usuarios_accesos";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'pusuarioip'=> $datos['usuarioip'],
			'pusuarioso'=> $datos['usuarioso'],
			'pusuarionavegador'=> $datos['usuarionavegador'],
			'pusuariofecha'=> $datos['usuariofecha']
			);				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al dar de alta el acceso del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	
	}
	

}


?>