<?php  
include(DIR_CLASES_DB."cPlantillasMacrosZonasColumnas.db.php");

class cPlantillasMacrosZonasColumnas extends cPlantillasMacrosZonasColumnasdb	
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
// Trae los macros pertenecientes a una platnilla

// Parámetros de Entrada:
//		datos: arreglo de datos
//			plantcod = codigo de la Plantilla
//			zonanombre = nombre de la zona

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarZonasColumnasxMacrozonacod($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarZonasColumnasxMacrozonacod ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Trae los datos del macro por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			macrozonacod = codigo del macro zona

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
// Inserta nuevo formato

// Parámetros de Entrada:
//			formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;
						
		$oPlantillasMacrosZonas = new cPlantillasMacrosZonas($this->conexion,$this->formato);	
		if(!$oPlantillasMacrosZonas->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosmacrozona = $this->conexion->ObtenerSiguienteRegistro($resultado);	
			
		$this->ObtenerProximoOrden($datos,$proxorden);
		$datos['plantmacroorden'] = $proxorden;
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		$oColumnas = new cMacrosColumnasEstructuras($this->conexion,$this->formato);
		if(!$oColumnas->BuscarxColumna($datos,$resultado,$numfilas))
			return false;

		$datosinsertar['plantmacrocolumnacod'] = $codigoinsertado;		
		$datosinsertar['plantcod'] = $datosmacrozona['plantcod'];
		$datosinsertar['macrocod'] = $datosmacrozona['macrocod'];
		$oPlantillasZonas = new cPlantillasZonas($this->conexion,$this->formato);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			$datosinsertar['colestructuracod'] = $fila['colestructuracod'];
			if(!$oPlantillasZonas->Insertar($datosinsertar,$zonacod))
				return false;
		}
		return true;
	} 


//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de las plantillas - macro

// Parámetros de Entrada:
//		plantmacroorden = orden de las plantillas - macro.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$datosmodif['plantmacroorden'] = 1;
		foreach ($datos['plantmacrocolumnacod'] as $plantmacrocolumnacod)
		{
			$datosmodif['plantmacrocolumnacod'] = $plantmacrocolumnacod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['plantmacroorden']++;
		}
		
		return true;
	}	


//----------------------------------------------------------------------------------------- 
// Eliminar un macro

// Parámetros de Entrada:
//			plantmacrocod: codigo del plantmacrocod
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminarDatos($datos))
			return false;
		
		$oPlantillasZonas = new cPlantillasZonas($this->conexion,$this->formato);
		if(!$oPlantillasZonas->Eliminar($datos))
			return false;
			
		if(!parent::Eliminar($datos))
			return false;
		
		return true;	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//		formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	 	 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar si puede o no eliminar una zona del macro

// Parámetros de Entrada:
//		    formatocod: código del formato
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos)
	{
		//validar que no tenga columnas
			
		return true;
	}	
	
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de la plantilla -macro

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!$this->BuscarUltimoOrdenxMacrozonaCod($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}

}
?>