<?php  
abstract class cFormulariosEnviosTiposdb
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

	   protected function FormulariosEnviosTiposSP(&$spnombre,&$sparam)
       {
		$spnombre="sel_con_formulario_envios_tipos_stored";
		$sparam=array(
			);
   
		   return true;
       }
	   
	   
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_con_formulario_envios_tipos_xenviotipo";
		$sparam=array(
			'penviotipo'=> $datos['enviotipo']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de envio por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


}
?>