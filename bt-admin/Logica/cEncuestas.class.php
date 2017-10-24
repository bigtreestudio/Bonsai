<?php  
include(DIR_CLASES_DB."cEncuestas.db.php");

class cEncuestas extends cEncuestasdb	
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

// Trae las encuestas

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
	
	
	
	
// Trae las encuestas por filtros

// Parámetros de Entrada:
//		datos: arreglo de datos
//			encuestapregunta = pregunta (la busqueda se realiza con un like)
//			encuestatipocod = Tipo de encuesta
//			catcod = Codigo de la categoria
//			orderby = orden de los resultados, (opcional, por default se ordena por el campo encuestacod)
//			limit = limite de la consulta (opcional)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xencuestapregunta'=> 0,
			'encuestapregunta'=> "",
			'xencuestatipocod'=> 0,
			'encuestatipocod'=> "",
			'xencuestaestado'=> 0,
			'encuestaestado'=> "",			
			'xcatcod'=> 0,
			'catcod'=> "",					
			'orderby'=> "encuestacod desc",
			'limit'=> ""
		);	
		
		if (isset ($datos['encuestapregunta']) && $datos['encuestapregunta']!="")
		{
			$sparam['encuestapregunta']= $datos['encuestapregunta'];
			$sparam['xencuestapregunta']= 1;
		}	
		if (isset ($datos['encuestaestado']) && $datos['encuestaestado']!="")
		{
			$sparam['encuestaestado']= $datos['encuestaestado'];
			$sparam['xencuestaestado']= 1;
		}		
		if (isset ($datos['encuestatipocod']) && $datos['encuestatipocod']!="")
		{
			$sparam['encuestatipocod']= $datos['encuestatipocod'];
			$sparam['xencuestatipocod']= 1;
		}	

		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
		}			
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}
	
	
	
	
// Trae las opciones de una encuesta

// Parámetros de Entrada:
//	encuestacod: Codigo de la encuesta

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarEncuestasOpciones($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarEncuestasOpciones ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}



// Trae la encuesta por codigo de encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
	
	
// Elimina una encuesta por codigo, y elimina la opciones de la encuesta.

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	
	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		$oEncuestasOpciones= new cEncuestasOpciones($this->conexion);
		if (!$oEncuestasOpciones->EliminarOpcionesxEncuesta($datos))
			return false;
		
		if (!parent::EliminarEncuesta($datos))
			return false;
		
		return true;
	}




// Modifica una encuesta por codigo

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;
			
		if(!parent::ModificarEncuesta($datos))
			return false;

		
		return true;
	} 




// Inserta una encuesta por codigo

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta

// Retorna:
//		encuestacod: Codigo de la encuesta insertado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$encuestacod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['encuestaestado'] = ACTIVO;	
		if(!parent::InsertarEncuesta($datos,$encuestacod))
			return false;
		
		return true;
	} 
	
	
	
// Modifica el estado a no activo de una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function DesActivarEncuesta($datos)
	{
		
		$datosmodificar['encuestacod'] = $datos['encuestacod'];
		$datosmodificar['encuestaestado'] = NOACTIVO;
		if (!$this->ModificarEstadoEncuesta($datosmodificar))
			return false;
		
		return true;
	}
	
	
// Modifica el estado a activo de una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActivarEncuesta($datos)
	{
		
		$datosmodificar['encuestacod'] = $datos['encuestacod'];
		$datosmodificar['encuestaestado'] = ACTIVO;
		if (!$this->ModificarEstadoEncuesta($datosmodificar))
			return false;
		
		return true;
	}
	
	
	
// Modifica el estado de una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta
//	encuestaestado = Estado

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ModificarEstadoEncuesta($datos)
	{
		if (!parent::ModificarEstadoEncuesta($datos))
			return false;
			
		return true;	
	}


// Valida los datos de alta de una encuesta

// Parámetros de Entrada:
//	encuestapregunta = Pregunta de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}



// Valida los datos al modificar una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta
//	encuestapregunta = Pregunta de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una encuesta valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	



		return true;
	}
	

// Valida los datos obligatorios

// Parámetros de Entrada:
//	encuestapregunta = Pregunta de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['encuestapregunta']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre en la encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}


// Valida los datos al eliminar una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de la encuesta

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una encuesta valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	

		$oEncuestasRespuestas= new cEncuestasRespuestas($this->conexion);
		if (!$oEncuestasRespuestas->BuscarporCodigoEncuesta($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La encuesta contiene votos asociados. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}			
	
		return true;
	}
	
}//FIN CLASS
?>