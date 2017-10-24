<?php 
include(DIR_CLASES_DB."cMacrosColumnasEstructuras.db.php");

class cMacrosColumnasEstructuras extends cMacrosColumnasEstructurasdb	
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

	public function BuscarxColumna($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxColumna ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}



// Parámetros de Entrada:
//		datos: arreglo de datos
//			macrocod = codigo de la plantilla - macro

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'colestructuracod'=> "",
			'xcolestructuracod'=>0,
			'xcolumnacod'=>0,
			'columnacod'=> ""
			);

		if (isset ($datos['colestructuracod']) && $datos['colestructuracod']!="")
		{
			$sparam['colestructuracod']= $datos['colestructuracod'];
			$sparam['xcolestructuracod']= 1;
		}
		if (isset ($datos['columnacod']) && $datos['columnacod']!="")
		{
			$sparam['columnacod']= $datos['columnacod'];
			$sparam['xcolumnacod']= 1;
		}
		
		if (!parent::BuscarxCodigo ($sparam,$resultado,$numfilas))
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
			'columnacod'=> $datos['columnacod'],
			'xcolestructuraclass'=> 0,
			'colestructuraclass'=> "",
			'xcolumnadesc'=> 0,
			'columnadesc'=> "",
			'orderby'=> "e.colestructuraorden ASC",
			'limit'=> ""
			);

		if (isset ($datos['colestructuraclass']) && $datos['colestructuraclass']!="")
		{
			$sparam['colestructuraclass']= $datos['colestructuraclass'];
			$sparam['xcolestructuraclass']= 1;
		}
		
		if (isset ($datos['columnadesc']) && $datos['columnadesc']!="")
		{
			$sparam['columnadesc']= $datos['columnadesc'];
			$sparam['xcolumnadesc']= 1;
		}


		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;

		return true;
	}
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:

// Retorna:
//		resultado= Arreglo con el maximo orden del album de galeria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarUltimoOrden($datos,&$resultado,&$numfilas)
	{
		
		if (!parent::BuscarUltimoOrden($datos,$resultado,$numfilas))
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
		$datos['colestructuraorden']= $proxorden;
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un formato

// Parámetros de Entrada:
//			formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Modificar($datos)
	{
		if (!$this->_ValidarDatosModificar($datos))
			return false;
		
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
			
		if(!parent::Eliminar($datos))
			return false;
		
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
		if ($datos['colestructuradesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_OK,"Error, debe ingresar una descripción. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($datos['colestructuraclass']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_OK,"Error, debe ingresar una descripción. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
// Retorna true o false al si puede eliminar o no

// Parámetros de Entrada:
//	macrocod= codigo de macro para buscar en las columnas

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeEliminar($datos,$muestromsg)
	{
		$oMacrosEstructuras = new cMacrosEstructuras($this->conexion,$this->formato);
		if(!$oMacrosEstructuras->BuscarxMacro($datos,$resultado,$numfilas))
			return false;
		if ($numfilas > 0)
		{
			if($muestromsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la macro tiene estructuras asignadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error columna estructura inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}	

//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los albumes

// Parámetros de Entrada:
//		albumcod = codigo del album
//		galeriacod = codigo de la galeria
//		albumgaleriaorden = orden de los albums.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		
		$arreglomacrocol = explode(",",$datos['orden']);
		$datosmodif['columnacod'] = $datos['columnacod'];
		$datosmodif['colestructuraorden'] = 1;
		foreach ($arreglomacrocol as $colestructuracod)
		{
			$datosmodif['colestructuracod'] = $colestructuracod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['colestructuraorden']++;
		}
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden del album

// Parámetros de Entrada:
//		albumcod = codigo del album.


// Retorna:
//		proxorden= el proximo mayor orden del album de galeria.
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!$this->BuscarUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 				
}//FIN CLASE

?>