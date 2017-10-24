<?php  
abstract class cRevistaTapasTiposdb
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
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_tipos_xbusqueda_avanzada";
		$sparam=array(
			'pxrevtapatipoestado'=> $datos['xrevtapatipoestado'],
			'prevtapatipoestado'=> $datos['revtapatipoestado'],		
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las Tapas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_rev_tapas_tipos_xrevtapatipocod";
		$sparam=array(
			'prevtapatipocod'=> $datos['revtapatipocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la Tapa por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

}
?>