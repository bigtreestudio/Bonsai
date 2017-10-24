<?php  
include(DIR_CLASES_DB."cTapasModulosTipos.db.php");

class cTapasModulosTipos extends cTapasModulosTiposdb	
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


	public function BuscarSP(&$spnombre,&$spparam)
	{
		if (!parent::BuscarSP ($spnombre,$spparam))
			return false;
			
		return true;			
	}

	
}//FIN CLASS
?>