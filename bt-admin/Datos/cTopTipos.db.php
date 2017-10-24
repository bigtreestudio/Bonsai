<?php  
abstract class cTopTiposdb
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

	function BuscarTopTipoxCodigo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_top_top_tipos_xtoptipocod";
		$sparam=array(
			'ptoptipocod'=> $datos['toptipocod']
			);
		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los tops por tipo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;	
	}
	
	//Busca top tipo
	function BuscarTopTipo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_top_top_tipos";
		$sparam=array(
			'porderby'=> "toptipocod"
			);
		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los top por tipo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;	
	}

		
		


}//fin clase	

?>
