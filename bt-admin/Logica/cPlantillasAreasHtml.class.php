<?php  
include(DIR_CLASES_DB."cPlantillasAreasHtml.db.php");

class cPlantillasAreasHtml extends cPlantillasAreasHtmldb	
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

	
	public function TraerAreasHtml(&$resultado,&$numfilas)
	{
		if (!parent::TraerAreasHtml ($resultado,$numfilas))
			return false;
		return true;			
	}


	
	public function TraerAreasHtmlxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::TraerAreasHtmlxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}



	public function BuscarAreasxAreaHtml($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAreasxAreaHtml ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}




//----------------------------------------------------------------------------------------- 
// Inserta nuevo formato

// Parámetros de Entrada:
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



	public function Modificar($datos)
	{
		if (!$this->_ValidarDatosModificar($datos))
			return false;
		
		if(!parent::Modificar($datos))
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




	public function _ValidarDatosVacios($datos)
	{
		if ($datos['areahtmldesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un nombre del area.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}


	function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
			
		if (!$this->TraerAreasHtmlxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error area html inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
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
		
		if (!$this->TraerAreasHtmlxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error area html inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		

		if (!$this->BuscarAreasxAreaHtml($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error el area html contiene plantillas con areas asociadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}	

}
?>