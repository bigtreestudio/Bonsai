<?php  
include(DIR_CLASES_DB."cGraficosValores.db.php");

class cGraficosValores extends cGraficosValoresdb	
{
	protected $conexion;
	protected $formato;


	
//-----------------------------------------------------------------------------------------
//  LAS FUNCIONES QUE HASTA AHORA TIENEN ESTO SON:  
// 	ArregloHijos
//  TieneHijos
//  TraerDatosCategoria
//-----------------------------------------------------------------------------------------

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


//------------------------------------------------------------------------------------------	
	// Retorna los valores de una fila de un grafico
	
	// Parámetros de Entrada:
	//		filacod: valores de la fila a buscar
	//		graficocod: valores del grafico a buscar
	
	// Retorna:
	//		resultado= Arreglo con todos los datos de un album.
	//		numfilas= cantidad de filas 
	//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxGraficoxFila($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxGraficoxFila($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
	
//------------------------------------------------------------------------------------------	
	// Retorna los valores de una fila de un grafico
	
	// Parámetros de Entrada:
	//		filacod: valores de la fila a buscar
	//		graficocod: valores del grafico a buscar
	//		columnacod: columna del valor a buscar
	
	// Retorna:
	//		resultado= Arreglo con todos los datos de un album.
	//		numfilas= cantidad de filas 
	//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
	
	
	
	
	
	function Actualizar($datos)
	{
		if(!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas==0)
		{
			if(!$this->Insertar($datos))
				return false;
		}else
		{
			if(!$this->Modificar($datos))
				return false;
		}	
		
		return true;	
	}
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar un valor

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
		
		if (!parent::Insertar($datos))
			return false;

		return true;
		
	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar un valor

// Parámetros de Entrada:
//		datos: arreglo de datos
//			columnacod = codigo de la fila

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar ($datos))
			return false;
			
		return true;	
	}	
	




//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar un valor

// Parámetros de Entrada:
//		datos: arreglo de datos
//			columnacod = codigo de la fila

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarxColumna($datos)
	{
		if (!parent::EliminarxColumna ($datos))
			return false;
			
		return true;	
	}	
	



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar un valor

// Parámetros de Entrada:
//		datos: arreglo de datos
//			columnacod = codigo de la fila

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarxFila($datos)
	{
		if (!parent::EliminarxFila ($datos))
			return false;
			
		return true;	
	}	
	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Elimina un valor

// Parámetros de Entrada:
//		datos: arreglo de datos
//			graficocod = codigo del grafico

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarxGrafico($datos)
	{
		if (!parent::EliminarxGrafico ($datos))
			return false;
			
		return true;	
	}	



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de insertar una fila

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarInsertar($datos)
	{
		
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de modificar una fila

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarModificar($datos)
	{
		
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos los datos basicos de una fila

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
//			filatitulocod = titulo de la columna

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarDatosVacios($datos)
	{

		if (!isset($datos['graficocod']) || $datos['graficocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		if (!isset($datos['filacod']) || $datos['filacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar una fila. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		if (!isset($datos['columnacod']) || $datos['columnacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar una columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		

		return true;
	}



}// FIN CLASE

?>