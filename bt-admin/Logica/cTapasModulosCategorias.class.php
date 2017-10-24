<?php  
include(DIR_CLASES_DB."cTapasModulosCategorias.db.php");

class cTapasModulosCategorias extends cTapasModulosCategoriasdb	
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

// Trae los Modulos Categorias de las tapas

// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Buscar(&$resultado,&$numfilas)
	{
		if (!parent::Buscar ($resultado,$numfilas))
			return false;
		return true;			
	}
	
	
// Trae los Modulos Categorias de las tapas por tipo de modulo

// Parámetros de Entrada:
//	modulotipocod: Tipo de modulo

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarSPxTipo($datos,&$spnombre,&$sparam)
	{
		if (!parent::BuscarSPxTipo ($datos,$spnombre,$sparam))
			return false;
		return true;			
	}


// Trae los Modulos Categorias de las tapas

// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarSP(&$spnombre,&$sparam)
	{
		if (!parent::BuscarSP ($spnombre,$sparam))
			return false;
		return true;			
	}

// Trae los Modulos de las tapas

// Parámetros de Entrada:
//	catcod=codigo de la categoria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function SpModulosTapasxCodigo($datos,&$spnombre,&$sparam)
	{
		$datos['moduloestado']=ACTIVO;
		if (!parent::SpModulosTapasxCodigo($datos,$spnombre,$sparam))
			return false;
		return true;			
	}



// Trae los Modulos de las tapas

// Parámetros de Entrada:
//	catcod=codigo de la categoria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModulosTapasxCodigo($datos,&$spnombre,&$sparam)
	{
		$datos['moduloestado']=ACTIVO;
		if (!parent::BuscarModulosTapasxCodigo($datos,$spnombre,$sparam))
			return false;
		return true;			
	}


// Trae los datos de la categoria de un modulo de una tapa por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

// Trae los datos de la categoria de un modulo de una tapa por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzadaTapasNoticiasModulos($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xmodulodesc'=> 0,
			'modulodesc'=> "",
			'orderby'=> "modulodesc ASC",
			'limit'=> ""
		);	
			

		if (isset ($datos['modulodesc']) && $datos['modulodesc']!="")
		{
			$sparam['modulodesc']= $datos['modulodesc'];
			$sparam['xmodulodesc']= 1;
		}	
		
		
		if (!parent::BusquedaAvanzadaTapasNoticiasModulos ($sparam,$resultado,$numfilas))
			return false;

		return true;			
	}



}
?>