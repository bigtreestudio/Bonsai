<?php  
abstract class cTapasMenuPublicadasdb
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


	protected function Insertar($datos)
	{

		$spnombre="ins_tap_menu_publicados";
		$sparam=array(
			'pmenucod'=> $datos['menucod'],
			'pmenudesc'=> $datos['menudesc'],
			'pmenulink'=> $datos['menulink'],
			'pmenutitle'=> $datos['menutitle'],
			'pmenuaccesskey'=> $datos['menuaccesskey'],
			'pmenucodsup'=> $datos['menucodsup'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmenuorden'=> $datos['menuorden'],
			'pmenutarget'=> $datos['menutarget'],
			'pmenuclass'=> $datos['menuclass'],
			'pmenuclassli'=> $datos['menuclassli'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar las tapas del menu.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	

	protected function Eliminar($datos)
	{
		$spnombre="del_tap_menu_publicados_xmenutipocod";
		$sparam=array(
			'pmenutipocod'=> $datos['menutipocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar del menu publicado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	


}
?>