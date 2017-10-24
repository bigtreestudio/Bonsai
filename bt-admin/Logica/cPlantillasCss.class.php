<?php  
include(DIR_CLASES_DB."cPlantillasCss.db.php");

class cPlantillasCss extends cPlantillasCssdb	
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

	
// Parámetros de Entrada:
//		Sin datos de entrada

// Retorna:
//		sparam,spnombre: Parametros del sql y nombre del sql

	public function SpEstilosMacroCSS($datos,&$spnombre,&$sparam)
	{
		$datos['csstipocod'] = CSSTIPOMACRO;
		$datos['orderby'] = "cssdesc";
		parent::SpEstilosCSS($datos,$spnombre,$sparam);
		return true;	
	}

// Parámetros de Entrada:
//		Sin datos de entrada

// Retorna:
//		sparam,spnombre: Parametros del sql y nombre del sql

	public function SpEstilosColumnaCSS($datos,&$spnombre,&$sparam)
	{
		$datos['csstipocod'] = CSSTIPOCOLUMNA;
		$datos['orderby'] = "cssdesc";
		parent::SpEstilosCSS($datos,$spnombre,$sparam);
		return true;	
	}


// Parámetros de Entrada:
//		Sin datos de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TraerEstilosCSS($datos,&$resultado,&$numfilas)
	{
		$datos['orderby'] = "macrocssdesc";
		if (!parent::TraerEstilosCSS ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}



}
?>