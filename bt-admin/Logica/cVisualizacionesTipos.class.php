<?php 
include(DIR_CLASES_DB."cVisualizacionesTipos.db.php");

class cVisualizacionesTipos extends cVisualizacionesTiposdb	
{


	protected $conexion;
	protected $formato;
	private $prefijo_archivo = "noticia_";
	
	
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



// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function VisualizacionTiposSP(&$resultado,&$numfilas)
	{
		if (!parent::VisualizacionTiposSP ($resultado,$numfilas))
			return false;
		return true;			
	}
	
// Trae el tipo de tapa por codigo

// Parámetros de Entrada:
//	tapatipocod = Codigo del tipo de tapa

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
	
//----------------------------------------------------------------------------------------- 
	
}//FIN CLASE

?>