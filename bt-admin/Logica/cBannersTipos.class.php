<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
include(DIR_CLASES_DB."cBannersTipos.db.php");

class cBannersTipos extends cBannersTiposdb	
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

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Trae los datos de la tabla BAN_BANNERS_TIPOS 

// Retorna una consulta con los datos del tipo de banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//		    bannertipocod: código del tipo de banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarBannerTipoxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarBannerTipoxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

//Busca banner tipo

// Retorna una consulta con los datos de todos los tipos de banner

// Parámetros de Entrada:
//		datos: arreglo de datos

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarBannerTipo($datos,&$resultado,&$numfilas)
	{
		
		if (!parent::BuscarBannerTipo ($datos,$resultado,$numfilas))
			return false;
		return true;		
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un formato multimedia

// Parámetros de Entrada:
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{

		$sparam=array(
			'xbannertipodesc'=> 0,
			'bannertipodesc'=> "",
			'xbanneralto'=> 0,
			'banneralto'=> "",
			'xbannerancho'=> 0,
			'bannerancho'=> "",
			'orderby'=> "bannertipocod ASC",
			'limit'=> ""
			);	

			
		if (isset ($datos['bannertipodesc']) && $datos['bannertipodesc']!="")
		{
			$sparam['bannertipodesc']= $datos['bannertipodesc'];
			$sparam['xbannertipodesc']= 1;
		}	
		if (isset ($datos['banneralto']) && $datos['banneralto']!="")
		{
			$sparam['banneralto']= $datos['banneralto'];
			$sparam['xbanneralto']= 1;
		}
		if (isset ($datos['bannerancho']) && $datos['bannerancho']!="")
		{
			$sparam['bannerancho']= $datos['bannerancho'];
			$sparam['xbannerancho']= 1;
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
// Inserta nuevo tipo de banner

// Parámetros de Entrada:
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner
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
// Modifica los datos de un tipo de banner

// Parámetros de Entrada:
 //		    bannertipocod: código del tipo de banner
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner
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

// Eliminar un tipo de banner
// Parámetros de Entrada:
//		datos: arreglo de datos
 //		    bannertipocod: código del tipo de banner

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
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if (isset($datos['bannertipodesc']) && $datos['bannertipodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['bannerancho']) && $datos['bannerancho']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un ancho. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['bannerancho'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un ancho valido (solo número).",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if (isset($datos['banneralto']) && $datos['banneralto']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un alto. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['banneralto'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un alto valido (solo número).",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite 

// Parámetros de Entrada:
//		formatodesc: descripción del formato
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite 

// Parámetros de Entrada:
 //		    bannertipocod: código del tipo de banner
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		if (!$this->BuscarBannerTipoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error tipo de banner inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el tipo de banner

// Parámetros de Entrada:
//		    bannertipocod: código del tipo de banner
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos)
	{
		
		if (!$this->BuscarBannerTipoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error tipo de banner inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}	

}//fin clase	

?>