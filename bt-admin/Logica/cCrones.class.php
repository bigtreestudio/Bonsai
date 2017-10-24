<?php 
include(DIR_CLASES_DB."cCrones.db.php");

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de usuarios

class cCrones extends cCronesdb
{
	protected $conexion;
	protected $formato;
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	

//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

	public function BuscarCronesEjecutar($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarCronesEjecutar ($datos,$resultado,$numfilas))
			return false;
		return true;
	}
	
}//fin clase	

?>