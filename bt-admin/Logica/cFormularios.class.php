<?php  
include(DIR_CLASES_DB."cFormularios.db.php");

class cFormularios extends cFormulariosdb	
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
// Trae los formularios

// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}


// Trae los formularios

// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarDatosxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarDatosxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
//	datos de entradas :
//   limit order by

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		
		$sparam=array(
			'xformularionombre'=> 0,
			'formularionombre'=> "",
			'xformulariotipocod'=> 0,
			'formulariotipocod'=> "",							
			'orderby'=> "formulariodatoscod desc",
			'limit'=> ""
		);	
		
		if (isset ($datos['formularionombre']) && $datos['formularionombre']!="")
		{
			$sparam['formularionombre']= $datos['formularionombre'];
			$sparam['xformularionombre']= 1;
		}	
	
		if (isset ($datos['formulariotipocod']) && $datos['formulariotipocod']!="")
		{
			$sparam['formulariotipocod']= $datos['formulariotipocod'];
			$sparam['xformulariotipocod']= 1;
		}	
		
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}

	public function BuscarTiposFormulariosSP($datos,&$spnombre,&$sparam)
	{
		if (!parent::BuscarTiposFormulariosSP($datos,$spnombre,$sparam))
			return false;
		return true;			
	}


	
}//FIN CLASS
?>