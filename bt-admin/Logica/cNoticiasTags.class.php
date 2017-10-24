<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cNoticiasTags.db.php");

class cNoticiasTags extends cNoticiasTagsdb	
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
// Retorna una consulta con los tags de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarTagsSitemap($datos,&$resultado,&$numfilas)
	{
		if (!isset($datos['limit']))
			$datos['limit']="";
			
		if (!parent::BuscarTagsSitemap($datos,$resultado,$numfilas))
			return false;

		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los tags de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarTagsPredictivos($datos,&$resultado,&$numfilas)
	{
		if (!isset($datos['limit']))
			$datos['limit']="";
			
		if (!parent::BuscarTagsPredictivos($datos,$resultado,$numfilas))
			return false;

		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los tags de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarTagsxNoticia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarTagsxNoticia($datos,$resultado,$numfilas))
			return false;

		return true;
	}



// Actualiza los tags de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			noticiatag = tag de la noticia

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Actualizar($datos)
	{
		if(!$this->Eliminar($datos))
			return false;
		if (isset($datos['noticiatags']))
		{
			$tags = explode(",",$datos['noticiatags']);
			$datosinsertar['noticiacod'] = $datos['noticiacod'];
			foreach($tags as $tag)
			{
				if (trim($tag)!="")
				{
					$datosinsertar['noticiatag'] = trim($tag);
					if(!$this->Insertar($datosinsertar))
						return false;
				}
			}
		}
		return true;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar un nuevo tag a la noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			noticiacod: codigo de la noticia a insertar
//			noticiatag: tag de la noticia

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

// Insertar un nuevo tag a la noticia //NO VALIDA DATOS

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			noticiacod: codigo de la noticia a insertar
//			noticiatag: tag de la noticia

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarDuplicar($datos)
	{
		if (!parent::Insertar($datos))
			return false;

		return true;
	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar un nuevo tag a la noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			noticiacod: codigo de la noticia a eliminar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		if (!parent::Eliminar($datos))
			return false;

		return true;
	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos al momento de insertar una nuevo tag a la noticia

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

// function que valida los datos al momento de eliminar los tags de la noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
//			noticiacod: Valida que este seteada la noticia y que exista en la bd

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarEliminar($datos)
	{
		if (!isset($datos['noticiacod']) || $datos['noticiacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar una noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$oNoticia=new cNoticias($this->conexion,$this->formato);
		if(!$oNoticia->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar una noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// function que valida los datos obigatorios

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
//			noticiacod: Valida que este seteada la noticia y que exista en la bd
//			noticiatag: Valida que tag no este vacio

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarDatosVacios($datos)
	{
		if (!isset($datos['noticiacod']) || $datos['noticiacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar una noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$oNoticia=new cNoticias($this->conexion,$this->formato);
		if(!$oNoticia->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar una noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!isset($datos['noticiatag']) || $datos['noticiatag']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tag.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}		
		
		return true;
	}
	
	
}//fin clase	

?>