<?php  
include(DIR_CLASES_DB."cEncuestasRespuestas.db.php");

class cEncuestasRespuestas extends cEncuestasRespuestasdb	
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

// Trae los registros de las respuestas de las encuestas por codigo de encuesta y codigo de opcion

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta
//	opcioncod = Codigo de la opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarporCodigos($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarporCodigos ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
// Trae los registros de las respuestas de las encuestas por codigo de encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarporCodigoEncuesta($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarporCodigoEncuesta ($datos,$resultado,$numfilas))
			return false;
			
		return true;			
	}
	
	
	
// Trae los registros de las respuestas de las encuestas por codigo de encuesta y codigo de opcion

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta
//	opcioncod = Codigo de la opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarCantidadRespuestasxEncuesta($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarCantidadRespuestasxEncuesta ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
	
// Funcion que inserta un voto de una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta
//	opcioncod = Codigo de la opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Votar($datos,&$codigoinsertado)
	{
		if (!$this->ValidarInsertar($datos))
			return false;
		$datos['respuestafecha'] = date("Y/m/d H:i:s");
		$Browser = new Browser();
		$datos['respuestanavegador'] = $Browser->getBrowser();
		$datos['respuestaso'] = $Browser->getPlatform();
		$datos['respuestaip'] = $_SERVER['REMOTE_ADDR'];
		if (!parent::Insertar ($datos,$codigoinsertado))
			return false;
		
		return true;			
	}
	
	
// Funcion que valida un voto de una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta
//	opcioncod = Codigo de la opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	function ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		return true;
	}




// Funcion que valida los datos obligatorios que debe ingresar para insertar una respuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta
//	opcioncod = Codigo de la opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no


	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['opcioncod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una opcion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['encuestacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		$oEncuesta = new cEncuestasOpciones($this->conexion,$this->formato);		
		if (!$oEncuesta->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una opcion valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	

		return true;
	}
	
}//FIN CLASS	
?>