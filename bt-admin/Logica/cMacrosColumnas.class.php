<?php 
include(DIR_CLASES_DB."cMacrosColumnas.db.php");

class cMacrosColumnas extends cMacrosColumnasdb	
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




	public function ColumnasSP($datos,&$spnombre,&$sparam)
	{
		parent::ColumnasSP ($datos,$spnombre,$sparam);
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
			'columnacod'=> $datos['columnacod'],
			'xestructuracod'=> 0,
			'estructuracod'=> ""
		);	
		
		if (isset ($datos['estructuracod']) && $datos['estructuracod']!="")
		{
			$sparam['estructuracod']= $datos['estructuracod'];
			$sparam['xestructuracod']= 1;
		}
		
		if (!parent::BuscarxCodigo ($sparam,$resultado,$numfilas))
			return false;
		return true;	
	}
//-------------------------------------------------------------------------------
// Parámetros de Entrada:
//		datos: arreglo de datos
//			estructuracod = codigo de la estructura

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxEstructura($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'estructuracod'=> $datos['estructuracod']
		);	
		
		if (!parent::BuscarxEstructura ($sparam,$resultado,$numfilas))
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
			'xcolumnacod'=> 0,
			'columnacod'=> "",
			'xestructuracod'=> 0,
			'estructuracod'=> "",
			'xcolumnadesc'=> 0,
			'columnadesc'=> "",
			'xestructuradesc'=> 0,
			'estructuradesc'=> "",
			'orderby'=> "c.columnacod ASC",
			'limit'=> ""
			);
		
		if (isset ($datos['columnacod']) && $datos['columnacod']!="")
		{
			$sparam['columnacod']= $datos['columnacod'];
			$sparam['xcolumnacod']= 1;
		}
		if (isset ($datos['estructuracod']) && $datos['estructuracod']!="")
		{
			$sparam['estructuracod']= $datos['estructuracod'];
			$sparam['xestructuracod']= 1;
		}

		if (isset ($datos['columnadescbusqueda']) && $datos['columnadescbusqueda']!="")
		{
			$sparam['columnadesc']= $datos['columnadescbusqueda'];
			$sparam['xcolumnadesc']= 1;
		}

		if (isset ($datos['estructuradesc']) && $datos['estructuradesc']!="")
		{
			$sparam['estructuradesc']= $datos['estructuradesc'];
			$sparam['xestructuradesc']= 1;
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
// Retorna true o false al si puede eliminar o no

// Parámetros de Entrada:
//	macrocod= codigo de macro para buscar en las columnas

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeEliminar($datos,$muestromsg)
	{
		
		$oMacrosColumnasEstructuras = new cMacrosColumnasEstructuras($this->conexion,$this->formato);
		if(!$oMacrosColumnasEstructuras->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas > 0)
		{
			if($muestromsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la macro columna tiene estructuras asignadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
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
		if(!$this->PuedeEliminar($datos,true))
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
		if ($datos['columnadesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un descripción de la macro. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error macro inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		$oMacrosColumnasEstructuras = new cMacrosColumnasEstructuras($this->conexion,$this->formato);
	
		if(!$oMacrosColumnasEstructuras->BuscarxColumna($datos,$resultado,$numfilas))
			return false;

		if ($numfilas > 0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la macro tiene estructuras asignadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
		return true;
	}	

			
}//FIN CLASE

?>