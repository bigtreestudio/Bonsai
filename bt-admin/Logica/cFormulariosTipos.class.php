<?php  
include(DIR_CLASES_DB."cFormulariosTipos.db.php");

class cFormulariosTipos extends cFormulariosTiposdb	
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

// Parámetros de Entrada:
//	$datos = array asociativos
//		

// Retorna:
//		spnombre,sparam: nombre del stored procedures y parametros.
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function TiposFormulariosSP(&$spnombre,&$sparam)
	{
		if (!parent::TiposFormulariosSP($spnombre,$sparam))
			return false;
		return true;			
	}
	
// Retorna en un arreglo con los datos de los tipos de formularios 

// Parámetros de Entrada:
//			$datos = array asociativos
// 				formulariotipodesc: descripción del tipo de formulario
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		
		$sparam=array(
			'xformulariotipodesc'=> 0,
			'formulariotipodesc'=> "",
			'orderby'=> "formulariotipodesc ASC",
			'limit'=> ""
		);	
		
		if (isset ($datos['formulariotipodesc']) && $datos['formulariotipodesc']!="")
		{
			$sparam['formulariotipodesc']= $datos['formulariotipodesc'];
			$sparam['xformulariotipodesc']= 1;
		}	
	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			formulariotipocod: código del tipo de formulario 

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no


	function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;
	}

//----------------------------------------------------------------------------------------- 

// Eliminar un formato multimedia
// Parámetros de Entrada:
//		datos: arreglo de datos
//			formulariotipodesc: descripción del tipo de formulario

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
		return false;;
		
		if (!parent::Eliminar($datos))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un formato

// Parámetros de Entrada:
//			formulariotipodesc: descripción del tipo de formulario
//          formulariotipocod: código del tipo de formulario 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;
			
		if(!parent::Modificar($datos))
			return false;

		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
// Inserta nuevo formato

// Parámetros de Entrada:
//			formulariotipodesc: descripción del tipo de formulario
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$formulariocod)
	{	


		if (!$this->_ValidarInsertar($datos))
			return false;
			
		if(!parent::Insertar($datos,$formulariocod))
			return false;
		
		return true;
	} 
//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			formulariotipodesc: descripción del tipo de formulario

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			formulariotipodesc: descripción del tipo de formulario
//			formulariotipocod: código del tipo de formulario 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de formulario inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//			formulariotipodesc: descripción del tipo de formulario

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['formulariotipodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
			
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    formulariotipocod: código del tipo de formulario 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminar($datos)
	{

		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de formulario inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
	
		return true;
	}
	
}//FIN CLASS
?>