<?php  
include(DIR_CLASES_DB."cGraficosFilas.db.php");

class cGraficosFilas extends cGraficosFilasdb	
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


	//------------------------------------------------------------------------------------------	
	// Retorna las filas de un grafico
	
	// Parámetros de Entrada:
	
	// Retorna:
	//		resultado= Arreglo con todos los datos de un album.
	//		numfilas= cantidad de filas 
	//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxGrafico($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxGrafico($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 



	//------------------------------------------------------------------------------------------	
	// Retorna las filas de un grafico
	
	// Parámetros de Entrada:
	//		filacod: codigo de la fila a buscar
	//		graficocod: codigo del grafico
	
	// Retorna:
	//		resultado= Arreglo con todos los datos de un album.
	//		numfilas= cantidad de filas 
	//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxCodigoxGrafico($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigoxGrafico($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar una nueva fila

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
		
		$this->ObtenerProximoOrden($datos,$proxorden);
		$datos['filaorden'] = $proxorden;
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;

		return true;
		
	
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar una columna

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

// Elimina una columna

// Parámetros de Entrada:
//		datos: arreglo de datos
//			columnacod = codigo de la fila

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		
		if (!$this->_ValidarEliminar($datos))
			return false;

		$oGraficosValores = new cGraficosValores($this->conexion,$this->formato);
		if(!$oGraficosValores->EliminarxFila($datos))
			return false;

		$oGraficosValores = new cGraficosValoresPorcentajes($this->conexion,$this->formato);
		if(!$oGraficosValores->EliminarxFila($datos))
			return false;

		if (!parent::Eliminar ($datos))
			return false;
			
		return true;	
	}	



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Elimina una fila

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

// Modifica el orden de las columnas

// Parámetros de Entrada:
//		datos: arreglo de datos
//			graficocod = codigo del grafico
//			columna = codigos de las columnas

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarOrden($datos)
	{
		$datosmodif['filaorden'] = 1;
		$datosmodif['graficocod'] = $datos['graficocod'];
		$arreglofilas = explode(",",$datos['orden']);
		foreach ($arreglofilas as $fila)
		{
			$datosmodif['filacod'] = $fila;
			if (!parent::ModificarOrden($datosmodif))
				return false;
			$datosmodif['filaorden']++;
		}
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

		if (!$this->BuscarxCodigoxGrafico($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, fila inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de modificar una fila

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarEliminar($datos)
	{
		
		if (!$this->BuscarxCodigoxGrafico($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, fila inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

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

		if ($datos['filatitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un titulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		

		return true;
	}


	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarFilaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}


}// FIN CLASE

?>