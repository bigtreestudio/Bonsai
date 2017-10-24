<?php  
include(DIR_CLASES_DB."cFrases.db.php");

class cFrases extends cFrasesdb	
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
//	datos de entradas :
//   limit order by

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "fraseorden ASC",
			'limit'=> ""
		);	
			
		$sparam=array(
			'xfraseautor'=> 0,
			'fraseautor'=> "",
			'xfrasedesclarga'=> 0,
			'frasedesclarga'=> "",
			'orderby'=> "frasecod desc",
			'limit'=> ""
		);	
		
		if (isset ($datos['fraseautor']) && $datos['fraseautor']!="")
		{
			$sparam['fraseautor']= $datos['fraseautor'];
			$sparam['xfraseautor']= 1;
		}	
		if (isset ($datos['frasedesclarga']) && $datos['frasedesclarga']!="")
		{
			$sparam['frasedesclarga']= $datos['frasedesclarga'];
			$sparam['xfrasedesclarga']= 1;
		}	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
			
		return true;			
	}



// Trae los datos de la tapa por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			frasecod = codigo de la frrase

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}



// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function EliminarFrase($datos)
	{
		//if (!$this->_ValidarEliminar($datos))
			//return false;
			
		if (!parent::EliminarFrase($datos))
			return false;
		return true;
	}

//
	public function ModificarFrase($datos)
	{	
		//if (!$this->_ValidarModificar($datos))
			//return false;
			
			
		if(!parent::ModificarFrase($datos))
			return false;

		
		return true;
	} 




	public function InsertarFrase($datos,&$frasecod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['fraseestado'] = ACTIVO;	
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['fraseorden']= $proxorden;
		
		if(!parent::InsertarFrase($datos,$frasecod))
			return false;
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de la frase

// Parámetros de Entrada:
//		frasecod = codigo de las frases.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!$this->BuscarFraseUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}	
	
// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarFrase($datos)
	{
		
		$datosmodificar['frasecod'] = $datos['frasecod'];
		$datosmodificar['fraseestado'] = NOACTIVO;
		if (!$this->ModificarEstadoFrase($datosmodificar))
			return false;
		
		return true;
	}
	
	// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function ActivarFrase($datos)
	{
		
		$datosmodificar['frasecod'] = $datos['frasecod'];
		$datosmodificar['fraseestado'] = ACTIVO;
		if (!$this->ModificarEstadoFrase($datosmodificar))
			return false;
		
		return true;
	}
	
// Retorna true o false si pudo cambiarle el estado de la tapa
// Parámetros de Entrada:
//		tapacod = codigo de la tapa.
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarEstadoFrase($datos)
	{
		if (!parent::ModificarEstadoFrase($datos))
			return false;
			
		return true;	
	}


//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los albumes

// Parámetros de Entrada:
//		albumorden = orden de los albums.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$arreglofrase = explode(",",$datos['fraseorden']);
		
		$datosmodif['fraseorden'] = 1;
		foreach ($arreglofrase as $frasecod)
		{
			$datosmodif['frasecod'] = $frasecod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['fraseorden']++;
		}
		
		return true;
	}
	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

// Retorna true o false al modificar si alguno de los campos esta vacio.

	private function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una frase valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}
	

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una frase valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		
		return true;
	}
	
	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['fraseautor']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un autor. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['frasedesclarga']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar descripcion de la frase. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
	
		return true;
	}


	
}//FIN CLASS
?>