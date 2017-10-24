<?php  
abstract class cGoogledb
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
// Retorna el SP y los parametros para cargar los roles del sistema

	protected function Buscar($datos,&$resultado,&$numfilas)
	{	
		//traer los datos de google analytics
		$spnombre="sel_goo_google_xgooglecod";
		$sparam=array(
			'pgooglecod'=> $datos['googlecod']

			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al traer los datos.",array("archivo" => __FILE__,									"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}


	
	protected function ModificarDatos($datos)
	{
		$spnombre="upd_goo_google_xgooglecod";
		$sparam=array(
			'pgoogletitulo'=> $datos['googletitulo'],
			'pgoogleuser'=> $datos['googleuser'],
			'pgooglepass'=> $datos['googlepass'],
			'pgooglecodanalytics'=> $datos['googlecodigoanalytics'],
			'pgooglecod'=> $datos['googlecod']
			);
	
		//print_r($sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el feriado.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function ModificarProfiles($datos)
	{	
		$spnombre="upd_goo_google_xgoogleprofile";
		$sparam=array(
			'pgoogleprofile'=> $datos['googleprofile'],
			'pgoogleprofilename'=> $datos['googleprofilename'],
			'pgooglecod'=> $datos['googlecod']
			);
				
	
		//print_r($sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el profile.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
}
?>