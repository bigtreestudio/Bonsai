<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

class cRevTapas
{
	
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function BuscarTapas ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_xrevtapafecha";
		$sparam=array(
			'porderby'=> 'revtapafecha DESC',
			'prevtapaestado'=>'10'
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar  la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;
	}


	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_xrevtapacod";
		$sparam=array(
			'prevtapacod'=> $datos['revtapacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
	}

	public function BuscarPaginasxTapa($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_multimedia_xrevtapacod";
		$sparam=array(
			'prevtapacod'=> $datos['revtapacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las paginas de la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
	}


	

			
}//FIN CLASE
?>