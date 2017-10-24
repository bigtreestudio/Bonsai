<?php  
abstract class cCronesdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	
	protected function BuscarCronesEjecutar($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_crones_ejecutar";
		$sparam=array(
			'pfecha'=> date("Y-m-d",strtotime($datos['fecha'])),
			'phora'=>  date("H:i:s",strtotime($datos['fecha'])),
			'pfechahora'=> $datos['fecha']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje("Error al buscar los crones ".$datos['tabla']);
			return false;
		}
		return true;
	}

	

}


?>