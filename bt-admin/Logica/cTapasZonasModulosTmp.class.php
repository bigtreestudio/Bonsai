<?php  
include(DIR_CLASES_DB."cTapasZonasModulosTmp.db.php");

class cTapasZonasModulosTmp extends cTapasZonasModulosTmpdb	
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



//----------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------- 
// Inserta los datos del modulo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			datos= datos a modificar

// Retorna:

	
	public function Insertar($datos)
	{
		$datos['modulodata'] = json_encode($datos);
		if (!isset($datos['modulonombre']) || $datos['modulonombre']=="")
			$datos['modulonombre']="NULL";
		if (!parent::Insertar ($datos))
			return false;
		return true;			
	}


//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo Temporal

// Parámetros de Entrada:
//		datos: arreglo de datos


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModulosTmp($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModulosTmp ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo Temporal

// Parámetros de Entrada:
//		datos: arreglo de datos
//		modulompcod: modulompcod


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModulosTmpxModuloTmpcod($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModulosTmpxModuloTmpcod ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}	
	
//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo Temporal

// Parámetros de Entrada:
//		datos: arreglo de datos
//		modulompcod: modulompcod


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Eliminar($datos)
	{
		if (!parent::Eliminar ($datos))
			return false;
		return true;			
	}	
}
?>