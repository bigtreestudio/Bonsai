<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
include(DIR_CLASES_DB."cTopTipos.db.php");

class cTopTipos extends cTopTiposdb	
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
//----------------------------------------------------------------------------------------- 

// Trae los datos de la tabla TOP_TOP_TIPOS 

// Retorna una consulta con los datos del tipo de top

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarTopTipoxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarTopTipoxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

//Busca top tipo

// Retorna una consulta con los datos de todos los tipos de top

// Parámetros de Entrada:
//		datos: arreglo de datos
//		topcod = codigo del top

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarTopTipo($datos,&$resultado,&$numfilas)
	{
		
		if (!parent::BuscarTopTipo ($datos,$resultado,$numfilas))
			return false;
		return true;		
	}


}//fin clase	

?>
