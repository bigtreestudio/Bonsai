<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de los archivos multimedia del banner

include(DIR_CLASES_DB."cBannersMultimedia.db.php");

class cBannersMultimedia extends cBannersMultimediadb	
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
// Retorna una consulta con los datos del multimedia por galeria y multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo de lal banner 
//			multimediacod = codigo del archivo multimedia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarBannerxCodigoBannerxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarBannerxCodigoBannerxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;

		return true;
	}

	

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los archivos multimedia del banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarBannersxCodigoBanner($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarBannersxCodigoBanner($datos,$resultado,$numfilas))
			return false;

		return true;
	}



//----------------------------------------------------------------------------------------- 
// Genera un nuevo archivo multimedia y lo inserta banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarArchivo($datos)
	{
		$datos['multimediaconjuntocod'] = FOTOS;
		$datos['multimediadesc'] = "NULL";
		$datos['multimedianombre'] = $datos['multimedianombre'];
		$datos['multimediaubic'] = $datos['multimediaubic'];
		$datos['multimediacatcod'] = 2;
		$oMultimedia = new cMultimedia($this->conexion,CARPGALERIAS,$this->formato);
		if(!$oMultimedia->InsertarImagen($datos,$multimediacod))
			return false;
		
		$datos['multimediacod'] = $multimediacod;
		if (!$this->Insertar($datos))
			return false;
			
		return true;	
	}



//----------------------------------------------------------------------------------------- 
// Insertar Multimedia a un banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner
//			multimediacod = codigo multimedia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['bannermultimediafalta'] = date("Y/m/d H:i:s");
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}





//----------------------------------------------------------------------------------------- 
// Eliminar Multimedia a una galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner
//			multimediacod = codigo multimedia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
		return true;	
	}





	private function _ValidarInsertar($datos)
	{

		return true;
	}
	
	

	private function _ValidarEliminar($datos)
	{


		return true;
	}


}//FIN CLASE

?>