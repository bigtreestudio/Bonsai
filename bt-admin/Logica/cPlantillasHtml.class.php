<?php 
include(DIR_CLASES_DB."cPlantillasHtml.db.php");

class cPlantillasHtml extends cPlantillasHtmldb	
{


	protected $conexion;
	protected $formato;
	private $prefijo_archivo_header = "plantillaHeader_";
	private $prefijo_archivo_footer = "plantillaFooter_";
	private $extension_archivos = ".html";
	
	
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

// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function PlantillasHtmlSP(&$resultado,&$numfilas)
	{
		if (!parent::PlantillasHtmlSP ($resultado,$numfilas))
			return false;
		return true;			
	}

// Retorna el html de un codigo de html de plantilla

// Parámetros de Entrada:
//		datos: arreglo de datos
//			planthtmlcod = codigo de html de la plantilla

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		return true;
	}
	
		
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de la plantilla html

// Parámetros de Entrada:

// Retorna:
//		resultado= Arreglo con todos los datos de una  plantilla - macro.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{

		$sparam=array(
			'xplanthtmldesc'=> 0,
			'planthtmldesc'=> "",
			'orderby'=> "planthtmlcod ASC",
			'limit'=> ""
		);	
		
		if (isset ($datos['planthtmldesc']) && $datos['planthtmldesc']!="")
		{
			$sparam['planthtmldesc']= $datos['planthtmldesc'];
			$sparam['xplanthtmldesc']= 1;
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
// Inserta nuevo el html de la plantilla

// Parámetros de Entrada:
//			planthtmldesc: descripción de la plantilla html
//			planthtmladmin: html plantilla (admin)
//			planthtml: html plantilla (front)
//			planthtmldisco: ubicación de la plantilla
//			planthtmldefault: si es o no plantilla por default

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
		
		if ($datos['planthtmldefault']==1)
		{
			if(!parent::ResetearPlantillasDefault())
				return false;
		}
		
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de una plantilla html

// Parámetros de Entrada:
//			planthtmldesc: descripción de la plantilla html
//			planthtmladmin: html plantilla (admin)
//			planthtml: html plantilla (front)
//			planthtmldisco: ubicación de la plantilla
//			planthtmlcod: codigo de la plantilla html
//			planthtmldefault: si es o no plantilla por default

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;
		
		if ($datos['planthtmldefault']==1)
		{
			if(!parent::ResetearPlantillasDefault())
				return false;
		}
		
		if(!parent::Modificar($datos))
			return false;
			
		if(!$this->PublicarHtml($datos,$datos['planthtmlcod']))
			return false;
			
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 

// Eliminar una plantilla html
// Parámetros de Entrada:
//		datos: arreglo de datos
//			planthtmlcod: codigo de la plantilla html

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminar($datos))
			return false;

		if(!parent::Eliminar($datos))
			return false;
		
		return true;	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Funcion que publica el html del header y footer de una plantilla

// Parámetros de Entrada:
//			planthtmldesc: descripción de la plantilla html
//			planthtmladmin: html plantilla (admin)
//			planthtml: html plantilla (front)
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function PublicarHtml($datos,$codigo)
	{
		$archivoHeader = $this->prefijo_archivo_header.$codigo.$this->extension_archivos;
		$archivoFooter = $this->prefijo_archivo_footer.$codigo.$this->extension_archivos;
		FuncionesPHPLocal::GuardarArchivo(PUBLICA,$datos['planthtmlheader'],$archivoHeader);
		FuncionesPHPLocal::GuardarArchivo(PUBLICA,$datos['planthtmlfooter'],$archivoFooter);
		
		return true;
	}

//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//			planthtmldesc: descripción de la plantilla html
//			planthtmladmin: html plantilla (admin)
//			planthtml: html plantilla (front)
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if (!isset($datos['planthtmldesc']) || $datos['planthtmldesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar una descripcion.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset($datos['planthtmlheader']) || $datos['planthtmlheader']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar una plantilla html (Header).",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset($datos['planthtmlfooter']) || $datos['planthtmlfooter']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar una plantilla html (Footer).",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!isset($datos['planthtmldefault']) || $datos['planthtmldefault']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar si la plantilla es o no default.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//		formatodesc: descripción del formato
//			plantdesc: ancho del formato
//			planthtmlcod: codigo de la plantilla html

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarInsertar($datos)
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

	public function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if(!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un codigo de plantilla html.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    planthtmlcod: código de la plantilla html
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminar($datos)
	{
		if (!isset($datos['planthtmlcod']) || $datos['planthtmlcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un codigo de plantilla html.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un codigo de plantilla .",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if(!parent::BuscarPlantillasxPlanthtmlcod($datos,$resultado,$numfilas))
			return false;
		if ($numfilas>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la plantilla html tiene asociadas plantillas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}	
	
	
			
}//FIN CLASE

?>