<?php  
abstract class cPlantillasCssdb
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

	protected function SpEstilosCSS($datos,&$spnombre,&$sparam)
	{
		$spnombre="sel_tap_plantillas_css_xcsstipocod";
		$sparam=array('pcsstipocod'=> $datos['csstipocod'],'porderby'=> $datos['orderby']);
		return true;
	}

	
	protected function TraerEstilosCSS($datos,&$resultado,&$numfilas)
	{

		$this->SpEstilosCSS($datos,$spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estilos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}


}
?>