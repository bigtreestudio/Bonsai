<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las paginas

class cTemas
{
	
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	



	public function BuscarTema($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tem_temas_xtemacod";
		$sparam=array(
			'ptemacod'=> $datos['temacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el tema.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;	
	}




}//FIN CLASE
?>