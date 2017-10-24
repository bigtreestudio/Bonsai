<?php  
include(DIR_CLASES_DB."cTapas.db.php");

class cTapas extends cTapasdb	
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
			'orderby'=> "catorden ASC",
			'limit'=> ""
		);	
			
		$sparam=array(
			'xtapanom'=> 0,
			'tapanom'=> "",
			'xtapatipocod'=> 0,
			'tapatipocod'=> "",
			'orderby'=> "tapacod desc",
			'limit'=> ""
		);	
		
		if (isset ($datos['tapanom']) && $datos['tapanom']!="")
		{
			$sparam['tapanom']= $datos['tapanom'];
			$sparam['xtapanom']= 1;
		}	
		if (isset ($datos['tapatipocod']) && $datos['tapatipocod']!="")
		{
			$sparam['tapatipocod']= $datos['tapatipocod'];
			$sparam['xtapatipocod']= 1;
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



// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function EliminarTapa($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		if (!parent::EliminarModulosTmpTapa($datos))
			return false;

		if (!parent::EliminarModulosTapa($datos))
			return false;

		if (!parent::EliminarTapa($datos))
			return false;

		return true;
	}

//
	public function ModificarTapa($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;
			
			
		if(!parent::ModificarTapa($datos))
			return false;

		
		return true;
	} 



//
	public function ModificarMetadata($datos)
	{	
		if (!$this->_ValidarModificarMetadata($datos))
			return false;
		
		$datos['tapametadata'] = json_encode(array_map("utf8_encode",$datos));	
		
		if(!parent::ModificarMetadata($datos))
			return false;
		
		return true;
	} 


	public function InsertarTapa($datos,&$tapacod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['tapaestado'] = ACTIVO;	
		if (!isset($datos['tapaarchivo']) || $datos['tapaarchivo']=="")
			$datos['tapaarchivo'] = "NULL";	

		if(!parent::InsertarTapa($datos,$tapacod))
			return false;
		
		return true;
	} 
	
	
	
// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarTapa($datos)
	{
		
		$datosmodificar['tapacod'] = $datos['tapacod'];
		$datosmodificar['tapaestado'] = NOACTIVO;
		if (!$this->ModificarEstadoTapa($datosmodificar))
			return false;
		
		return true;
	}
	
	// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function ActivarTapa($datos)
	{
		
		$datosmodificar['tapacod'] = $datos['tapacod'];
		$datosmodificar['tapaestado'] = ACTIVO;
		if (!$this->ModificarEstadoTapa($datosmodificar))
			return false;
		
		return true;
	}
	
// Retorna true o false si pudo cambiarle el estado de la tapa
// Parámetros de Entrada:
//		tapacod = codigo de la tapa.
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarEstadoTapa($datos)
	{
		if (!parent::ModificarEstadoTapa($datos))
			return false;
			
		return true;	
	}




	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if ($datos['plantcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
	
		$oPlantilla = new cPlantillas($this->conexion, $this->formato);
		if(!$oPlantilla ->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, plantilla inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}


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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una tapa valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una tapa valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}
	



// Retorna true o false al modificar si alguno de los campos esta vacio.

	private function _ValidarModificarMetadata($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una tapa valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}
	

	
	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['tapanom']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		if ($datos['tapatipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		$oTapasTipos = new cTapasTipos($this->conexion,$this->formato);
		if(!$oTapasTipos ->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de tapa inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}


	
}//FIN CLASS
?>