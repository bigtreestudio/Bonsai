<?php  
include(DIR_CLASES_DB."cPlantillasMacros.db.php");

class cPlantillasMacros extends cPlantillasMacrosdb	
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
// Trae los datos de la zona por codigo de plantilla y nombre de zona

// Parámetros de Entrada:
//		datos: arreglo de datos
//			plantcod = codigo de la Plantilla
//			zonanombre = nombre de la zona

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function TraerxPlantilla($datos,&$resultado,&$numfilas)
	{
		if (!parent::TraerxPlantilla ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}


// Trae los datos de la zona por codigo de plantilla y nombre de zona

// Parámetros de Entrada:
//		datos: arreglo de datos
//			plantcod = codigo de la Plantilla
//			zonanombre = nombre de la zona

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function TraerxArea($datos,&$resultado,&$numfilas)
	{
		if (!parent::TraerxArea ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			plantmacrocod = codigo de la plantilla - macro

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de la plantilla -macro

// Parámetros de Entrada:

// Retorna:
//		resultado= Arreglo con todos los datos de una  plantilla - macro.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{

		$sparam=array(
			'xplantdesc'=> 0,
			'plantdesc'=> "",
			'xmacrodesc'=> 0,
			'macrodesc'=> "",
			'orderby'=> "plantmacroorden ASC",
			'limit'=> ""
		);	
			
		if (isset ($datos['plantdesc']) && $datos['plantdesc']!="")
		{
			$sparam['plantdesc']= $datos['plantdesc'];
			$sparam['xplantdesc']= 1;
		}	
		if (isset ($datos['macrodesc']) && $datos['macrodesc']!="")
		{
			$sparam['macrodesc']= $datos['macrodesc'];
			$sparam['xmacrodesc']= 1;
		}

		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
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
		$datos['plantmacroorden']= $proxorden;

		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		$oMacrosEstructuras = new cMacrosEstructuras($this->conexion,$this->formato);	
		$oPlantillasMacrosZonas = new cPlantillasMacrosZonas($this->conexion,$this->formato);	
		if(!$oMacrosEstructuras->BuscarxMacro($datos,$resultado,$numfilas))
			return false;

		$datosinsertar['plantcod'] = $datos['plantcod'];
		$datosinsertar['macrocod'] = $datos['macrocod'];
		$datosinsertar['plantmacrocod'] = $codigoinsertado;
		while ($datoscol = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			$datosinsertar['estructuracod'] = $datoscol['estructuracod'];
			if(!$oPlantillasMacrosZonas->Insertar($datosinsertar,$zonacod))
				return false;
		}	
		
			
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un formato

// Parámetros de Entrada:
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Modificar($datos)
	{
		if (!$this->_ValidarDatosModificar($datos))
			return false;
		
		$datos['plantmacrodatos'] = json_encode($datos);
		if(!parent::Modificar($datos))
			return false;
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 

// Eliminar un formato multimedia
// Parámetros de Entrada:
//		datos: arreglo de datos
//			formatocod = codigo del formato

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminarDatos($datos))
			return false;
			
		$oPlantillasMacrosZonas = new cPlantillasMacrosZonas($this->conexion,$this->formato);	
		if(!$oPlantillasMacrosZonas->Eliminar($datos))
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
	public function BuscarPlantillaMacroUltimoOrden($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarPlantillaMacroUltimoOrden($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		plantmacrocod: 

// Retorna:
//		resultado= Arreglo con el maximo orden de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarModulosxplantmacrocod($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModulosxplantmacrocod($datos,$resultado,$numfilas))
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
		$datosmodif['plantmacroorden'] = 1;
		$datosmodif['areacod'] = $datos['areacod'];
		foreach ($datos['plantmacrocod'] as $plantmacrocod)
		{
			$datosmodif['plantmacrocod'] = $plantmacrocod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['plantmacroorden']++;
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
		if (!isset($datos['areacod']) || $datos['areacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar un area.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
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
		
		
		if (!$this->BuscarModulosxplantmacrocod($datos,$resultadomac,$numfilasmac))
			return false;
		
		if ($numfilasmac>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, el macro tiene columnas relacionadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			

		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error plantilla - macro inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
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
		if (!$this->BuscarPlantillaMacroUltimoOrden($datos,$resultado,$numfilas))
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