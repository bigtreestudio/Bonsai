<?php  
abstract class cVisualizacionesTiposdb
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



	   protected function VisualizacionTiposSP(&$spnombre,&$sparam)
       {
		$spnombre="sel_vis_visualizaciones_tipos_stored";
		$sparam=array(
			);
   
		   return true;
       }
	   
	 protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_vis_visualizaciones_tipos_xvisualizaciontipocod";
		$sparam=array(
			'pvisualizaciontipocod'=> $datos['visualizaciontipocod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de visualización por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
  
}


?>