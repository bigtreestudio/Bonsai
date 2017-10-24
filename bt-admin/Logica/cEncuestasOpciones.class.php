<?php  
include(DIR_CLASES_DB."cEncuestasOpciones.db.php");

class cEncuestasOpciones extends cEncuestasOpcionesdb	
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


// Trae los datos de una opcion, por encuesta y codigo de opcion
// Parámetros de Entrada:
//		datos: arreglo de datos
//			encuestacod = codigo de la Encuesta
//			opcioncod = codigo de la Opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
	
	
// Trae todas las opciones de una encuesta
// Parámetros de Entrada:
//		datos: arreglo de datos
//			encuestacod = codigo de la Encuesta (obligatorio)
//			orderby = orden de los resultados, (opcional, por default se ordena por el campo orden)
//			limit = limite de la consulta (opcional)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigoEncuestacod($datos,&$resultado,&$numfilas)
	{

		if ($datos['encuestacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		$datos['orderby'] = "opcionorden";
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$datos['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$datos['limit']= $datos['limit'];
		else
			$datos['limit']="";
				
		if (!parent::BuscarxCodigoEncuestacod ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}	
	
	
	

	
// Funcion que inserta una opcion a una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta
//	opcionnombre = Nombre de la opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$encuestacod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
		
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['opcionorden']= $proxorden;			
		if(!parent::Insertar($datos,$encuestacod))
			return false;
		
		return true;
	} 
	

	
// Funcion que modifica una opcion a una encuesta

// Parámetros de Entrada:
//	opciocodmodif = Codigo de encuesta a modificar
//	opcionnombre = Nombre de la opcion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{
		if(isset($datos["opciocodmodif"]) && $datos["opciocodmodif"] != "")
			$datos["opcioncod"]=$datos["opciocodmodif"];
		else
			return false;
			
		if (!$this->_ValidarModificar($datos))
			return false;
		
		if (!parent::Modificar($datos))
			return false;
			
		return true;	
	}


	
// Funcion que elimina una opcion a una encuesta

// Parámetros de Entrada:
//	opcioncod = Codigo de encuesta a eliminar
//	opcionnombre = Nombre de la opcion

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
	
	
// Funcion que elimina las opciones de una encuesta

// Parámetros de Entrada:
//	encuestacod = Codigo de encuesta 

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function EliminarOpcionesxEncuesta($datos)
	{
		
		if (!$this->_ValidarEliminarxEncuesta($datos))
			return false;

		if (!parent::EliminarOpcionesxEncuesta($datos))
			return false;
			
			
		return true;
	}

	
	
// Funcion que modifica el orden de las opciones de una encuesta

// Parámetros de Entrada:
//	opcionorden = Codigos de las opciones separados por coma.

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarOrden($datos)
	{
		$arregloencuestaorden = explode(",",$datos['opcionorden']);
		
		$datosmodif['opcionorden'] = 1;
		foreach ($arregloencuestaorden as $opcioncod)
		{
			$datosmodif['opcioncod'] = $opcioncod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['opcionorden']++;
		}
		
		return true;
	}



	
	


//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de las opciones

// Parámetros de Entrada:
//		encuestacod = codigo de la encuesta.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarEncuestaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
// Valida los datos a ingresar

// Parámetros de Entrada:
//		encuestacod = codigo de la encuesta.
//		opcionnombre = nombre de la opcion.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}
	
	
	

//----------------------------------------------------------------------------------------- 
// Valida los datos a modificar

// Parámetros de Entrada:
//		encuestacod = codigo de la encuesta.
//		opcioncod = codigo de la opcion.
//		opcionnombre = nombre de la opcion.

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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una opcion valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	
			
		return true;
	}
	

//----------------------------------------------------------------------------------------- 
// Valida los datos obligatorios

// Parámetros de Entrada:
//		encuestacod = codigo de la encuesta.
//		opcionnombre = nombre de la opcion.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['opcionnombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['encuestacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
	
		$oEncuesta = new cEncuestas($this->conexion, $this->formato);
		if(!$oEncuesta ->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, encuesta inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Valida los datos a eliminar
// 	Verifica que la opcion no tenga respuestas, si tiene al menos una no se podrá eliminar

// Parámetros de Entrada:
//		encuestacod = codigo de la encuesta.
//		opcioncod = codigo de la opcion.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una opcion valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	
			
		$oEncuestasRespuestas = new cEncuestasRespuestas($this->conexion,$this->formato);
		if(!$oEncuestasRespuestas->BuscarporCodigos($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la opción ya contiene votos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	
		
		return true;
	}
	
	

//----------------------------------------------------------------------------------------- 
// Valida los datos a eliminar

// Parámetros de Entrada:
//		encuestacod = codigo de la encuesta.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarxEncuesta($datos)
	{
		if ($datos['encuestacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		$oEncuesta = new cEncuestas($this->conexion, $this->formato);
		if(!$oEncuesta ->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, encuesta inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}
	
	
	
}//FIN CLASS
?>