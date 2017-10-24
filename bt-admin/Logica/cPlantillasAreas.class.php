<?php  
include(DIR_CLASES_DB."cPlantillasAreas.db.php");

class cPlantillasAreas extends cPlantillasAreasdb	
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
// Trae las areas por codigo de plantilla

// Parámetros de Entrada:
//		datos: arreglo de datos
//			plantcod = codigo de la Plantilla

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function TraerxPlantilla($datos,&$resultado,&$numfilas)
	{
		if (!parent::TraerxPlantilla ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			areacod = codigo del area

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
		
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['areaorden']= $proxorden;

		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
			
		return true;
	} 




//----------------------------------------------------------------------------------------- 

// Eliminar un area
// Parámetros de Entrada:
//		datos: arreglo de datos


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminarDatos($datos))
			return false;
			
		if(!parent::Eliminar($datos))
			return false;
		
		return true;	
	}
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		albumsuperior: album superior a buscar.Si vale "", entonces retorna el raiz del album

// Retorna:
//		resultado= Arreglo con el maximo orden de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarUltimoOrdenAreaxPlantilla($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarUltimoOrdenAreaxPlantilla($datos,$resultado,$numfilas))
			return false;
		
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
		$orden = explode(",",$datos['orden']);
		$datosmodif['areaorden'] = 1;
		$datosmodif['plantcod'] = $datos['plantcod'];
		foreach ($orden as $areacod)
		{
			$datosmodif['areacod'] = $areacod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['areaorden']++;
		}
		
		return true;
	}	

//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if ($datos['areahtmlcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe seleccionar un area.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$oPlantillasAreasHtml = new cPlantillasAreasHtml($this->conexion,$this->formato);
		if(!$oPlantillasAreasHtml->TraerAreasHtmlxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe seleccionar un area valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
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
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    formatocod: código del formato
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error area inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		$oPlantillasMacros = new cPlantillasMacros($this->conexion,$this->formato);
		if (!$oPlantillasMacros->TraerxArea($datos,$resultadomac,$numfilasmac))
			return false;
		
		if ($numfilasmac>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, el area tiene macros asociados.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			

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
		if (!$this->BuscarUltimoOrdenAreaxPlantilla($datos,$resultado,$numfilas))
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