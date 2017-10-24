<?php  
include(DIR_CLASES_DB."cRevistaTapasTipos.db.php");

class cRevistaTapasTipos extends cRevistaTapasTiposdb	
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

// Trae las tapas

//	datos de entradas :
//   limit order by

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "revtapatiponombre ASC",
			'limit'=> ""
		);	
		$sparam=array(
			'xrevtapatipoestado'=> 0,
			'revtapatipoestado'=> "",
			'orderby'=> "revtapatiponombre asc",
			'limit'=> ""
		);	
		
		if (isset ($datos['revtapatipoestado']) && $datos['revtapatipoestado']!="")
		{
			$sparam['revtapatipoestado']= $datos['revtapatipoestado'];
			$sparam['xrevtapatipoestado']= 1;
		}	

		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
			
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
			
		return true;			
	}


// Trae los datos del tipo de la tapa por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			revtapatipocod = codigo identificatorio

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