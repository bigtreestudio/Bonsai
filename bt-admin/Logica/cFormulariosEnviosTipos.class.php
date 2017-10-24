<?php  
include(DIR_CLASES_DB."cFormulariosEnviosTipos.db.php");

class cFormulariosEnviosTipos extends cFormulariosEnviosTiposdb	
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
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function FormulariosEnviosTiposSP(&$resultado,&$numfilas)
	{
		if (!parent::FormulariosEnviosTiposSP ($resultado,$numfilas))
			return false;
		return true;			
	}

// Trae el tipo de tapa por codigo

// Parámetros de Entrada:
//	enviotipo = Codigo del tipo de tapa

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
}//FIN CLASS
?>